<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sale Completed - Order #<?= $order['order_id'] ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Pharmacist/physicalSale.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .receipt-card {
            max-width: 650px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .success-banner {
            text-align: center;
            color: #00a494;
            margin-bottom: 25px;
        }
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .receipt-table th, .receipt-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        @media print {
            body * { visibility: hidden; }
            .receipt-card, .receipt-card * { visibility: visible; }
            .receipt-card { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="receipt-card">
        <div class="success-banner">
            <i data-lucide="check-circle" style="width: 48px; height: 48px;"></i>
            <h2>Sale Successfully Recorded</h2>
            <p style="color: #64748b;">Order #<?= $order['order_id'] ?> | <?= $order['sale_date'] ?></p>
        </div>

        <div style="margin-bottom: 15px;">
            <p><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
            <p><strong>Pharmacist:</strong> <?= htmlspecialchars($order['pharmacist_name']) ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method'] ?? 'Cash') ?></p>
        </div>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Batch</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['medicine_name']) ?></td>
                        <td><?= htmlspecialchars($item['batch_number']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>Rs. <?= number_format($item['unit_price'], 2) ?></td>
                        <td>Rs. <?= number_format($item['subtotal'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="text-align: right; margin-top: 15px;">
            <h3>Total: Rs. <?= number_format($order['total_amount'], 2) ?></h3>
        </div>

        <div class="no-print" style="margin-top: 30px; display: flex; gap: 15px; justify-content: center;">
            <button onclick="window.print()" class="btn btn-primary" style="padding: 10px 20px;">Print Bill</button>
            <a href="<?= BASE_URL ?>/index.php?url=pharmacist/dashboard/sales" class="btn btn-print-quotation" style="padding: 10px 20px; text-decoration: none;">New Sale</a>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>