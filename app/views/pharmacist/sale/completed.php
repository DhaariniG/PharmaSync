<style>
/* Base Font & Layout Styling */
.receipt-card, 
.receipt-card * {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
}

/* Print Styles */
@media print {
    body * {
        visibility: hidden !important;
    }

    .receipt-actions {
        display: none !important;
    }

    .receipt-card, .receipt-card * {
        visibility: visible !important;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
    }

    .receipt-card {
        position: relative !important;
        margin: 20px auto !important;
        width: 100% !important;
        max-width: 480px !important;
        padding: 24px !important;
        box-shadow: none !important;
        border: 1px solid #cbd5e1 !important;
        background: #ffffff !important;
    }

    body, html {
        background: #ffffff !important;
        margin: 0 !important;
        padding: 0 !important;
    }
}
</style>

<div class="receipt-card" style="max-width: 520px; margin: 40px auto; background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
    
    <!-- Success Badge & Header -->
    <div class="receipt-header" style="text-align: center; margin-bottom: 24px;">
        <div class="check-icon" style="width: 56px; height: 56px; border-radius: 50%; border: 3px solid #00a884; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
            <i data-lucide="check" style="width: 32px; height: 32px; color: #00a884; stroke-width: 3;"></i>
        </div>
        <h2 style="font-size: 20px; font-weight: 700; color: #00a884; margin: 0 0 4px 0; letter-spacing: -0.3px;">Sale Successfully Recorded</h2>
        <p class="order-meta" style="font-size: 12px; color: #64748b; margin: 0;">Order #<?= e($order['order_id'] ?? '4') ?> | <?= e($order['created_at'] ?? date('Y-m-d H:i:s')) ?></p>
    </div>

    <!-- Customer Details -->
    <div class="receipt-details" style="font-size: 13px; color: #1e293b; line-height: 1.8; margin-bottom: 20px;">
        <p style="margin: 0;"><strong>Customer:</strong> <?= e($order['customer_name'] ?? 'Ms.D') ?></p>
        <p style="margin: 0;"><strong>Pharmacist:</strong> <?= e($order['pharmacist'] ?? 'Sarah Jenkins') ?></p>
        <p style="margin: 0;"><strong>Payment Method:</strong> <?= e($order['payment_method'] ?? 'Cash') ?></p>
    </div>

    <!-- Receipt Table -->
    <table class="receipt-table" style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 16px;">
        <thead>
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <th style="padding: 10px 0; text-align: left; font-weight: 700; color: #0f172a;">Medicine</th>
                <th style="padding: 10px 0; text-align: right; font-weight: 700; color: #0f172a;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($order['items'] ?? [] as $item): ?>
            <tr style="border-bottom: 1px solid #f1f5f9; color: #334155;">
                <td style="padding: 12px 0; font-weight: 600; text-align: left;"><?= e($item['name'] ?? $item['medicine_name'] ?? 'Paracetamol 500mg') ?></td>
                <td style="padding: 12px 0; text-align: right; font-weight: 600;">Rs. <?= number_format($item['subtotal'] ?? 25.00, 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Total Summary -->
    <div class="receipt-total" style="text-align: right; margin-bottom: 28px;">
        <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0;">Total: Rs. <?= number_format($order['total_amount'] ?? 25.00, 2) ?></h3>
    </div>

    <!-- Screen Buttons (Hidden in Print) -->
    <div class="receipt-actions" style="display: flex; gap: 16px; align-items: center;">
        <button type="button" onclick="window.print();" style="flex: 1; height: 42px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; font-weight: 700; color: #0f172a; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <i data-lucide="printer" style="width: 16px; height: 16px;"></i> Print Bill
        </button>
        <a href="<?= url('/pharmacist/dashboard') ?>" style="flex: 1; height: 42px; background: #ffffff; border: 1px solid #00766c; border-radius: 8px; font-size: 13px; font-weight: 700; color: #00766c; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <i data-lucide="layout-grid" style="width: 16px; height: 16px;"></i> New Sale
        </a>
    </div>

</div>