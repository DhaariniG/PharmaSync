<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Privacy Policy</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="" data-page-title="Privacy Policy" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="On Shift" data-user-initials="AR">

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
          <h2 class="page-heading">Privacy Policy</h2>
          <p class="page-subheading">Last updated: October 2024</p>
        </div>
      </div>

      <div class="card">
        <div class="static-copy">
          <p>PharmaSync Driver Logistics collects the information needed to route deliveries safely and pay drivers accurately, including your account details, vehicle information, GPS location while on shift, and delivery/earnings history.</p>

          <h3>Information We Collect</h3>
          <ul>
            <li>Account details: name, email, phone number, driver ID</li>
            <li>Vehicle and compliance documents (license, insurance, certifications)</li>
            <li>Real-time location data while your shift is active</li>
            <li>Delivery and earnings history</li>
          </ul>

          <h3>How We Use It</h3>
          <ul>
            <li>To assign and route deliveries to the nearest available driver</li>
            <li>To calculate and process earnings and payouts</li>
            <li>To verify compliance documents remain valid</li>
            <li>To improve route planning and delivery reliability</li>
          </ul>

          <h3>Data Sharing</h3>
          <p>Delivery destination details are shared only with dispatch and the pharmacy fulfilling the order. Payout information is shared only with your selected financial institution to process withdrawals.</p>

          <h3>Your Rights</h3>
          <p>You can review or update your account information at any time from your Profile, and can request a copy or deletion of your data by contacting Support.</p>
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
</body>
</html>
