<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Deliveries</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="deliveries" data-page-title="Deliveries" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="On Shift" data-user-initials="AR">

<div class="app">
  <div id="sidebar-root"><?php require __DIR__ . '/../partials/sidebar.php'; ?></div>
  <div class="main">
    <div id="topbar-root"><?php require __DIR__ . '/../partials/topbar.php'; ?></div>

    <main class="content">

      <!-- Page header -->
      <div class="page-header-row">
        <div>
          <h2 class="page-heading">Deliveries</h2>
          <p class="page-subheading">Real-time status monitoring for pharmacy logistics network.</p>
        </div>
        <a href="<?= url('/deliveryPartner/new-delivery') ?>" class="btn-primary">New Delivery</a>
      </div>

      <!-- Stat cards -->
      <div class="metric-grid three-col">
        <div class="metric-card">
          <div class="metric-top">
            <span class="metric-label">ACTIVE DELIVERIES</span>
            <svg class="icon" viewBox="0 0 24 24"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
          </div>
          <div class="metric-bottom">
            <span class="metric-value">24</span>
          </div>
        </div>

        <div class="metric-card">
          <div class="metric-top">
            <span class="metric-label">IN TRANSIT</span>
            <svg class="icon" viewBox="0 0 24 24"><path d="M12 2a5 5 0 015 5v3a5 5 0 01-10 0V7a5 5 0 015-5z"/></svg>
          </div>
          <div class="metric-bottom">
            <span class="metric-value">12</span>
          </div>
        </div>

        <div class="metric-card">
          <div class="metric-top">
            <span class="metric-label">PENDING</span>
            <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
          </div>
          <div class="metric-bottom">
            <span class="metric-value">03</span>
          </div>
        </div>
      </div>

      <!-- Deliveries table -->
      <div class="card table-card">
        <div class="table-header deliveries-table-header">
          <div class="delivery-tabs" id="deliveryTabs">
            <button class="tab-btn active" data-filter="all">All Deliveries</button>
            <button class="tab-btn" data-filter="pending">Pending</button>
            <button class="tab-btn" data-filter="transit">In Transit</button>
          </div>
          <button class="btn-outline" id="advFiltersBtn" aria-expanded="false">
            <svg class="icon" viewBox="0 0 24 24"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
            Advanced Filters
          </button>
        </div>

        <div class="form-panel form-grid" id="advFiltersPanel" style="display:none; border-bottom:1px solid var(--color-border);">
          <div class="form-group">
            <label for="filterPriority">Priority</label>
            <select id="filterPriority">
              <option value="">Any priority</option>
              <option value="urgent">Urgent</option>
              <option value="high">High</option>
              <option value="standard">Standard</option>
            </select>
          </div>
          <div class="form-group">
            <label for="filterDriver">Assigned Driver</label>
            <select id="filterDriver">
              <option value="">Any driver</option>
              <option value="MS">Michael S.</option>
              <option value="JL">Jessica L.</option>
              <option value="DA">David A.</option>
              <option value="RH">Robert H.</option>
              <option value="unassigned">Unassigned</option>
            </select>
          </div>
          <div class="form-group full">
            <label for="filterSearch">Search Destination / Patient</label>
            <input type="text" id="filterSearch" placeholder="e.g. Jonathan Edwards, Clinic B..." />
          </div>
        </div>

        <div class="table-scroll">
          <table>
            <thead>
              <tr>
                <th>Delivery ID</th>
                <th>Destination / Patient</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Assigned Driver</th>
                <th>ETA</th>
              </tr>
            </thead>
            <tbody>
              <tr data-status="issue" class="row-link" onclick="window.location='order-detail.php?id=PH-2024-8841'">
                <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-2024-8841" onclick="event.stopPropagation()">#PH-2024-8841</a></td>
                <td>
                  <div class="dest-cell">
                    <span class="dest-name">Jonathan Edwards</span>
                    <span class="dest-address">452 Oak Avenue, Medical Heights</span>
                  </div>
                </td>
                <td><span class="status-badge issue">Issue</span></td>
                <td><span class="priority-badge high">High</span></td>
                <td>
                  <div class="driver-chip">
                    <div class="driver-avatar">MS</div>
                    <span>Michael S.</span>
                  </div>
                </td>
                <td class="eta-cell muted">--:--</td>
              </tr>

              <tr data-status="transit" class="row-link" onclick="window.location='order-detail.php?id=PH-2024-8842'">
                <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-2024-8842" onclick="event.stopPropagation()">#PH-2024-8842</a></td>
                <td>
                  <div class="dest-cell">
                    <span class="dest-name">901 Bulk Way, West Sector</span>
                  </div>
                </td>
                <td><span class="status-badge transit">In Transit</span></td>
                <td><span class="priority-badge standard">Standard</span></td>
                <td>
                  <div class="driver-chip">
                    <div class="driver-avatar">JL</div>
                    <span>Jessica L.</span>
                  </div>
                </td>
                <td class="eta-cell">14:20 <span class="eta-sub">(9 min)</span></td>
              </tr>

              <tr data-status="pending" class="row-link" onclick="window.location='order-detail.php?id=PH-2024-8843'">
                <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-2024-8843" onclick="event.stopPropagation()">#PH-2024-8843</a></td>
                <td>
                  <div class="dest-cell">
                    <span class="dest-name">Dr. Amanda Lee</span>
                    <span class="dest-address">Clinic B, Suite 400</span>
                  </div>
                </td>
                <td><span class="status-badge pending">Pending</span></td>
                <td><span class="priority-badge standard">Standard</span></td>
                <td><span class="driver-unassigned">Unassigned</span></td>
                <td class="eta-cell muted">Scheduled: 15:00</td>
              </tr>

              <tr data-status="delivered" class="row-link" onclick="window.location='order-detail.php?id=PH-2024-8839'">
                <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-2024-8839" onclick="event.stopPropagation()">#PH-2024-8839</a></td>
                <td>
                  <div class="dest-cell">
                    <span class="dest-name">Robert Thompson</span>
                    <span class="dest-address">12 Silver Dr, Apt 2B</span>
                  </div>
                </td>
                <td><span class="status-badge delivered">Delivered</span></td>
                <td><span class="priority-badge standard">Standard</span></td>
                <td>
                  <div class="driver-chip">
                    <div class="driver-avatar">DA</div>
                    <span>David A.</span>
                  </div>
                </td>
                <td class="eta-cell">13:45 <span class="eta-sub">(Arrived)</span></td>
              </tr>

              <tr data-status="transit" class="row-link" onclick="window.location='order-detail.php?id=PH-2024-8840'">
                <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-2024-8840" onclick="event.stopPropagation()">#PH-2024-8840</a></td>
                <td>
                  <div class="dest-cell">
                    <span class="dest-name">City General Hospital</span>
                    <span class="dest-address">Emergency Intake Dock</span>
                  </div>
                </td>
                <td><span class="status-badge transit">In Transit</span></td>
                <td><span class="priority-badge urgent">Urgent</span></td>
                <td>
                  <div class="driver-chip">
                    <div class="driver-avatar">RH</div>
                    <span>Robert H.</span>
                  </div>
                </td>
                <td class="eta-cell">14:05 <span class="eta-sub">(3 min)</span></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="table-footer-note">Showing 5 of 128 active deliveries</div>
      </div>

      <!-- Alert cards -->
      <div class="alert-grid">
        

        <div class="info-alert-card notice">
          <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>
          <div>
            <p class="info-alert-title">3 Unassigned Deliveries</p>
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
  // Toggle the Advanced Filters panel
  document.getElementById('advFiltersBtn').addEventListener('click', function () {
    const panel = document.getElementById('advFiltersPanel');
    const open = panel.style.display !== 'none';
    panel.style.display = open ? 'none' : 'grid';
    this.setAttribute('aria-expanded', String(!open));
  });
</script>
</body>
</html>
