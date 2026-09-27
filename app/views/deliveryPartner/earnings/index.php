<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Earnings</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="earnings" data-page-title="Earnings" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="On Shift" data-user-initials="AR">

<div class="app">
  <div id="sidebar-root"><?php require __DIR__ . '/../partials/sidebar.php'; ?></div>
  <div class="main">
    <div id="topbar-root"><?php require __DIR__ . '/../partials/topbar.php'; ?></div>

    <main class="content">

      <!-- Page header -->
      <div class="page-header-row">
        <div>
          <h2 class="page-heading">Earnings Overview</h2>
          <p class="page-subheading">Tracking your performance from June 01 &mdash; June 28</p>
        </div>
        <div class="welcome-actions">
          <a href="<?= url('/deliveryPartner/earnings-report') ?>" class="btn-outline">Export Report</a>
          <a href="<?= url('/deliveryPartner/withdraw') ?>" class="btn-primary">Withdraw Funds</a>
        </div>
      </div>

      <!-- Balance / payout cards -->
      <div class="metric-grid two-col">
        <div class="metric-card">
          <div class="metric-top">
            <span class="metric-label">TOTAL BALANCE</span>
            <svg class="icon" viewBox="0 0 24 24"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
          </div>
          <div class="metric-bottom">
            <span class="metric-value">Rs. 37,215.00</span>
          </div>
          <p class="metric-footnote">Available for withdrawal</p>
        </div>

        <div class="metric-card">
          <div class="metric-top">
            <span class="metric-label">NEXT PAYOUT</span>
            <span class="status-badge pending">Pending</span>
          </div>
          <div class="metric-bottom">
            <span class="metric-value">Rs. 9,360.00</span>
          </div>
          <p class="metric-footnote">July 02</p>
        </div>
      </div>

      <!-- Weekly trends -->
      <div class="card chart-card">
        <div class="card-header">
          <span class="card-title">Weekly Trends</span>
          <div class="legend">
            <div class="legend-item"><span class="legend-swatch"></span>Base Pay</div>
            <div class="legend-item"><span class="legend-swatch muted"></span></div>
          </div>
        </div>
        <div class="bars">
          <div class="bar-col"><span class="bar-value">Rs. 4000</span><div class="bar-fill" style="height:42%"></div><span class="bar-label">MON</span></div>
          <div class="bar-col"><span class="bar-value">Rs. 6000</span><div class="bar-fill" style="height:63%"></div><span class="bar-label">TUE</span></div>
          <div class="bar-col"><span class="bar-value">Rs. 5500</span><div class="bar-fill" style="height:58%"></div><span class="bar-label">WED</span></div>
          <div class="bar-col"><span class="bar-value">Rs. 8000</span><div class="bar-fill" style="height:84%"></div><span class="bar-label">THU</span></div>
          <div class="bar-col"><span class="bar-value">Rs. 4000</span><div class="bar-fill" style="height:48%"></div><span class="bar-label">FRI</span></div>
          <div class="bar-col"><span class="bar-value">Rs. 7500</span><div class="bar-fill" style="height:73%"></div><span class="bar-label">SAT</span></div>
          <div class="bar-col"><span class="bar-value">Rs. 3000</span><div class="bar-fill projected" style="height:33%"></div><span class="bar-label">SUN</span></div>
        </div>
      </div>

      <!-- Payment history + side panel -->
      <div class="lower-grid earnings-grid">

        <div class="card table-card">
          <div class="table-header">
            <span class="card-title">Payment History</span>
            <a href="<?= url('/deliveryPartner/payment-history') ?>" class="view-all">
              View All
              <svg class="icon" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
            </a>
          </div>

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
                  <td>Jun 28, 2024</td>
                  <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-889021" onclick="event.stopPropagation()">#PH-889021</a></td>
                  <td><span class="badge cold-chain">Cold Chain</span></td>
                  <td>Rs. 1500.00</td>
                  <td class="tip-cell">+Rs. 350.00</td>
                  <td><span class="status-badge delivered">Completed</span></td>
                </tr>
                <tr class="row-link" onclick="window.location='order-detail.php?id=PH-889104'">
                  <td>Jun 28, 2024</td>
                  <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-889104" onclick="event.stopPropagation()">#PH-889104</a></td>
                  <td><span class="badge standard">Standard</span></td>
                  <td>Rs. 600.00</td>
                  <td class="tip-cell muted">&mdash;</td>
                  <td><span class="status-badge pending">Pending</span></td>
                </tr>
                <tr class="row-link" onclick="window.location='order-detail.php?id=PH-888442'">
                  <td>Jun 27, 2024</td>
                  <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-888442" onclick="event.stopPropagation()">#PH-888442</a></td>
                  <td><span class="badge hazmat">Hazmat</span></td>
                  <td>Rs. 1900.00</td>
                  <td class="tip-cell">+Rs. 220.00</td>
                  <td><span class="status-badge delivered">Completed</span></td>
                </tr>
                <tr class="row-link" onclick="window.location='order-detail.php?id=PH-888320'">
                  <td>Jun 27, 2024</td>
                  <td class="order-id"><a class="order-id-link" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-888320" onclick="event.stopPropagation()">#PH-888320</a></td>
                  <td><span class="badge standard">Standard</span></td>
                  <td>Rs. 550.00</td>
                  <td class="tip-cell">+Rs. 100.00</td>
                  <td><span class="status-badge delivered">Completed</span></td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- mobile stacked view -->
          <div class="delivery-cards">
            <a class="delivery-card" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-889021">
              <div class="delivery-card-top">
                <span class="delivery-card-id">#PH-889021</span>
                <span class="badge cold-chain">Cold Chain</span>
              </div>
              <div class="delivery-card-row"><span>Date</span><span>Jun 22,024</span></div>
              <div class="delivery-card-row"><span>Base Pay / Tips</span><span>Rs. 13,500.00 &bull; +Rs. 3,750.00</span></div>
              <div class="delivery-card-row"><span>Status</span><span class="status-badge delivered">Completed</span></div>
            </a>
            <a class="delivery-card" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-889104">
              <div class="delivery-card-top">
                <span class="delivery-card-id">#PH-889104</span>
                <span class="badge standard">Standard</span>
              </div>
              <div class="delivery-card-row"><span>Date</span><span>Jun 28, 2024</span></div>
              <div class="delivery-card-row"><span>Base Pay / Tips</span><span>Rs. 6,600.00 &bull; &mdash;</span></div>
              <div class="delivery-card-row"><span>Status</span><span class="status-badge pending">Pending</span></div>
            </a>
            <a class="delivery-card" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-888442">
              <div class="delivery-card-top">
                <span class="delivery-card-id">#PH-888442</span>
                <span class="badge hazmat">Hazmat</span>
              </div>
              <div class="delivery-card-row"><span>Date</span><span>Jun 27, 2024</span></div>
              <div class="delivery-card-row"><span>Base Pay / Tips</span><span>Rs. 19,500.00 &bull; +Rs. 7,500.00</span></div>
              <div class="delivery-card-row"><span>Status</span><span class="status-badge delivered">Completed</span></div>
            </a>
            <a class="delivery-card" href="<?= url('/deliveryPartner/order-detail') ?>?id=PH-888320">
              <div class="delivery-card-top">
                <span class="delivery-card-id">#PH-888320</span>
                <span class="badge standard">Standard</span>
              </div>
              <div class="delivery-card-row"><span>Date</span><span>Jun 27, 2024</span></div>
              <div class="delivery-card-row"><span>Base Pay / Tips</span><span>Rs. 5,550.00 &bull; +Rs. 1,500.00</span></div>
              <div class="delivery-card-row"><span>Status</span><span class="status-badge delivered">Completed</span></div>
            </a>
          </div>
        </div>

        <div class="earnings-side">
          <div class="card side-card">
            <div class="card-header">
              <span class="card-title">Payout Method</span>
            </div>
            <div class="payout-method">
              <div class="payout-icon">
                <svg class="icon" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
              </div>
              <div>
                <p class="payout-name">Chase Business Checking</p>
                <p class="info-sub-standalone">Ending in &bull;&bull;&bull;&bull; 5590</p>
              </div>
            </div>
            <a href="<?= url('/deliveryPartner/add-payout-method') ?>" class="btn-outline full-width">+ Add New Method</a>
          </div>

          <div class="card side-card">
            <div class="card-header">
              <span class="card-title">Earnings Breakdown</span>
            </div>
            <div class="breakdown-list">
              <div class="breakdown-row">
                <span>Base Earnings</span>
                <span class="breakdown-value">Rs. 25,560.00</span>
              </div>
              <div class="breakdown-row">
                <span>Tips &amp; Gratuities</span>
                <span class="breakdown-value">Rs. 6090.00</span>
              </div>
              <div class="breakdown-row">
                <span>Performance Bonuses</span>
                <span class="breakdown-value">Rs. 5500.00</span>
              </div>
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
</body>
</html>