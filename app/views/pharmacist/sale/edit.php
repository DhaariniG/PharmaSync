<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order #POS-<?= e($order['order_id']) ?></title>
    <link rel="stylesheet" href="<?= role_css('Pharmacist', 'editSale.css') ?>">
    <script src="<?= asset('assets/js/lucide.min.js') ?>"></script>
</head>
<body>
    <div class="edit-card">
        <?php require __DIR__ . '/../partials/flash.php'; ?>
        <div class="edit-header">
            <div>
                <h2 class="edit-title">Edit Order Quantities</h2>
                <p class="edit-subtitle">Order #POS-<?= e($order['order_id']) ?> | Customer: <?= e($order['customer_name']) ?></p>
            </div>
            <a href="<?= url('/pharmacist/history') ?>" class="cancel-link">Cancel</a>
        </div>

        <form action="<?= url('/pharmacist/sales/' . (int) $order['order_id'] . '/update') ?>" method="POST">
            <?= csrf_field() ?>

            <table class="edit-table">
                <thead>
                    <tr>
                        <th>Medicine Item</th>
                        <th>Batch</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($order['items'])): ?>
                        <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td class="med-name-cell"><?= e($item['medicine_name']) ?></td>
                                <td>
                                    <span class="badge-batch"><?= e($item['batch_number']) ?></span>
                                </td>
                                <td>Rs. <?= number_format((float)$item['unit_price'], 2) ?></td>
                                <td>
                                    <input type="number" 
                                           name="items[<?= (int) $item['item_id'] ?>]" 
                                           value="<?= (int)$item['quantity'] ?>" 
                                           min="1" 
                                           class="qty-input" 
                                           required>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #94a3b8; padding: 20px;">
                                No items found for this order.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="form-footer">
                <p class="delta-notice">Stock batch quantities will adjust automatically (Delta Adjustment).</p>
                <button type="submit" class="btn-save-recalc">Save & Recalculate</button>
            </div>
        </form>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>