<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Payment History</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="earnings" data-page-title="Payment History" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="On Shift" data-user-initials="AR">

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
          <h2 class="page-heading">Payment History</h2>
          <p class="page-subheading">Every payout and delivery payment on your account.</p>
        </div>
        <a href="<?= url('/deliveryPartner/earnings-report') ?>" class="btn-outline">Export Report</a>
      </div>

      <div class="card table-card">
        <div class="table-scroll">
          <table>
            <thead>
              <tr>
                <th>Date</th>
                <th>Order ID</th>
                <th>Delivery Type</th>
                <th>Base Pay</th>
                <th>Tips</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr class="row-link" onclick="window.location='order-detail.php?id=PH-889021'">
                <td>Jun 28, 2024</td><td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-889021" onclick="event.stopPropagation()">#PH-889021</a></td>
                <td><span class="badge cold-chain">Cold Chain</span></td><td>$45.00</td><td class="tip-cell">+$12.50</td>
                <td><span class="status-badge delivered">Completed</span></td>
              </tr>
              <tr class="row-link" onclick="window.location='order-detail.php?id=PH-889104'">
                <td>Jun 28, 2024</td><td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-889104" onclick="event.stopPropagation()">#PH-889104</a></td>
                <td><span class="badge standard">Standard</span></td><td>$22.00</td><td class="tip-cell muted">&mdash;</td>
                <td><span class="status-badge pending">Pending</span></td>
              </tr>
              <tr class="row-link" onclick="window.location='order-detail.php?id=PH-888442'">
                <td>Jun 27, 2024</td><td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-888442" onclick="event.stopPropagation()">#PH-888442</a></td>
                <td><span class="badge hazmat">Hazmat</span></td><td>$65.00</td><td class="tip-cell">+$25.00</td>
                <td><span class="status-badge delivered">Completed</span></td>
              </tr>
              <tr class="row-link" onclick="window.location='order-detail.php?id=PH-888320'">
                <td>Jun 27, 2024</td><td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-888320" onclick="event.stopPropagation()">#PH-888320</a></td>
                <td><span class="badge standard">Standard</span></td><td>$18.50</td><td class="tip-cell">+$5.00</td>
                <td><span class="status-badge delivered">Completed</span></td>
              </tr>
              <tr>
                <td>Jun 26, 2024</td><td class="order-id muted">#PH-887190</td>
                <td><span class="badge standard">Standard</span></td><td>$19.75</td><td class="tip-cell">+$3.00</td>
                <td><span class="status-badge delivered">Completed</span></td>
              </tr>
              <tr>
                <td>Jun 25, 2024</td><td class="order-id muted">#PH-886904</td>
                <td><span class="badge cold-chain">Cold Chain</span></td><td>$52.00</td><td class="tip-cell">+$18.00</td>
                <td><span class="status-badge delivered">Completed</span></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="table-footer-note">Showing 6 of 214 payments</div>
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
</body>
</html>
