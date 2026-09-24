<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Audit Log</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="dashboard" data-page-title="Audit Log" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="On Shift" data-user-initials="AR">

<div class="app">
  <div id="sidebar-root"><?php require __DIR__ . '/../partials/sidebar.php'; ?></div>
  <div class="main">
    <div id="topbar-root"><?php require __DIR__ . '/../partials/topbar.php'; ?></div>

    <main class="content">
      <a class="back-link" href="<?= url('/deliveryPartner/dashboard') ?>">
        <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Back to Dashboard
      </a>

      <div class="page-header-row">
        <div>
          <h2 class="page-heading">Audit Log</h2>
          <p class="page-subheading">Full activity history for your account and deliveries.</p>
        </div>
      </div>

      <div class="card table-card">
        <div class="table-scroll">
          <table>
            <thead>
              <tr>
                <th>Timestamp</th>
                <th>Event</th>
                <th>Order</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr class="row-link" onclick="window.location='order-detail.php?id=PH-9021-A'">
                <td>2024-10-23 14:12</td>
                <td>Delivery picked up from Warehouse Alpha</td>
                <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-9021-A" onclick="event.stopPropagation()">#PH-9021-A</a></td>
                <td><span class="status-badge transit">In Transit</span></td>
              </tr>
              <tr class="row-link" onclick="window.location='order-detail.php?id=PH-8812-B'">
                <td>2024-10-23 13:45</td>
                <td>Delivery confirmed at Riverside Clinic</td>
                <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-8812-B" onclick="event.stopPropagation()">#PH-8812-B</a></td>
                <td><span class="status-badge delivered">Completed</span></td>
              </tr>
              <tr class="row-link" onclick="window.location='order-detail.php?id=CH-9921'">
                <td>Today, 14:22</td>
                <td>Temperature deviation flagged on vehicle unit #882</td>
                <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=CH-9921" onclick="event.stopPropagation()">#CH-9921</a></td>
                <td><span class="status-badge issue">Issue</span></td>
              </tr>
              <tr>
                <td>Today, 09:04</td>
                <td>Shift started &mdash; driving system activated</td>
                <td class="order-id muted">&mdash;</td>
                <td><span class="status-badge delivered">Logged</span></td>
              </tr>
              <tr>
                <td>Yesterday, 18:30</td>
                <td>Shift ended &mdash; 42 deliveries completed</td>
                <td class="order-id muted">&mdash;</td>
                <td><span class="status-badge delivered">Logged</span></td>
              </tr>
              <tr class="row-link" onclick="window.location='order-detail.php?id=PH-888442'">
                <td>Jun 27, 2024</td>
                <td>Hazmat delivery completed, tip received</td>
                <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-888442" onclick="event.stopPropagation()">#PH-888442</a></td>
                <td><span class="status-badge delivered">Completed</span></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="table-footer-note">Showing 6 of 214 logged events</div>
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
