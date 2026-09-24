<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Global Supply Network</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/tokens.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/chrome.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/pages.css" />
</head>
<body data-page="suppliers" data-user-name="Admin User" data-user-role="Administrator" data-user-initials="AU">

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
          <h1>Global Supply Network</h1>
          <p>Manage 24 active vendor partnerships and logistics.</p>
        </div>
        <div class="page-head-actions">
          <a href="<?= BASE_URL ?>/admin/procurement/create" class="btn btn-primary" style="background:var(--teal-500);"><i data-lucide="shopping-cart"></i> New Procurement Action</a>
        </div>
      </div>

      <div class="stat-grid cols-3">
        <div class="stat-card">
          <div class="stat-label">Active Suppliers</div>
          <div class="stat-value" style="color:var(--teal-700);">128</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Avg. Lead Time</div>
          <div class="stat-value" style="color:var(--teal-700);">4.2 <span style="font-size:16px;font-weight:600;color:var(--ink-500);">Days</span></div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Pending Shipments <span class="badge badge-red">12 Urgent</span></div>
          <div class="stat-value">31</div>
        </div>
      </div>

      <div class="body-grid">
        <div class="panel">
          <div class="panel-header">
            <h2>Top Tier Suppliers</h2>
            <div style="display:flex;gap:8px;">
              <button class="icon-btn" style="border:none;"><i data-lucide="sliders-horizontal"></i></button>
              <button class="icon-btn" style="border:none;"><i data-lucide="more-vertical"></i></button>
            </div>
          </div>
          <table class="data-table">
            <thead>
              <tr><th>Vendor</th><th>Rating</th><th>Lead Time</th><th>Active POs</th><th>Actions</th></tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="vendor-cell">
                    <div class="vendor-badge">PF</div>
                    <div><div class="v-name">Pfizer Global</div><div class="v-sub">Primary Pharmaceutical</div></div>
                  </div>
                </td>
                <td><span class="rating"><i data-lucide="star"></i> 4.9</span></td>
                <td>2-3 Days</td>
                <td>12</td>
                <td><a href="<?= BASE_URL ?>/admin/suppliers/detail?id=PF" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
              </tr>
              <tr>
                <td>
                  <div class="vendor-cell">
                    <div class="vendor-badge">ME</div>
                    <div><div class="v-name">MedEquip Solutions</div><div class="v-sub">Medical Hardware</div></div>
                  </div>
                </td>
                <td><span class="rating"><i data-lucide="star"></i> 4.7</span></td>
                <td>5 Days</td>
                <td>8</td>
                <td><a href="<?= BASE_URL ?>/admin/suppliers/detail?id=ME" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
              </tr>
              <tr>
                <td>
                  <div class="vendor-cell">
                    <div class="vendor-badge">AP</div>
                    <div><div class="v-name">Apex Logistics</div><div class="v-sub">Shipping &amp; Logistics</div></div>
                  </div>
                </td>
                <td><span class="rating"><i data-lucide="star"></i> 4.2</span></td>
                <td>1 Day</td>
                <td>24</td>
                <td><a href="<?= BASE_URL ?>/admin/suppliers/detail?id=AP" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
              </tr>
              <tr>
                <td>
                  <div class="vendor-cell">
                    <div class="vendor-badge">SN</div>
                    <div><div class="v-name">SinoPharma</div><div class="v-sub">Global Chemicals</div></div>
                  </div>
                </td>
                <td><span class="rating"><i data-lucide="star"></i> 3.8</span></td>
                <td>14 Days</td>
                <td>4</td>
                <td><a href="<?= BASE_URL ?>/admin/suppliers/detail?id=SN" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="panel">
          <div class="panel-header"><h2>Recent Purchase Orders</h2></div>
          <div class="po-list">
            <div class="po-item transit">
              <div><div class="po-id">PO-2023-984</div><div class="po-sub">Pfizer &bull; $12,400.00</div></div>
              <span class="badge badge-cyan">Transit</span>
            </div>
            <div class="po-item pending">
              <div><div class="po-id">PO-2023-983</div><div class="po-sub">MedEquip &bull; $5,210.00</div></div>
              <span class="badge badge-amber">Pending</span>
            </div>
            <div class="po-item delivered">
              <div><div class="po-id">PO-2023-982</div><div class="po-sub">Apex &bull; $2,800.00</div></div>
              <span class="badge badge-gray">Delivered</span>
            </div>
            <div class="po-item delayed">
              <div><div class="po-id">PO-2023-981</div><div class="po-sub">SinoPharma &bull; $45,000.00</div></div>
              <span class="badge badge-red">Delayed</span>
            </div>
            <div class="po-item delivered">
              <div><div class="po-id">PO-2023-980</div><div class="po-sub">Pfizer &bull; $18,200.00</div></div>
              <span class="badge badge-gray">Delivered</span>
            </div>
          </div>
          <a href="<?= BASE_URL ?>/admin/suppliers" class="panel-footer-link">View All Procurement History</a>
        </div>
      </div>
    </main>
  </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
</body>
</html>
