<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Withdraw Funds</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="earnings" data-page-title="Withdraw Funds" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="On Shift" data-user-initials="AR">

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
          <h2 class="page-heading">Withdraw Funds</h2>
          <p class="page-subheading">Transfer your available balance to a payout method.</p>
        </div>
      </div>

      <div class="lower-grid detail-grid">
        <form class="card" id="withdrawForm">
          <div class="form-panel form-grid">
            <div class="form-group full">
              <label for="amount">Amount to Withdraw</label>
              <input type="number" id="amount" min="1" max="1240.50" step="0.01" value="1240.50" required />
              <span class="hint">Available balance: $1,240.50</span>
            </div>
            <div class="form-group full">
              <label for="method">Payout Method</label>
              <select id="method">
                <option>Chase Business Checking &bull;&bull;&bull;&bull; 5590</option>
                <option>Add a new method&hellip;</option>
              </select>
            </div>
            <div class="form-group full">
              <label for="speed">Withdrawal Speed</label>
              <select id="speed">
                <option>Standard (1&ndash;2 business days) &mdash; Free</option>
                <option>Instant (within minutes) &mdash; 1.5% fee</option>
              </select>
            </div>
          </div>
          <div class="form-actions">
            <a href="<?= url('/deliveryPartner/earnings') ?>" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">Confirm Withdrawal</button>
          </div>
        </form>

        <div class="card side-card">
          <div class="card-header">
            <span class="card-title">Summary</span>
          </div>
          <div class="breakdown-list" style="padding:0 1.5rem 1.5rem;">
            <div class="breakdown-row">
              <span>Available Balance</span>
              <span class="breakdown-value">$1,240.50</span>
            </div>
            <div class="breakdown-row">
              <span>Next Scheduled Payout</span>
              <span class="breakdown-value">$312.00 &bull; Jul 02</span>
            </div>
          </div>
        </div>
      </div>

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
  document.getElementById('withdrawForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const amount = document.getElementById('amount').value;
    showToast('Withdrawal of $' + amount + ' initiated', '<?= url('/deliveryPartner/earnings') ?>');
  });
</script>
</body>
</html>
