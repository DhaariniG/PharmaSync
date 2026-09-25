<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Inventory Control</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/tokens.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/chrome.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/pages.css" />
</head>
<body data-page="inventory" data-user-name="Admin User" data-user-role="Administrator" data-user-initials="AU">

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
          <h1>Inventory Control</h1>
          <p>Real-time pharmaceutical stock monitoring and batch management.</p>
        </div>
        <div class="page-head-actions">
          <a href="<?= BASE_URL ?>/admin/inventory/audit" class="btn btn-outline"><i data-lucide="file-bar-chart"></i> Generate Audit</a>
        </div>
      </div>

      <div class="callout-critical">
        <i data-lucide="triangle-alert"></i>
        <div>
          <div class="t">Critical Stock Alert</div>
          <div class="b">4 vaccine batches at Warehouse A are reaching expiration within 72 hours. Immediate redistribution or disposal required.</div>
        </div>
      </div>

      <div class="stat-grid cols-3">
        <div class="stat-card">
          <div class="stat-label" style="text-transform:uppercase;font-size:11.5px;font-weight:700;">Total SKUs <span class="badge badge-teal">+2.4%</span></div>
          <div class="stat-value">1,248</div>
        </div>
        <div class="stat-card">
          <div class="stat-label" style="text-transform:uppercase;font-size:11.5px;font-weight:700;">Low Stock Items <span class="badge badge-red">+12</span></div>
          <div class="stat-value">42</div>
        </div>
        <div class="stat-card">
          <div class="stat-label" style="text-transform:uppercase;font-size:11.5px;font-weight:700;">Expiring Batches (30D) <span class="badge badge-amber">Critical</span></div>
          <div class="stat-value">18</div>
        </div>
      </div>

      <div class="panel" style="margin-bottom:20px;">
        <div class="panel-header">
          <h2>Detailed Inventory Status</h2>
          <div style="display:flex;gap:8px;">
            <button class="icon-btn"><i data-lucide="filter"></i></button>
            <button class="icon-btn"><i data-lucide="download"></i></button>
          </div>
        </div>
        <table class="data-table">
          <thead>
            <tr><th>Batch ID</th><th>Product Name</th><th>Stock Qty</th><th>Expiration Date</th><th>Warehouse Location</th><th>Status</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <tr>
              <td style="color:var(--teal-700);font-weight:700;">#BT-99021-X</td>
              <td>Amoxicillin 500mg Caps</td>
              <td>4,500 Units</td>
              <td>Oct 12, 2025</td>
              <td><i data-lucide="map-pin" style="width:12px;height:12px;color:var(--ink-400);"></i> WH-Alpha-R4</td>
              <td><span class="badge badge-teal">Optimal</span></td>
              <td><a href="<?= BASE_URL ?>/admin/inventory/detail?id=BT-99021-X" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
            </tr>
            <tr>
              <td style="color:var(--teal-700);font-weight:700;">#BT-11204-Y</td>
              <td>Insulin Glargine 100U</td>
              <td style="color:var(--red-600);font-weight:700;">120 Units</td>
              <td>Aug 04, 2024</td>
              <td><i data-lucide="map-pin" style="width:12px;height:12px;color:var(--ink-400);"></i> Cold-Storage-C1</td>
              <td><span class="badge badge-red">Low Stock</span></td>
              <td><a href="<?= BASE_URL ?>/admin/inventory/detail?id=BT-11204-Y" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
            </tr>
            <tr>
              <td style="color:var(--teal-700);font-weight:700;">#BT-55670-Z</td>
              <td>Lisinopril 10mg Tabs</td>
              <td>12,800 Units</td>
              <td style="color:#b45309;font-weight:700;">Mar 15, 2024</td>
              <td><i data-lucide="map-pin" style="width:12px;height:12px;color:var(--ink-400);"></i> WH-Gamma-R12</td>
              <td><span class="badge badge-amber">Expiring Soon</span></td>
              <td><a href="<?= BASE_URL ?>/admin/inventory/detail?id=BT-55670-Z" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
            </tr>
            <tr>
              <td style="color:var(--teal-700);font-weight:700;">#BT-88432-A</td>
              <td>Metformin HCl 850mg</td>
              <td>8,200 Units</td>
              <td>Nov 22, 2026</td>
              <td><i data-lucide="map-pin" style="width:12px;height:12px;color:var(--ink-400);"></i> WH-Alpha-R9</td>
              <td><span class="badge badge-teal">Optimal</span></td>
              <td><a href="<?= BASE_URL ?>/admin/inventory/detail?id=BT-88432-A" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
            </tr>
            <tr>
              <td style="color:var(--teal-700);font-weight:700;">#BT-23419-B</td>
              <td>Atorvastatin 20mg</td>
              <td style="color:var(--red-600);font-weight:700;">150 Units</td>
              <td>Jan 30, 2025</td>
              <td><i data-lucide="map-pin" style="width:12px;height:12px;color:var(--ink-400);"></i> WH-Beta-R2</td>
              <td><span class="badge badge-red">Critical Stock</span></td>
              <td><a href="<?= BASE_URL ?>/admin/inventory/detail?id=BT-23419-B" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
            </tr>
          </tbody>
        </table>
        <div class="pager">
          <span>Showing 1-10 of 1,248 items</span>
          <div class="pager-btns">
            <button><i data-lucide="chevron-left" style="width:14px;height:14px;"></i></button>
            <button class="active">1</button>
            <button>2</button>
            <button>3</button>
            <button><i data-lucide="chevron-right" style="width:14px;height:14px;"></i></button>
          </div>
        </div>
      </div>

      <div class="body-grid" style="grid-template-columns: 1fr 320px;">
        

        
      </div>
    </main>
  </div>
</div>

<script src="<?= asset('assets/js/lucide.min.js') ?>"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
</body>
</html>
