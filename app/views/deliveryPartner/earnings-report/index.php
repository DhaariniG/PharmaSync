<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Earnings Report</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
<style>
  @media print {
    .sidebar, .topbar, .app-footer, .back-link, .no-print { display: none !important; }
    .app, .main, .content { display: block !important; padding: 0 !important; }
  }
</style>
</head>
<body data-page="earnings" data-page-title="Earnings Report" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="On Shift" data-user-initials="AR">

<div class="app">
  <div id="sidebar-root"><?php require __DIR__ . '/../partials/sidebar.php'; ?></div>
  <div class="main">
    <div id="topbar-root"><?php require __DIR__ . '/../partials/topbar.php'; ?></div>

    <main class="content">
      <a class="back-link no-print" href="<?= url('/deliveryPartner/earnings') ?>">
        <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Back to Earnings
      </a>

      <div class="page-header-row">
        <div>
          <h2 class="page-heading">Earnings Report</h2>
          <p class="page-subheading">June 01 &mdash; June 28, 2024 &bull; Alex Rivero</p>
        </div>
        <button class="btn-primary no-print" onclick="window.print()">
          <svg class="icon" viewBox="0 0 24 24" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;vertical-align:-3px;margin-right:4px;"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
          Print / Save as PDF
        </button>
      </div>

      <div class="metric-grid two-col">
        <div class="metric-card">
          <div class="metric-top"><span class="metric-label">TOTAL EARNED</span></div>
          <div class="metric-bottom"><span class="metric-value">$1,240.50</span></div>
        </div>
        <div class="metric-card">
          <div class="metric-top"><span class="metric-label">TOTAL DELIVERIES</span></div>
          <div class="metric-bottom"><span class="metric-value">86</span></div>
        </div>
      </div>

      <div class="card table-card">
        <div class="table-header"><span class="card-title">Breakdown by Category</span></div>
        <div class="table-scroll">
          <table>
            <thead><tr><th>Category</th><th>Amount</th></tr></thead>
            <tbody>
              <tr><td>Base Earnings</td><td>$845.20</td></tr>
              <tr><td>Tips &amp; Gratuities</td><td>$210.30</td></tr>
              <tr><td>Performance Bonuses</td><td>$185.00</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card table-card">
        <div class="table-header"><span class="card-title">Payment Detail</span></div>
        <div class="table-scroll">
          <table>
            <thead>
              <tr><th>Date</th><th>Order ID</th><th>Type</th><th>Base Pay</th><th>Tips</th><th>Status</th></tr>
            </thead>
            <tbody>
              <tr><td>Jun 28, 2024</td><td class="order-id">#PH-889021</td><td><span class="badge cold-chain">Cold Chain</span></td><td>$45.00</td><td class="tip-cell">+$12.50</td><td><span class="status-badge delivered">Completed</span></td></tr>
              <tr><td>Jun 28, 2024</td><td class="order-id">#PH-889104</td><td><span class="badge standard">Standard</span></td><td>$22.00</td><td class="tip-cell muted">&mdash;</td><td><span class="status-badge pending">Pending</span></td></tr>
              <tr><td>Jun 27, 2024</td><td class="order-id">#PH-888442</td><td><span class="badge hazmat">Hazmat</span></td><td>$65.00</td><td class="tip-cell">+$25.00</td><td><span class="status-badge delivered">Completed</span></td></tr>
              <tr><td>Jun 27, 2024</td><td class="order-id">#PH-888320</td><td><span class="badge standard">Standard</span></td><td>$18.50</td><td class="tip-cell">+$5.00</td><td><span class="status-badge delivered">Completed</span></td></tr>
            </tbody>
          </table>
        </div>
        <div class="table-footer-note">Report generated <span id="reportTimestamp"></span> &bull; PharmaSync Driver Logistics</div>
      </div>

    </main>

    <footer class="app-footer no-print">
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
  document.getElementById('reportTimestamp').textContent = new Date().toLocaleString();
</script>
</body>
</html>
