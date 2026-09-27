<?php
/**
 * PhysicalSale - counter (walk-in) sales made by a pharmacist.
 */
class PhysicalSale extends Model
{
    protected function db(): ?PDO
    {
        return Database::getConnection();
    }

    public string $error = '';

    private const STATUS_SQL = "CASE
            WHEN p.payment_status = 'Refunded' THEN
                CASE WHEN po.notes LIKE '%[Refunded:%' THEN 'Refunded' ELSE 'Cancelled' END
            ELSE 'Completed'
        END";

    public function getAvailableStock(): array
    {
        return $this->fetchAll(
            "SELECT m.medicine_id, m.name, m.description, m.unit_price,
                    sb.batch_id, sb.batch_number, sb.quantity, sb.expiry_date
               FROM stock_batches sb
               JOIN medicines m ON sb.medicine_id = m.medicine_id
              WHERE sb.status = 'Available'
                AND m.status = 'Active'
                AND sb.quantity > 0
                AND sb.expiry_date >= CURDATE()
              ORDER BY sb.expiry_date ASC, m.name ASC"
        );
    }

    public function getAllPhysicalSales(): array
    {
        return $this->fetchAll(
            "SELECT po.order_id, po.customer_name, po.total_amount, po.notes, po.sale_date,
                    " . self::STATUS_SQL . " AS status,
                    u.full_name AS pharmacist_name,
                    p.payment_method
               FROM physical_orders po
               JOIN users u ON po.pharmacist_id = u.user_id
               LEFT JOIN payments p ON po.order_id = p.physical_order_id
              ORDER BY po.order_id DESC"
        );
    }

    public function getOrderDetails(int $orderId): ?array
    {
        $order = $this->fetchOne(
            "SELECT po.*,
                    " . self::STATUS_SQL . " AS status,
                    u.full_name AS pharmacist_name,
                    p.payment_method
               FROM physical_orders po
               JOIN users u ON po.pharmacist_id = u.user_id
               LEFT JOIN payments p ON po.order_id = p.physical_order_id
              WHERE po.order_id = :order_id",
            ['order_id' => $orderId]
        );

        if ($order === null) {
            return null;
        }

        $order['items'] = $this->fetchAll(
            "SELECT poi.*, m.name AS medicine_name, sb.batch_number
               FROM physical_order_items poi
               JOIN medicines m ON poi.medicine_id = m.medicine_id
               JOIN stock_batches sb ON poi.batch_id = sb.batch_id
              WHERE poi.order_id = :order_id
              ORDER BY poi.item_id",
            ['order_id' => $orderId]
        );

        return $order;
    }

    public function createSale(int $pharmacistId, string $customerName, array $items, string $paymentMethod, string $notes): ?int
    {
        $db = $this->db();

        try {
            $db->beginTransaction();

            $lines = [];
            $total = 0.00;

            foreach ($items as $item) {
                $batch = $this->fetchOne(
                    "SELECT sb.batch_id, sb.medicine_id, sb.quantity, sb.status, sb.expiry_date, m.unit_price
                       FROM stock_batches sb
                       JOIN medicines m ON sb.medicine_id = m.medicine_id
                      WHERE sb.batch_id = :batch_id
                        AND m.status = 'Active'",
                    ['batch_id' => $item['batch_id']]
                );

                if ($batch === null) {
                    throw new RuntimeException('One of the selected medicines does not exist or is no longer active.');
                }
                if ($batch['status'] !== 'Available' || $batch['expiry_date'] < date('Y-m-d')) {
                    throw new RuntimeException('One of the selected batches is expired or unavailable.');
                }

                $subtotal = $item['quantity'] * (float) $batch['unit_price'];
                $total   += $subtotal;

                $prescribedQty = null;
                if (!empty($item['prescribed_quantity'])) {
                    $prescribedQty = (int) $item['prescribed_quantity'];
                }

                $frequency = null;
                if (!empty($item['frequency'])) {
                    $frequency = trim($item['frequency']);
                }

                $lines[] = [
                    'medicine_id'         => (int) $batch['medicine_id'],
                    'batch_id'            => (int) $batch['batch_id'],
                    'quantity'            => $item['quantity'],
                    'prescribed_quantity' => $prescribedQty,
                    'frequency'           => $frequency,
                    'unit_price'          => (float) $batch['unit_price'],
                    'subtotal'            => $subtotal,
                ];
            }

            $orderId = $this->insertRow([
                'pharmacist_id' => $pharmacistId,
                'customer_name' => $customerName !== '' ? $customerName : 'Walk-in Customer',
                'total_amount'  => $total,
                'notes'         => $notes !== '' ? $notes : null,
            ], 'physical_orders');

            foreach ($lines as $line) {
                $this->insertRow($line + ['order_id' => $orderId], 'physical_order_items');

                if (!$this->takeStock($line['batch_id'], $line['quantity'])) {
                    throw new RuntimeException('Not enough stock left in one of the batches.');
                }

                $this->logStockChange($line['batch_id'], $pharmacistId, 'Sale', -$line['quantity'], $orderId, 'POS Counter Sale');
            }

            $this->insertRow([
                'physical_order_id' => $orderId,
                'payment_method'    => $paymentMethod,
                'collected_by'      => $pharmacistId,
                'amount'            => $total,
                'payment_status'    => 'Successful',
            ], 'payments');

            $db->commit();
            return $orderId;

        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $this->error = $e instanceof RuntimeException ? $e->getMessage() : 'The sale could not be saved.';
            error_log('PhysicalSale::createSale - ' . $e->getMessage());
            return null;
        }
    }

    public function cancelSale(int $orderId, int $userId, string $status, string $reason): bool
    {
        $db = $this->db();

        try {
            $db->beginTransaction();

            $payment = $this->fetchOne(
                "SELECT payment_status FROM payments WHERE physical_order_id = :order_id",
                ['order_id' => $orderId]
            );
            if ($payment === null) {
                throw new RuntimeException('That sale was not found.');
            }
            if ($payment['payment_status'] === 'Refunded') {
                throw new RuntimeException('That sale is already cancelled.');
            }

            $items = $this->fetchAll(
                "SELECT batch_id, quantity FROM physical_order_items WHERE order_id = :order_id",
                ['order_id' => $orderId]
            );

            foreach ($items as $item) {
                $this->returnStock((int) $item['batch_id'], (int) $item['quantity']);
                $this->logStockChange((int) $item['batch_id'], $userId, 'Return', (int) $item['quantity'], $orderId, "Order $status: $reason");
            }

            $this->exec(
                "UPDATE physical_orders
                    SET notes = CONCAT(IFNULL(notes, ''), ' [', :status_label, ': ', :reason, ']')
                  WHERE order_id = :order_id",
                ['status_label' => $status, 'reason' => $reason, 'order_id' => $orderId]
            );

            $this->exec(
                "UPDATE payments SET payment_status = 'Refunded' WHERE physical_order_id = :order_id",
                ['order_id' => $orderId]
            );

            $db->commit();
            return true;

        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $this->error = $e instanceof RuntimeException ? $e->getMessage() : 'The sale could not be cancelled.';
            error_log('PhysicalSale::cancelSale - ' . $e->getMessage());
            return false;
        }
    }

    public function updateQuantities(int $orderId, array $quantities, int $userId): bool
    {
        $db = $this->db();

        try {
            $db->beginTransaction();

            $payment = $this->fetchOne(
                "SELECT payment_status FROM payments WHERE physical_order_id = :order_id",
                ['order_id' => $orderId]
            );
            if ($payment === null) {
                throw new RuntimeException('That sale was not found.');
            }
            if ($payment['payment_status'] === 'Refunded') {
                throw new RuntimeException('A cancelled sale cannot be edited.');
            }

            foreach ($quantities as $itemId => $newQty) {
                $item = $this->fetchOne(
                    "SELECT item_id, batch_id, quantity, unit_price
                       FROM physical_order_items
                      WHERE item_id = :item_id AND order_id = :order_id",
                    ['item_id' => $itemId, 'order_id' => $orderId]
                );
                if ($item === null) {
                    throw new RuntimeException('One of the items does not belong to this sale.');
                }

                $delta = $newQty - (int) $item['quantity'];
                if ($delta === 0) {
                    continue;
                }

                if ($delta > 0) {
                    if (!$this->takeStock((int) $item['batch_id'], $delta)) {
                        throw new RuntimeException('Not enough stock left in that batch for the new quantity.');
                    }
                } else {
                    $this->returnStock((int) $item['batch_id'], -$delta);
                }

                $this->logStockChange((int) $item['batch_id'], $userId, 'Adjustment', -$delta, $orderId, 'POS Delta Quantity Adjustment');

                $this->exec(
                    "UPDATE physical_order_items SET quantity = :qty, subtotal = :subtotal WHERE item_id = :item_id",
                    ['qty' => $newQty, 'subtotal' => $newQty * (float) $item['unit_price'], 'item_id' => $itemId]
                );
            }

            $newTotal = (float) $this->fetchValue(
                "SELECT SUM(subtotal) FROM physical_order_items WHERE order_id = :order_id",
                ['order_id' => $orderId]
            );

            $this->exec("UPDATE physical_orders SET total_amount = :total WHERE order_id = :order_id",
                ['total' => $newTotal, 'order_id' => $orderId]);
            $this->exec("UPDATE payments SET amount = :total WHERE physical_order_id = :order_id",
                ['total' => $newTotal, 'order_id' => $orderId]);

            $db->commit();
            return true;

        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $this->error = $e instanceof RuntimeException ? $e->getMessage() : 'The quantities could not be saved.';
            error_log('PhysicalSale::updateQuantities - ' . $e->getMessage());
            return false;
        }
    }

    private function takeStock(int $batchId, int $qty): bool
    {
        $done = $this->exec(
            "UPDATE stock_batches SET quantity = quantity - :qty
              WHERE batch_id = :batch_id AND quantity >= :min_qty",
            ['qty' => $qty, 'min_qty' => $qty, 'batch_id' => $batchId]
        );

        $this->exec(
            "UPDATE stock_batches SET status = 'Depleted' WHERE batch_id = :batch_id AND quantity = 0",
            ['batch_id' => $batchId]
        );

        return $done > 0;
    }

    private function returnStock(int $batchId, int $qty): void
    {
        $this->exec(
            "UPDATE stock_batches
                SET quantity = quantity + :qty,
                    status = IF(expiry_date < CURDATE(), 'Expired', 'Available')
              WHERE batch_id = :batch_id",
            ['qty' => $qty, 'batch_id' => $batchId]
        );
    }

    private function logStockChange(int $batchId, int $userId, string $type, int $qty, int $orderId, string $notes): void
    {
        $this->insertRow([
            'batch_id'       => $batchId,
            'user_id'        => $userId,
            'change_type'    => $type,
            'quantity'       => $qty,
            'reference_id'   => $orderId,
            'reference_type' => 'Physical_Order',
            'notes'          => $notes,
        ], 'stock_changes');
    }
}