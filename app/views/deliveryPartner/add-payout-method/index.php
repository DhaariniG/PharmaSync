<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Add Payout Method</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="earnings" data-page-title="Add Payout Method" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="On Shift" data-user-initials="AR">

<div class="app">
  <div id="sidebar-root"><?php require __DIR__ . '/../partials/sidebar.php'; ?></div>
  <div class="main">
    <div id="topbar-root"><?php require __DIR__ . '/../partials/topbar.php'; ?></div>

    <main class="content">
      <a class="back-link" href="<?= url('/deliveryPartner/earnings') ?>">
        <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Back to Earnings
      </a>

      <div class="page-header-row">
        <div>
          <h2 class="page-heading">Add Payout Method</h2>
          <p class="page-subheading">Connect a new bank account for withdrawals.</p>
        </div>
      </div>

      <form class="card" id="payoutForm">
        <div class="form-panel form-grid">
          <div class="form-group full">
            <label for="bankName">Bank Name</label>
            <input type="text" id="bankName" placeholder="e.g. Chase Business Checking" required />
          </div>
          <div class="form-group">
            <label for="accountHolder">Account Holder Name</label>
            <input type="text" id="accountHolder" placeholder="Full legal name" required />
          </div>
          <div class="form-group">
            <label for="accountType">Account Type</label>
            <select id="accountType">
              <option>Checking</option>
              <option>Savings</option>
            </select>
          </div>
          <div class="form-group">
            <label for="routingNumber">Routing Number</label>
            <input type="text" id="routingNumber" placeholder="9-digit routing number" required />
          </div>
          <div class="form-group">
            <label for="accountNumber">Account Number</label>
            <input type="text" id="accountNumber" placeholder="Account number" required />
          </div>
        </div>
        <div class="form-actions">
          <a href="<?= url('/deliveryPartner/earnings') ?>" class="btn-outline">Cancel</a>
          <button type="submit" class="btn-primary">Save Payout Method</button>
        </div>
      </form>

    </main>

    <footer class="app-footer">
      <div class="footer-left">
        <span class="footer-dot"></span>
        <span>PharmaSync Driver Logistics &bull; System status: All services operational</span>
      </div>
      <div class="footer-links">
        <a href="<?= url('/deliveryPartner/privacy') ?>">Privacy</a>
        <a href="<?= url('/deliveryPartner/terms') ?>">Terms</a>
        <a href="<?= url('/deliveryPartner/support') ?>">Contact Dispatch</a>
      </div>
    </footer>
  </div>
</div>

<script src="<?= asset('assets/js/delivery-partner.js') ?>"></script>
<script>
  document.getElementById('payoutForm').addEventListener('submit', function (e) {
    e.preventDefault();
    showToast('Payout method added', '<?= url('/deliveryPartner/earnings') ?>');
  });
</script>
</body>
</html>
