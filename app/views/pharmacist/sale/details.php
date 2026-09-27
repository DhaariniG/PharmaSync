<div class="workspace-grid" style="max-width: 1000px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px;">
    
    <!-- Top Action & Info Bar -->
    <div style="background: #ffffff; padding: 14px 20px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; width: 100%; box-sizing: border-box;">
        
        <!-- Left: Back Button -->
        <a href="<?= url('/pharmacist/history') ?>" style="background: #f1f5f9; color: #334155; padding: 8px 16px; border-radius: 6px; border: 1px solid #cbd5e1; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap;">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Back to History
        </a>
        
        <!-- Center: Recorded On Date -->
        <div style="font-size: 13px; color: #64748b; font-weight: 500; text-align: center; white-space: nowrap;">
            Recorded on: <strong style="color: #0f172a; font-weight: 600;"><?= htmlspecialchars($order['sale_date'] ?? $order['created_at'] ?? 'N/A') ?></strong>
        </div>

        <!-- Right: Print Bill Button -->
        <a href="<?= url('/pharmacist/sales/' . $order['order_id'] . '/bill') ?>" target="_blank" style="background: #0d9488; color: #ffffff; padding: 8px 18px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; border: none; white-space: nowrap;">
            <i data-lucide="printer" style="width: 16px; height: 16px;"></i> Print Bill
        </a>
    </div>

    <!-- Metadata Cards -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
        <div class="card" style="background: #ffffff; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <span style="font-size: 11px; font-weight: 700; color: #64748b; display: block; margin-bottom: 4px;">CUSTOMER NAME</span>
            <strong style="font-size: 14px; color: #1e293b;"><?= htmlspecialchars($order['customer_name'] ?? 'Walk-in Customer') ?></strong>
        </div>
        <div class="card" style="background: #ffffff; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <span style="font-size: 11px; font-weight: 700; color: #64748b; display: block; margin-bottom: 4px;">PROCESSED BY</span>
            <strong style="font-size: 14px; color: #1e293b;"><?= htmlspecialchars($order['pharmacist_name'] ?? $order['pharmacist'] ?? 'Pharmacist') ?></strong>
        </div>
        <div class="card" style="background: #ffffff; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <span style="font-size: 11px; font-weight: 700; color: #64748b; display: block; margin-bottom: 4px;">PAYMENT METHOD</span>
            <strong style="font-size: 14px; color: #1e293b;"><?= htmlspecialchars($order['payment_method'] ?? 'Cash') ?></strong>
        </div>
    </div>

    <!-- Items Table Breakdown -->
    <div class="card" style="background: #ffffff; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
        <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px;">Order Items & Prescription Tracking</h3>
        
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #e2e8f0; font-size: 11px; font-weight: 700; color: #64748b;">
                    <th style="padding: 10px 12px; width: 28%;">MEDICINE NAME</th>
                    <th style="padding: 10px 12px; width: 12%;">BATCH</th>
                    <th style="padding: 10px 12px; text-align: center; width: 10%;">DISPENSED QTY</th>
                    <th style="padding: 10px 12px; text-align: center; width: 10%;">PRESCRIBED QTY</th>
                    <th style="padding: 10px 12px; width: 22%;">FREQUENCY / DOSAGE</th>
                    <th style="padding: 10px 12px; text-align: right; width: 9%;">UNIT PRICE</th>
                    <th style="padding: 10px 12px; text-align: right; width: 9%;">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($order['items'] ?? []) as $item): ?>
                <tr style="border-bottom: 1px solid #f1f5f9; font-size: 13px;">
                    <td style="padding: 12px; font-weight: 600; color: #1e293b; vertical-align: middle;">
                        <?= htmlspecialchars($item['medicine_name'] ?? $item['name'] ?? 'Unknown Medicine') ?>
                    </td>
                    <td style="padding: 12px; color: #64748b; vertical-align: middle;">
                        <span style="background: #fef08a; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700; color: #854d0e; display: inline-block;">
                            <?= htmlspecialchars($item['batch_number'] ?? 'N/A') ?>
                        </span>
                    </td>
                    <td style="padding: 12px; text-align: center; font-weight: 600; color: #1e293b; vertical-align: middle;">
                        <?= (int) ($item['quantity'] ?? 0) ?>
                    </td>
                    <td style="padding: 12px; text-align: center; color: #0d9488; font-weight: 600; vertical-align: middle;">
                        <?= !empty($item['prescribed_quantity']) ? (int) $item['prescribed_quantity'] : '-' ?>
                    </td>
                    <td style="padding: 12px; color: #334155; vertical-align: middle; line-height: 1.4;">
                        <?= htmlspecialchars($item['frequency'] ?? 'None specified') ?>
                    </td>
                    <td style="padding: 12px; text-align: right; color: #64748b; vertical-align: middle; white-space: nowrap;">
                        Rs. <?= number_format((float)($item['unit_price'] ?? 0), 2) ?>
                    </td>
                    <td style="padding: 12px; text-align: right; font-weight: 600; color: #0f172a; vertical-align: middle; white-space: nowrap;">
                        Rs. <?= number_format((float)($item['subtotal'] ?? 0), 2) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Order Total Section -->
        <div style="display: flex; justify-content: flex-end; margin-top: 24px; border-top: 2px solid #e2e8f0; padding-top: 16px;">
            <div style="text-align: right;">
                <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 2px;">Total Amount Paid</span>
                <span style="font-size: 22px; font-weight: 700; color: #0d9488;">Rs. <?= number_format((float)($order['total_amount'] ?? 0), 2) ?></span>
            </div>
        </div>
    </div>

    <?php if (!empty($order['notes'])): ?>
    <!-- Notes Card -->
    <div class="card" style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
        <span style="font-size: 11px; font-weight: 700; color: #64748b; display: block; margin-bottom: 4px;">NOTES / REMARKS</span>
        <p style="font-size: 13px; color: #334155; margin: 0;"><?= htmlspecialchars($order['notes']) ?></p>
    </div>
    <?php endif; ?>

</div>