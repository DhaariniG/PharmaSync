<?php

class PhysicalSale extends Model
{
    /** Fetch available stock sorted by FEFO (First Expiring, First Out) */
    public function getAvailableStock(): array
    {
        $sql = "
            SELECT 
                m.medicine_id,
                m.name,
                m.description,
                m.unit_price,
                sb.batch_id,
                sb.batch_number,
                sb.quantity,
                sb.expiry_date
            FROM stock_batches sb
            JOIN medicines m ON sb.medicine_id = m.medicine_id
            WHERE sb.status = 'Available' 
              AND sb.quantity > 0 
              AND sb.expiry_date >= CURDATE()
            ORDER BY sb.expiry_date ASC, m.name ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Record physical sale and deduct batch inventory in real time */
    public function createSale(
        int $pharmacistId, 
        ?string $customerName, 
        array $items, 
        string $paymentMethod = 'Cash', 
        ?string $notes = null, 
        ?string $prescriptionPath = null
    ) {
        try {
            $this->db->beginTransaction();

            $totalAmount = 0.00;
            foreach ($items as $item) {
                $qty = (int)($item['quantity'] ?? 1);
                $price = (float)($item['unit_price'] ?? 0);
                $totalAmount += ($qty * $price);
            }

            // Check if prescription_file column exists dynamically
            $hasPrescriptionCol = false;
            try {
                $checkCol = $this->db->query("SHOW COLUMNS FROM physical_orders LIKE 'prescription_file'");
                if ($checkCol && $checkCol->rowCount() > 0) {
                    $hasPrescriptionCol = true;
                }
            } catch (Exception $e) {
                $hasPrescriptionCol = false;
            }

            if ($hasPrescriptionCol) {
                $stmtOrder = $this->db->prepare("
                    INSERT INTO physical_orders (pharmacist_id, customer_name, total_amount, notes, prescription_file, sale_date)
                    VALUES (:pharmacist_id, :customer_name, :total_amount, :notes, :prescription_file, NOW())
                ");
                $stmtOrder->execute([
                    'pharmacist_id'     => $pharmacistId,
                    'customer_name'     => !empty($customerName) ? $customerName : 'Walk-in Customer',
                    'total_amount'      => $totalAmount,
                    'notes'              => $notes,
                    'prescription_file' => $prescriptionPath
                ]);
            } else {
                $stmtOrder = $this->db->prepare("
                    INSERT INTO physical_orders (pharmacist_id, customer_name, total_amount, notes, sale_date)
                    VALUES (:pharmacist_id, :customer_name, :total_amount, :notes, NOW())
                ");
                $stmtOrder->execute([
                    'pharmacist_id' => $pharmacistId,
                    'customer_name' => !empty($customerName) ? $customerName : 'Walk-in Customer',
                    'total_amount'  => $totalAmount,
                    'notes'          => $notes
                ]);
            }

            $orderId = (int)$this->db->lastInsertId();

            $stmtItem = $this->db->prepare("
                INSERT INTO physical_order_items (order_id, medicine_id, batch_id, quantity, unit_price, subtotal)
                VALUES (:order_id, :medicine_id, :batch_id, :quantity, :unit_price, :subtotal)
            ");

            $stmtDeduct = $this->db->prepare("
                UPDATE stock_batches 
                SET quantity = quantity - :qty1,
                    status = CASE WHEN (quantity - :qty2) <= 0 THEN 'Depleted' ELSE status END
                WHERE batch_id = :batch_id AND quantity >= :qty3
            ");

            $stmtStockChange = $this->db->prepare("
                INSERT INTO stock_changes (batch_id, user_id, change_type, quantity, reference_id, reference_type, notes)
                VALUES (:batch_id, :user_id, 'Sale', :quantity, :reference_id, 'Physical_Order', 'POS Counter Sale')
            ");

            foreach ($items as $item) {
                $medId     = (int)($item['medicine_id'] ?? 0);
                $batchId   = (int)($item['batch_id'] ?? 0);
                $qty       = (int)($item['quantity'] ?? 1);
                $unitPrice = (float)($item['unit_price'] ?? 0);
                $itemSubtotal = $qty * $unitPrice;

                if ($medId <= 0 || $batchId <= 0 || $qty <= 0) {
                    throw new Exception("Invalid item attributes.");
                }

                $stmtItem->execute([
                    'order_id'    => $orderId,
                    'medicine_id' => $medId,
                    'batch_id'    => $batchId,
                    'quantity'    => $qty,
                    'unit_price'  => $unitPrice,
                    'subtotal'    => $itemSubtotal
                ]);

                $stmtDeduct->execute([
                    'qty1'     => $qty,
                    'qty2'     => $qty,
                    'qty3'     => $qty,
                    'batch_id' => $batchId
                ]);

                if ($stmtDeduct->rowCount() === 0) {
                    throw new Exception("Stock deduction failed. Insufficient stock in batch #{$batchId}.");
                }

                $stmtStockChange->execute([
                    'batch_id'     => $batchId,
                    'user_id'      => $pharmacistId,
                    'quantity'     => -1 * abs($qty),
                    'reference_id' => $orderId
                ]);
            }

            $stmtPayment = $this->db->prepare("
                INSERT INTO payments (physical_order_id, payment_method, collected_by, amount, payment_status)
                VALUES (:order_id, :payment_method, :collected_by, :amount, 'Successful')
            ");
            $stmtPayment->execute([
                'order_id'       => $orderId,
                'payment_method' => in_array($paymentMethod, ['Cash', 'Card']) ? $paymentMethod : 'Cash',
                'collected_by'   => $pharmacistId,
                'amount'         => $totalAmount
            ]);

            $this->db->commit();
            return $orderId;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("POS Sale Error: " . $e->getMessage());
            return false;
        }
    }

    
    /** Cancel / Refund Sale: Soft delete by setting status and reverting stock */
    public function cancelSale(int $orderId, int $userId, string $status = 'Cancelled', string $cancellationReason = 'Pharmacist Voided'): bool
    {
        try {
            $this->db->beginTransaction();

            $stmtItems = $this->db->prepare("SELECT batch_id, quantity FROM physical_order_items WHERE order_id = :order_id");
            $stmtItems->execute(['order_id' => $orderId]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            $stmtRestoreStock = $this->db->prepare("
                UPDATE stock_batches 
                SET quantity = quantity + :qty, status = 'Available'
                WHERE batch_id = :batch_id
            ");

            $stmtStockChange = $this->db->prepare("
                INSERT INTO stock_changes (batch_id, user_id, change_type, quantity, reference_id, reference_type, notes)
                VALUES (:batch_id, :user_id, 'Return', :quantity, :reference_id, 'Physical_Order', :notes)
            ");

            foreach ($items as $item) {
                $stmtRestoreStock->execute(['qty' => $item['quantity'], 'batch_id' => $item['batch_id']]);
                $stmtStockChange->execute([
                    'batch_id'     => $item['batch_id'],
                    'user_id'      => $userId,
                    'quantity'     => $item['quantity'],
                    'reference_id' => $orderId,
                    'notes'        => "Order {$status}: " . $cancellationReason
                ]);
            }

            $targetStatus = in_array($status, ['Cancelled', 'Refunded']) ? $status : 'Cancelled';

            $hasStatusCol = false;
            try {
                $checkCol = $this->db->query("SHOW COLUMNS FROM physical_orders LIKE 'status'");
                if ($checkCol && $checkCol->rowCount() > 0) $hasStatusCol = true;
            } catch (Exception $e) { $hasStatusCol = false; }

            if ($hasStatusCol) {
                $stmtStatus = $this->db->prepare("
                    UPDATE physical_orders 
                    SET status = :status, 
                        notes = CONCAT(IFNULL(notes, ''), ' [', :status_label, ': ', :reason, ']')
                    WHERE order_id = :order_id
                ");
                $stmtStatus->execute([
                    'status'       => $targetStatus,
                    'status_label' => $targetStatus,
                    'reason'       => $cancellationReason,
                    'order_id'     => $orderId
                ]);
            } else {
                $stmtNotes = $this->db->prepare("
                    UPDATE physical_orders 
                    SET notes = CONCAT(IFNULL(notes, ''), ' [', :status_label, ': ', :reason, ']')
                    WHERE order_id = :order_id
                ");
                $stmtNotes->execute([
                    'status_label' => $targetStatus,
                    'reason'       => $cancellationReason,
                    'order_id'     => $orderId
                ]);
            }

            $stmtPayment = $this->db->prepare("UPDATE payments SET payment_status = 'Refunded' WHERE physical_order_id = :order_id");
            $stmtPayment->execute(['order_id' => $orderId]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            return false;
        }
    }

    /** Fetch live physical sales */
    public function getAllPhysicalSales(): array
    {
        $hasStatusCol = false;
        try {
            $checkCol = $this->db->query("SHOW COLUMNS FROM physical_orders LIKE 'status'");
            if ($checkCol && $checkCol->rowCount() > 0) $hasStatusCol = true;
        } catch (Exception $e) { $hasStatusCol = false; }

        $statusSelect = $hasStatusCol ? "po.status" : "'Completed' AS status";

        $sql = "
            SELECT 
                po.order_id,
                po.customer_name,
                po.total_amount,
                po.notes,
                po.sale_date AS sale_date,
                {$statusSelect},
                u.full_name AS pharmacist_name,
                p.payment_method
            FROM physical_orders po
            JOIN users u ON po.pharmacist_id = u.user_id
            LEFT JOIN payments p ON po.order_id = p.physical_order_id
            ORDER BY po.order_id DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Fetch live online prescriptions */
    public function getAllOnlinePrescriptions(): array
    {
        try {
            $sql = "
                SELECT 
                    prescription_id,
                    patient_name,
                    total_amount,
                    status,
                    created_at AS order_date,
                    payment_method
                FROM online_prescriptions
                ORDER BY prescription_id DESC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /** Fetch pending prescription queue */
    public function getPendingPrescriptionQueue(): array
    {
        try {
            $sql = "
                SELECT 
                    prescription_id,
                    patient_name,
                    created_at AS uploaded_date,
                    status,
                    priority
                FROM online_prescriptions
                WHERE status IN ('Pending', 'Under Review', 'Flagged')
                ORDER BY prescription_id DESC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /** Fetch details for single physical sale receipt */
    /** Fetch details for single physical sale receipt or edit view */
    public function getOrderDetails(int $orderId): ?array
    {
        $hasStatusCol = false;
        try {
            $checkCol = $this->db->query("SHOW COLUMNS FROM physical_orders LIKE 'status'");
            if ($checkCol && $checkCol->rowCount() > 0) $hasStatusCol = true;
        } catch (Exception $e) { $hasStatusCol = false; }

        $statusSelect = $hasStatusCol ? "po.status" : "'Completed' AS status";

        $stmt = $this->db->prepare("
            SELECT 
                po.*, 
                po.sale_date AS sale_date,
                {$statusSelect},
                u.full_name AS pharmacist_name, 
                p.payment_method
            FROM physical_orders po
            JOIN users u ON po.pharmacist_id = u.user_id
            LEFT JOIN payments p ON po.order_id = p.physical_order_id
            WHERE po.order_id = :order_id
        ");
        $stmt->execute(['order_id' => $orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) return null;

        // Fetch line items safely without assuming specific PK column names
        $stmtItems = $this->db->prepare("
            SELECT poi.*, 
                   m.name AS medicine_name, 
                   sb.batch_number
            FROM physical_order_items poi
            JOIN medicines m ON poi.medicine_id = m.medicine_id
            JOIN stock_batches sb ON poi.batch_id = sb.batch_id
            WHERE poi.order_id = :order_id
        ");
        $stmtItems->execute(['order_id' => $orderId]);
        $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }

    /** Update Order Quantities via Delta Adjustment using composite match */
    public function updateOrderQuantity(int $orderId, int $batchId, int $newQuantity, int $userId): bool
    {
        try {
            $this->db->beginTransaction();

            // Match by order_id + batch_id
            $stmtItem = $this->db->prepare("
                SELECT batch_id, quantity, unit_price 
                FROM physical_order_items 
                WHERE order_id = :order_id AND batch_id = :batch_id
            ");
            $stmtItem->execute([
                'order_id' => $orderId,
                'batch_id' => $batchId
            ]);
            $item = $stmtItem->fetch(PDO::FETCH_ASSOC);

            if (!$item) {
                $this->db->rollBack();
                return false;
            }

            $oldQuantity = (int)$item['quantity'];
            $unitPrice   = (float)$item['unit_price'];

            // Delta = New Quantity - Old Quantity
            $delta = $newQuantity - $oldQuantity;

            if ($delta === 0) {
                $this->db->commit();
                return true;
            }

            // Adjust inventory
            if ($delta > 0) {
                $stmtDeduct = $this->db->prepare("
                    UPDATE stock_batches 
                    SET quantity = quantity - :delta 
                    WHERE batch_id = :batch_id AND quantity >= :delta
                ");
                $stmtDeduct->execute(['delta' => $delta, 'batch_id' => $batchId]);

                if ($stmtDeduct->rowCount() === 0) {
                    throw new Exception("Insufficient stock available in batch #{$batchId}.");
                }
            } else {
                $absDelta = abs($delta);
                $stmtRestore = $this->db->prepare("
                    UPDATE stock_batches 
                    SET quantity = quantity + :abs_delta, status = 'Available' 
                    WHERE batch_id = :batch_id
                ");
                $stmtRestore->execute(['abs_delta' => $absDelta, 'batch_id' => $batchId]);
            }

            // Log stock change
            $stmtLog = $this->db->prepare("
                INSERT INTO stock_changes (batch_id, user_id, change_type, quantity, reference_id, reference_type, notes)
                VALUES (:batch_id, :user_id, 'Adjustment', :quantity, :reference_id, 'Physical_Order', 'POS Delta Quantity Adjustment')
            ");
            $stmtLog->execute([
                'batch_id'     => $batchId,
                'user_id'      => $userId,
                'quantity'     => -1 * $delta,
                'reference_id' => $orderId
            ]);

            // Update item subtotal
            $newSubtotal = $newQuantity * $unitPrice;
            $stmtUpdateItem = $this->db->prepare("
                UPDATE physical_order_items 
                SET quantity = :qty, subtotal = :subtotal 
                WHERE order_id = :order_id AND batch_id = :batch_id
            ");
            $stmtUpdateItem->execute([
                'qty'      => $newQuantity,
                'subtotal' => $newSubtotal,
                'order_id' => $orderId,
                'batch_id' => $batchId
            ]);

            // Recalculate grand total
            $stmtRecalc = $this->db->prepare("SELECT SUM(subtotal) FROM physical_order_items WHERE order_id = :order_id");
            $stmtRecalc->execute(['order_id' => $orderId]);
            $newGrandTotal = (float)$stmtRecalc->fetchColumn();

            $stmtUpdateOrder = $this->db->prepare("UPDATE physical_orders SET total_amount = :total WHERE order_id = :order_id");
            $stmtUpdateOrder->execute(['total' => $newGrandTotal, 'order_id' => $orderId]);

            $stmtUpdatePayment = $this->db->prepare("UPDATE payments SET amount = :total WHERE physical_order_id = :order_id");
            $stmtUpdatePayment->execute(['total' => $newGrandTotal, 'order_id' => $orderId]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Update Order Exception: " . $e->getMessage());
            return false;
        }
    }
}