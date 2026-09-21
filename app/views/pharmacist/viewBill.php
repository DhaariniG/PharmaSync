<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Receipt - #POS-<?= $order['order_id'] ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Pharmacist/viewBill.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="receipt-card">
        <div class="bill-header">
            <div>
                <h2 class="pharmacy-title">PharmaSync Pharmacy</h2>
                <p class="pharmacy-subtitle">Official Sales Receipt</p>
            </div>
            <div class="bill-meta-right">
                <h3 class="order-id-label">#POS-<?= $order['order_id'] ?></h3>
                <p class="order-date-label"><?= date('M d, Y h:i A', strtotime($order['sale_date'])) ?></p>
            </div>
        </div>

        <div class="order-info-block">
            <p><strong>Customer Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
            <p><strong>Pharmacist:</strong> <?= htmlspecialchars($order['pharmacist_name']) ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method'] ?? 'Cash') ?></p>
            <p><strong>Status:</strong> <span class="status-badge-active"><?= htmlspecialchars($order['status'] ?? 'Completed') ?></span></p>
        </div>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Medicine Item</th>
                    <th>Batch</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td class="med-name-cell"><?= htmlspecialchars($item['medicine_name']) ?></td>
                        <td><span class="badge-batch"><?= htmlspecialchars($item['batch_number']) ?></span></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>Rs. <?= number_format($item['unit_price'], 2) ?></td>
                        <td class="subtotal-cell">Rs. <?= number_format($item['subtotal'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-summary-row">
            <h3 class="grand-total-label">Total Paid: Rs. <?= number_format($order['total_amount'], 2) ?></h3>
        </div>

        <div class="no-print receipt-actions">
            <a href="<?= BASE_URL ?>/index.php?url=pharmacist/dashboard/history" class="back-link">
                <i data-lucide="arrow-left" class="action-icon"></i> Back to History
            </a>
            <button onclick="window.print()" class="btn-print">
                <i data-lucide="printer" class="action-icon"></i> Print Receipt
            </button>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>