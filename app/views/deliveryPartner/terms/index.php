<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Terms of Service</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="" data-page-title="Terms of Service" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="On Shift" data-user-initials="AR">

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
          <h2 class="page-heading">Terms of Service</h2>
          <p class="page-subheading">Last updated: October 2024</p>
        </div>
      </div>

      <div class="card">
        <div class="static-copy">
          <p>These Terms govern your use of the PharmaSync Driver Logistics platform as an independent delivery partner. By accepting deliveries through the app, you agree to the terms below.</p>

          <h3>Driver Responsibilities</h3>
          <ul>
            <li>Maintain a valid driver license, vehicle insurance, and any required handling certifications</li>
            <li>Handle cold-chain and hazmat shipments according to posted handling instructions</li>
            <li>Report delivery issues, delays, or temperature deviations promptly through the app</li>
          </ul>

          <h3>Payments</h3>
          <ul>
            <li>Base pay and tips are calculated per completed delivery and shown in your Earnings dashboard</li>
            <li>Standard withdrawals are processed within 1&ndash;2 business days; instant withdrawals may incur a fee</li>
            <li>Payout methods must be verified before funds can be withdrawn</li>
          </ul>

          <h3>Account Standing</h3>
          <p>Repeated delivery incidents, expired compliance documents, or safety violations may result in suspension of your ability to accept new deliveries until resolved.</p>

          <h3>Changes to These Terms</h3>
          <p>We may update these Terms from time to time. Continued use of the app after changes take effect constitutes acceptance of the revised Terms.</p>
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
