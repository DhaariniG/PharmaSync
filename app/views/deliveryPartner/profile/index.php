<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Profile</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="profile" data-page-title="Profile" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="Senior Delivery Partner" data-user-initials="SJ">

<div class="app">
  <div id="sidebar-root"><?php require __DIR__ . '/../partials/sidebar.php'; ?></div>
  <div class="main">
    <div id="topbar-root"><?php require __DIR__ . '/../partials/topbar.php'; ?></div>

    <main class="content">

      <!-- Profile header -->
      <div class="page-header-row">
        <div class="profile-identity">
          <div class="profile-avatar-lg">SJ</div>
          <div>
            <h2 class="page-heading">Kasun Bandara</h2>
            <p class="page-subheading"> &bull; Driver ID: #4829</p>
          </div>
        </div>
        <div class="welcome-actions">
          <a href="<?= url('/deliveryPartner/delivery-history') ?>" class="btn-outline">View History</a>
          <a href="<?= url('/deliveryPartner/edit-profile') ?>" class="btn-primary">Edit Profile</a>
        </div>
      </div>

      <!-- Rating / completion stats -->
      <div class="metric-grid two-col">
        <div class="metric-card">
          <div class="metric-top">
            <span class="metric-label">RATING</span>
            <svg class="icon" viewBox="0 0 24 24"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
          </div>
          <div class="metric-bottom">
            <span class="metric-value">4.9 / 5.0</span>
          </div>
        </div>

        <div class="metric-card">
          <div class="metric-top">
            <span class="metric-label">COMPLETION</span>
            <svg class="icon" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
          </div>
          <div class="metric-bottom">
            <span class="metric-value">98.4%</span>
          </div>
        </div>
      </div>

      <!-- Account + Vehicle info -->
      <div class="lower-grid profile-grid">
        <div class="card info-card">
          <div class="card-header">
            <span class="card-title">Account Information</span>
          </div>
          <div class="info-list">
            <div class="info-row">
              <span class="info-label">Full Name</span>
              <span class="info-value">Kasun Bandara</span>
            </div>
            <div class="info-row">
              <span class="info-label">Email Address</span>
              <span class="info-value">jbandara@gmail.com</span>
            </div>
            <div class="info-row">
              <span class="info-label">Phone Number</span>
              <span class="info-value">0779239600</span>
            </div>
            <div class="info-row">
              <span class="info-label">Joined Date</span>
              <span class="info-value">March 14, 2022</span>
            </div>
          </div>
        </div>

        <div class="card info-card">
          <div class="card-header">
            <span class="card-title">Vehicle Specifications</span>
          </div>
          <div class="info-list">
            <div class="info-row">
              <span class="info-label">Vehicle Type</span>
              <span class="info-value">Climate Controlled Van</span>
            </div>
            <div class="info-row">
              <span class="info-label">License Plate</span>
              <span class="info-value">CBK 6766<span class="info-sub">Registered: 2023</span></span>
            </div>
            <div class="info-row">
              <span class="info-label">Operating Region</span>
              <span class="info-value">Western Province</span>
            </div>
            <div class="info-row">
              <span class="info-label">Cargo Capacity</span>
              <span class="info-value">450 Units<span class="info-sub">Cold-chain Optimized</span></span>
            </div>
            <div class="info-row">
              <span class="info-label">Insurance Provider</span>
              <span class="info-value">Union Assurence<span class="badge-active">Active</span></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Monthly performance trends -->
      <div class="card chart-card">
        <div class="card-header">
          <span class="card-title">Monthly Performance Trends</span>
          <span class="tag-muted">Last 30 Days</span>
        </div>

        <div class="perf-grid">
          <div class="perf-item">
            <span class="perf-label">Deliveries</span>
            <span class="perf-value">248</span>
          </div>
          <div class="perf-item">
            <span class="perf-label">On-Time</span>
            <span class="perf-value">99.1%</span>
          </div>
          <div class="perf-item">
            <span class="perf-label">Distance</span>
            <span class="perf-value">1,842km</span>
          </div>
          <div class="perf-item">
            <span class="perf-label">Incidents</span>
            <span class="perf-value">0</span>
          </div>
        </div>

        <div class="mini-bars-wrap">
          <span class="mini-bars-label">&bull; current cycle</span>
          
        </div>
      </div>

      <!-- Compliance status -->
      <div class="card table-card compliance-card">
        <div class="table-header">
          <span class="card-title">Compliance Status</span>
          <a href="<?= url('/deliveryPartner/upload-document') ?>" class="btn-outline">
            <svg class="icon" viewBox="0 0 24 24"><path d="M12 3v12M7 8l5-5 5 5M5 21h14"/></svg>
            Upload New Document
          </a>
        </div>

        <div class="compliance-list">
          <a class="compliance-row row-link" href="<?= url('/deliveryPartner/upload-document') ?>?doc=driver-license">
            <div class="compliance-name">Driver License</div>
            <span class="status-badge delivered">Valid</span>
            <span class="compliance-exp">Exp: 10/2026</span>
          </a>
          <a class="compliance-row row-link" href="<?= url('/deliveryPartner/upload-document') ?>?doc=vehicle-insurance">
            <div class="compliance-name">Vehicle Insurance</div>
            <span class="status-badge delivered">Valid</span>
            <span class="compliance-exp">Exp: 01/2025</span>
          </a>
          <a class="compliance-row row-link" href="<?= url('/deliveryPartner/upload-document') ?>?doc=pharma-handling-cert">
            <div class="compliance-name">Pharma Handling Cert</div>
            <span class="status-badge pending">Renewal Due</span>
            <span class="compliance-exp">Exp: 03/2024</span>
          </a>
          <a class="compliance-row row-link" href="<?= url('/deliveryPartner/upload-document') ?>?doc=background-check">
            <div class="compliance-name">Background Check</div>
            <span class="status-badge delivered">Valid</span>
            <span class="compliance-exp">Verified: 2022</span>
          </a>
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
