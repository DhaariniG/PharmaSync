<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Driver Command Center</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="dashboard" data-page-title="Driver Command Center" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="On Shift" data-user-initials="AR">

<div class="app">
  <div id="sidebar-root"><?php require __DIR__ . '/../partials/sidebar.php'; ?></div>
  <div class="main">
    <div id="topbar-root"><?php require __DIR__ . '/../partials/topbar.php'; ?></div>

    <main class="content">

     
      <!-- Welcome header -->
      <div class="welcome">
        <div>
          <h2>Hello, Alex Rivero <a href="<?= url('/deliveryPartner/profile') ?>">Profile</a></h2>
          <p>Wednesday, October 23, 2024 &bull; Shift started 4h 12m ago</p>
        </div>
        <div class="welcome-actions">
          <div class="status-pill">
            <span class="dot"></span>
            <span>DRIVING SYSTEM ACTIVE</span>
          </div>
          <a href="<?= url('/deliveryPartner/new-delivery') ?>" class="btn-primary">New Delivery</a>
        </div>
      </div>

      <!-- Bento metric grid -->
      <div class="metric-grid">
        <div class="metric-card">
          <div class="metric-top">
            <span class="metric-label">TOTAL EARNINGS (THIS WEEK)</span>
            <svg class="icon" viewBox="0 0 24 24"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
          </div>
          <div class="metric-bottom">
            <span class="metric-value">$1,240.50</span>
          </div>
        </div>

        <div class="metric-card">
          <div class="metric-top">
            <span class="metric-label">ACTIVE TIME</span>
            <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
          </div>
          <div class="metric-bottom">
            <span class="metric-value">32.5h</span>
          </div>
        </div>

        <div class="metric-card">
          <div class="metric-top">
            <span class="metric-label">DRIVER RATING</span>
            <svg class="icon" viewBox="0 0 24 24"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
          </div>
          <div class="metric-bottom">
            <span class="metric-value">4.9/5</span>
            <div class="metric-stars">
              <span class="bar"></span><span class="bar"></span><span class="bar"></span><span class="bar"></span><span class="bar off"></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Lower grid: chart + route summary -->
      <div class="lower-grid">

        <div class="card chart-card">
          <div class="card-header">
            <span class="card-title">Weekly Activity Volume</span>
            <div class="legend">
              <div class="legend-item"><span class="legend-swatch"></span>Deliveries</div>
              <div class="legend-item"><span class="legend-swatch muted"></span>Hours</div>
            </div>
          </div>
          <div class="bars">
            <div class="bar-col"><div class="bar-fill" style="height:60%"></div><span class="bar-label">MON</span></div>
            <div class="bar-col"><div class="bar-fill" style="height:75%"></div><span class="bar-label">TUE</span></div>
            <div class="bar-col"><div class="bar-fill" style="height:90%"></div><span class="bar-label">WED</span></div>
            <div class="bar-col"><div class="bar-fill" style="height:45%"></div><span class="bar-label">THU</span></div>
            <div class="bar-col"><div class="bar-fill" style="height:80%"></div><span class="bar-label">FRI</span></div>
            <div class="bar-col"><div class="bar-fill projected" style="height:20%"></div><span class="bar-label">SAT</span></div>
            <div class="bar-col"><div class="bar-fill projected" style="height:10%"></div><span class="bar-label">SUN</span></div>
          </div>
        </div>

        <div class="card route-card">
          <div class="route-header">
            <span class="card-title">Daily Route Summary</span>
          </div>
          <div class="route-stat-row">
            <div class="ring">82%</div>
            <div class="route-stat-meta">
              <p>Progress</p>
              <p>37 / 45 Stops</p>
            </div>
          </div>

          <div class="progress-block">
            <div class="progress-labels">
              <span>PROGRESS</span>
              <span>82%</span>
            </div>
            <div class="progress-track">
              <div class="progress-fill"></div>
            </div>
          </div>

          <div class="cargo-block">
            <div class="cargo-row">
              <span class="cargo-label">REMAINING CARGO</span>
              <span class="cargo-value">8 UNITS</span>
            </div>
          </div>

          <div class="checklist">
            <div class="checklist-item">
              <svg class="icon" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
              Est. Completion: 17:45
            </div>
            <div class="checklist-item">
              <svg class="icon" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
              Next: City General Pharmacy
            </div>
          </div>
        </div>
      </div>

      <!-- Recent deliveries table -->
      <div class="card table-card">
        <div class="table-header">
          <span class="card-title">Recent Deliveries</span>
          <a href="<?= url('/deliveryPartner/audit-log') ?>" class="view-all">
            View Audit Log
            <svg class="icon" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
          </a>
        </div>

        <div class="table-scroll">
          <table>
            <thead>
              <tr>
                <th>Date</th>
                <th>Order ID</th>
                <th>Type</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr class="row-link" onclick="window.location='order-detail.php?id=PH-9021-A'">
                <td>2024-10-23 14:12</td>
                <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-9021-A" onclick="event.stopPropagation()">#PH-9021-A</a></td>
                <td><span class="badge cold-chain">Cold Chain</span></td>
                <td>
                  <div class="status-cell">
                    <span class="dot"></span>
                    <span>In Transit</span>
                  </div>
                </td>
                <td onclick="event.stopPropagation()">
                  <div class="dropdown-wrap">
                    <button class="row-menu dropdown-toggle" aria-label="More options"><svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg></button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-9021-A"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>View Details</a>
                      <a class="dropdown-item" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-9021-A#track"><svg viewBox="0 0 24 24"><path d="M12 2a5 5 0 015 5v3a5 5 0 01-10 0V7a5 5 0 015-5z"/></svg>Track Route</a>
                      <a class="dropdown-item" href="<?= url('/deliveryPartner/support') ?>"><svg viewBox="0 0 24 24"><path d="M12 9v4M12 17h.01M10.3 3.9L2.5 17a2 2 0 001.7 3h15.6a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/></svg>Report Issue</a>
                    </div>
                  </div>
                </td>
              </tr>
              <tr class="row-link" onclick="window.location='order-detail.php?id=PH-8812-B'">
                <td>2024-10-23 13:45</td>
                <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-8812-B" onclick="event.stopPropagation()">#PH-8812-B</a></td>
                <td><span class="badge standard">Standard</span></td>
                <td>
                  <div class="status-cell completed">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                    <span>Completed</span>
                  </div>
                </td>
                <td onclick="event.stopPropagation()">
                  <div class="dropdown-wrap">
                    <button class="row-menu dropdown-toggle" aria-label="More options"><svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg></button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-8812-B"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>View Details</a>
                      <a class="dropdown-item" href="<?= url('/deliveryPartner/earnings') ?>"><svg viewBox="0 0 24 24"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>View Payment</a>
                      <a class="dropdown-item" href="<?= url('/deliveryPartner/support') ?>"><svg viewBox="0 0 24 24"><path d="M12 9v4M12 17h.01M10.3 3.9L2.5 17a2 2 0 001.7 3h15.6a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/></svg>Report Issue</a>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- mobile stacked view -->
        <div class="delivery-cards">
          <a class="delivery-card" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-9021-A">
            <div class="delivery-card-top">
              <span class="delivery-card-id">#PH-9021-A</span>
              <span class="badge cold-chain">Cold Chain</span>
            </div>
            <div class="delivery-card-row"><span>Date</span><span>2024-10-23 14:12</span></div>
            <div class="delivery-card-row">
              <span>Status</span>
              <span class="status-cell"><span class="dot"></span>In Transit</span>
            </div>
          </a>
          <a class="delivery-card" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-8812-B">
            <div class="delivery-card-top">
              <span class="delivery-card-id">#PH-8812-B</span>
              <span class="badge standard">Standard</span>
            </div>
            <div class="delivery-card-row"><span>Date</span><span>2024-10-23 13:45</span></div>
            <div class="delivery-card-row">
              <span>Status</span>
              <span class="status-cell completed"><svg class="icon" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>Completed</span>
            </div>
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
