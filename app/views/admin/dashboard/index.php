<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Admin Dashboard</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/tokens.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/chrome.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/pages.css" />
</head>
<body data-page="dashboard" data-user-name="Admin User" data-user-role="Administrator" data-user-initials="AU">

<div class="app-shell">
  <div id="sidebar-root">
    <?php require APP_PATH . '/views/admin/partials/sidebar.php'; ?>
  </div>

  <div class="main-col">
    <div id="topbar-root">
      <?php require APP_PATH . '/views/admin/partials/topbar.php'; ?>
    </div>

    <main class="page-content">
      <div class="page-head">
        <div>
          <h1>Admin Terminal</h1>
          <p>Real-time pharmacy management and operational monitoring.</p>
        </div>
        <div class="page-head-actions">
          <a href="<?= BASE_URL ?>/admin/accounts/create" class="btn btn-primary"><i data-lucide="user-plus"></i> Create User</a>
          <a href="<?= BASE_URL ?>/admin/deliveries/create" class="btn btn-primary"><i data-lucide="send"></i> Assign Delivery</a>
          <a href="<?= BASE_URL ?>/admin/reports" class="btn btn-outline"><i data-lucide="file-bar-chart"></i> Generate Report</a>
          <a href="<?= BASE_URL ?>/admin/suppliers/create" class="btn btn-outline"><i data-lucide="truck"></i> Add Supplier</a>
        </div>
      </div>

      <div class="stat-grid cols-4">
        <div class="stat-card">
          <div class="stat-label">Total Users <i data-lucide="users" style="width:16px;height:16px;color:var(--teal-600)"></i></div>
          <div class="stat-value">12,842</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Active Customers <i data-lucide="user" style="width:16px;height:16px;color:var(--teal-600)"></i></div>
          <div class="stat-value">8,150</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Pharmacists <i data-lucide="briefcase-medical" style="width:16px;height:16px;color:var(--teal-600)"></i></div>
          <div class="stat-value">342</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Delivery Partners <i data-lucide="bike" style="width:16px;height:16px;color:var(--teal-600)"></i></div>
          <div class="stat-value">1,204</div>
        </div>
      </div>

      <div class="stat-grid cols-4">
        <div class="stat-card">
          <div class="stat-label">Pending Orders <i data-lucide="clipboard" style="width:16px;height:16px;color:#b45309"></i></div>
          <div class="stat-value">48</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Active Deliveries <i data-lucide="package" style="width:16px;height:16px;color:var(--teal-600)"></i></div>
          <div class="stat-value">156</div>
        </div>
        <div class="stat-card accent-red">
          <div class="stat-label warn">Low Stock Medicines <i data-lucide="triangle-alert" style="width:16px;height:16px;color:var(--red-600)"></i></div>
          <div class="stat-value">14</div>
        </div>
        <div class="stat-card accent-amber">
          <div class="stat-label warn" style="color:#92400e">Near Expiry <i data-lucide="hourglass" style="width:16px;height:16px;color:#b45309"></i></div>
          <div class="stat-value">27</div>
        </div>
      </div>

      <div class="body-grid">
        <div>
          <!-- Delivery Monitoring -->
          <div class="panel" style="margin-bottom:20px;">
            <div class="panel-header">
              <h2><i data-lucide="trending-up" style="width:18px;height:18px;color:var(--teal-600)"></i> Delivery Monitoring</h2>
              <a href="<?= BASE_URL ?>/admin/deliveries" style="font-size:13px;font-weight:700;color:var(--teal-700)">View All Deliveries</a>
            </div>
            <div class="tabs">
              <div class="tab"><span class="dot dot-gray"></span>Assigned (12)</div>
              <div class="tab"><span class="dot dot-gray"></span>Accepted (8)</div>
              <div class="tab active"><span class="dot dot-cyan"></span>In Transit (24)</div>
              <div class="tab"><span class="dot dot-gray"></span>Delivered (42)</div>
            </div>
            <div class="delivery-cards">
              <a class="delivery-card" href="<?= BASE_URL ?>/admin/orders/detail?id=ORD-9021">
                <div class="delivery-card-top"><span class="order-id">#ORD-9021</span></div>
                <div class="cust-name">Sarah J. Miller</div>
                <div class="cust-addr">422 Oak Street, Suite 10</div>
                <div class="meta"><i data-lucide="user"></i> ETA: 45m</div>
              </a>
              <a class="delivery-card" href="<?= BASE_URL ?>/admin/orders/detail?id=ORD-8843">
                <div class="delivery-card-top"><span class="order-id">#ORD-8843</span></div>
                <div class="cust-name">David Chen</div>
                <div class="cust-addr">15 King's Cross Road</div>
                <div class="meta"><i data-lucide="truck"></i> Driver: Mike R.</div>
              </a>
              <a class="delivery-card active-track" href="<?= BASE_URL ?>/admin/orders/detail?id=ORD-9110">
                <div class="delivery-card-top">
                  <span class="order-id">#ORD-9110</span>
                  <span class="badge badge-gray">Normal</span>
                </div>
                <div class="cust-name">Hospital Central</div>
                <div class="cust-addr">Medical Zone, Wing B</div>
                <div class="meta"><i data-lucide="navigation"></i> ETA: 12m</div>
              </a>
              <a class="delivery-card" href="<?= BASE_URL ?>/admin/orders/detail?id=ORD-7756">
                <div class="delivery-card-top">
                  <span class="order-id">#ORD-7756</span>
                  <span class="badge badge-red">Urgent</span>
                </div>
                <div class="cust-name">Anna Richards</div>
                <div class="cust-addr" style="display:flex;align-items:center;gap:5px;"><i data-lucide="check" style="width:12px;height:12px"></i> Delivered 5m ago</div>
              </a>
            </div>
          </div>

          <!-- Audit Logs -->
          <div class="panel">
            <div class="panel-header"><h2>Recent Audit Logs</h2></div>
            <div>
              <div class="log-item">
                <div class="log-icon teal"><i data-lucide="pencil"></i></div>
                <div class="log-body">
                  <div class="log-top">
                    <div class="log-title"><a href="<?= BASE_URL ?>/admin/inventory/detail?id=BT-99021-X">Update Stock: Amoxicillin 500mg</a></div>
                    <div class="log-time">Today, 14:22</div>
                  </div>
                  <div class="log-desc">Admin <b>Sarah J. Miller</b> increased inventory level by 500 units in <a href="<?= BASE_URL ?>/admin/inventory">Warehouse Alpha</a>.</div>
                  <div class="log-badges">
                    <span class="badge badge-teal">Inventory</span>
                    <span class="badge badge-green">Successful</span>
                  </div>
                </div>
              </div>
              <div class="log-item">
                <div class="log-icon amber"><i data-lucide="shield-alert"></i></div>
                <div class="log-body">
                  <div class="log-top">
                    <div class="log-title">System Access Warning</div>
                    <div class="log-time">Today, 11:05</div>
                  </div>
                  <div class="log-desc">Multiple failed login attempts from IP <span class="ip">192.168.1.104</span> targeting user r.taylor.</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- System alerts rail -->
        <div class="panel">
          <div class="panel-header">
            <h2>System Alerts</h2>
            <span class="dot dot-red" style="margin:0;"></span>
          </div>
          <div>
            <div class="alert-card crit">
              <div class="alert-title crit"><i data-lucide="pill"></i> Low Stock Alert</div>
              <div class="alert-body">Insulin Glargine (Solostar) is below safety threshold (<b>5 units left</b>).</div>
              <a href="<?= BASE_URL ?>/admin/procurement/create?item=Insulin+Glargine+(Solostar)" class="alert-btn crit" style="display:block;text-align:center;">Order Now</a>
            </div>
            <div class="alert-card warn">
              <div class="alert-title warn"><i data-lucide="calendar-clock"></i> Expiry Warning</div>
              <div class="alert-body">Batch #B-992 (Lisinopril) expires in <b>12 days</b>. 45 units remaining.</div>
            </div>
            <div class="alert-card info">
              <div class="alert-title" style="color:var(--ink-700)"><i data-lucide="shield"></i> Security Event</div>
              <div class="alert-body">New device login for User USR-1002 from London, UK.</div>
              <a href="<?= BASE_URL ?>/admin/accounts/detail?id=USR-1002" class="alert-btn warn" style="display:block;text-align:center;">Verify</a>
            </div>
            <div class="alert-card crit">
              <div class="alert-title crit"><i data-lucide="clock-alert"></i> Delivery Delayed</div>
              <div class="alert-body">Order <a href="<?= BASE_URL ?>/admin/orders/detail?id=ORD-4452" style="color:var(--red-600);text-decoration:underline;">#ORD-4452</a> flagged for address verification. Driver on hold.</div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<script src="<?= asset('assets/js/lucide.min.js') ?>"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
</body>
</html>
