<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Receipt - #POS-<?= (int) ($order['order_id'] ?? 4) ?></title>
    <link rel="stylesheet" href="<?= role_css('Pharmacist', 'viewBill.css') ?>">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* CSS to hide non-receipt elements during print */
        @media print {
            body, html {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            body * {
                visibility: hidden;
            }

            .receipt-card, .receipt-card * {
                visibility: visible;
            }

            .receipt-card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 20px !important;
                box-shadow: none !important;
                border: none !important;
            }

            .no-print, .receipt-actions {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-card">
        <?php require __DIR__ . '/../partials/flash.php'; ?>
        
        <div class="bill-header">
            <div>
                <h2 class="pharmacy-title">PharmaSync Pharmacy</h2>
                <p class="pharmacy-subtitle">Official Sales Receipt</p>
            </div>
            <div class="bill-meta-right">
                <h3 class="order-id-label">#POS-<?= (int) ($order['order_id'] ?? 4) ?></h3>
                <p class="order-date-label"><?= date('M d, Y h:i A', strtotime($order['sale_date'] ?? date('Y-m-d H:i:s'))) ?></p>
            </div>
        </div>

        <div class="order-info-block">
            <p><strong>Customer Name:</strong> <?= e($order['customer_name'] ?? 'Ms.D') ?></p>
            <p><strong>Pharmacist:</strong> <?= e($order['pharmacist_name'] ?? 'Sarah Jenkins') ?></p>
            <p><strong>Payment Method:</strong> <?= e($order['payment_method'] ?? 'Cash') ?></p>
            <p><strong>Status:</strong> <span class="status-badge-active"><?= e($order['status'] ?? 'Completed') ?></span></p>
        </div>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Medicine Item</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($order['items'] ?? []) as $item): ?>
                    <tr>
                        <td class="med-name-cell"><?= e($item['medicine_name'] ?? $item['name'] ?? 'Paracetamol 500mg') ?></td>
                        <td class="subtotal-cell" style="text-align: right;">Rs. <?= number_format($item['subtotal'] ?? 25.00, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-summary-row">
            <h3 class="grand-total-label">Total Paid: Rs. <?= number_format($order['total_amount'] ?? 25.00, 2) ?></h3>
        </div>

        <div class="no-print receipt-actions">
            <a href="<?= url('/pharmacist/dashboard') ?>" class="back-link">
                <i data-lucide="arrow-left" class="action-icon"></i> Back to Dashboard
            </a>
            <button onclick="window.print()" class="btn-print">
                <i data-lucide="printer" class="action-icon"></i> Print Receipt
            </button>
        </div>
    </div>

    <script>
        if (window.lucide) {
            lucide.createIcons();
        }
    </script>
</body>
</html>