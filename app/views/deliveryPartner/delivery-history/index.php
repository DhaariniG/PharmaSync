<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Delivery History</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="profile" data-page-title="Delivery History" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="Senior Delivery Partner" data-user-initials="SJ">

<div class="app">
  <div id="sidebar-root"><?php require __DIR__ . '/../partials/sidebar.php'; ?></div>
  <div class="main">
    <div id="topbar-root"><?php require __DIR__ . '/../partials/topbar.php'; ?></div>

    <main class="content">
      <a class="back-link" href="<?= url('/deliveryPartner/profile') ?>">
        <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Back to Profile
      </a>

      <div class="page-header-row">
        <div>
          <h2 class="page-heading">Delivery History</h2>
          <p class="page-subheading">Full delivery record for Sarah Jenkins &bull; Driver ID: #4829</p>
        </div>
      </div>

      <div class="metric-grid three-col">
        <div class="metric-card">
          <div class="metric-top"><span class="metric-label">TOTAL DELIVERIES</span></div>
          <div class="metric-bottom"><span class="metric-value">3,412</span></div>
        </div>
        <div class="metric-card">
          <div class="metric-top"><span class="metric-label">ON-TIME RATE</span></div>
          <div class="metric-bottom"><span class="metric-value">99.1%</span></div>
        </div>
        <div class="metric-card">
          <div class="metric-top"><span class="metric-label">INCIDENTS</span></div>
          <div class="metric-bottom"><span class="metric-value">0</span></div>
        </div>
      </div>

      <div class="card table-card">
        <div class="table-scroll">
          <table>
            <thead>
              <tr>
                <th>Date</th>
                <th>Order ID</th>
                <th>Destination</th>
                <th>Type</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr class="row-link" onclick="window.location='order-detail.php?id=PH-9021-A'">
                <td>2024-10-23 14:12</td>
                <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-9021-A" onclick="event.stopPropagation()">#PH-9021-A</a></td>
                <td>City General Pharmacy</td>
                <td><span class="badge cold-chain">Cold Chain</span></td>
                <td><span class="status-badge transit">In Transit</span></td>
              </tr>
              <tr class="row-link" onclick="window.location='order-detail.php?id=PH-8812-B'">
                <td>2024-10-23 13:45</td>
                <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-8812-B" onclick="event.stopPropagation()">#PH-8812-B</a></td>
                <td>Riverside Clinic</td>
                <td><span class="badge standard">Standard</span></td>
                <td><span class="status-badge delivered">Completed</span></td>
              </tr>
              <tr class="row-link" onclick="window.location='order-detail.php?id=PH-2024-8839'">
                <td>2024-10-22 13:45</td>
                <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-2024-8839" onclick="event.stopPropagation()">#PH-2024-8839</a></td>
                <td>Robert Thompson</td>
                <td><span class="badge standard">Standard</span></td>
                <td><span class="status-badge delivered">Delivered</span></td>
              </tr>
              <tr>
                <td>2024-10-21 11:02</td>
                <td class="order-id muted">#PH-8712-C</td>
                <td>Pioneer Rehab Center</td>
                <td><span class="badge standard">Standard</span></td>
                <td><span class="status-badge delivered">Delivered</span></td>
              </tr>
              <tr>
                <td>2024-10-20 09:30</td>
                <td class="order-id muted">#PH-8699-A</td>
                <td>Metro Health Hub</td>
                <td><span class="badge hazmat">Hazmat</span></td>
                <td><span class="status-badge delivered">Delivered</span></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="table-footer-note">Showing 5 of 3,412 deliveries</div>
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
