<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Inventory Audit</title>
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
      <a class="back-link" href="<?= BASE_URL ?>/admin/inventory"><i data-lucide="arrow-left"></i> Back to Inventory</a>

      <div class="page-head">
        <div>
          <h1>Inventory Audit</h1>
          <p>Automated stock reconciliation across all warehouse locations.</p>
        </div>
      </div>

      <div class="stat-grid cols-3">
        <div class="stat-card">
          <div class="stat-label">SKUs Audited</div>
          <div class="stat-value">1,248</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Discrepancies Found <span class="badge badge-red">3</span></div>
          <div class="stat-value">3</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Accuracy Rate</div>
          <div class="stat-value">99.9%</div>
        </div>
      </div>

      <div class="panel">
        <div class="panel-header"><h2>Discrepancies</h2></div>
        <table class="data-table">
          <thead><tr><th>Batch ID</th><th>Product</th><th>Expected</th><th>Counted</th><th>Variance</th></tr></thead>
          <tbody>
            <tr>
              <td style="color:var(--teal-700);font-weight:700;">#BT-11204-Y</td>
              <td>Insulin Glargine 100U</td>
              <td>132 Units</td>
              <td>120 Units</td>
              <td style="color:var(--red-600);font-weight:700;">-12 Units</td>
            </tr>
            <tr>
              <td style="color:var(--teal-700);font-weight:700;">#BT-23419-B</td>
              <td>Atorvastatin 20mg</td>
              <td>160 Units</td>
              <td>150 Units</td>
              <td style="color:var(--red-600);font-weight:700;">-10 Units</td>
            </tr>
            <tr>
              <td style="color:var(--teal-700);font-weight:700;">#BT-88432-A</td>
              <td>Metformin HCl 850mg</td>
              <td>8,195 Units</td>
              <td>8,200 Units</td>
              <td style="color:var(--green-600);font-weight:700;">+5 Units</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="form-actions" style="justify-content:flex-start;padding:20px 0;">
        <button class="btn btn-primary" id="downloadBtn"><i data-lucide="download"></i> Download Full Report</button>
        <a href="<?= BASE_URL ?>/admin/inventory" class="btn btn-outline">Back to Inventory</a>
      </div>

    </main>
  </div>
</div>

<script src="<?= asset('assets/js/lucide.min.js') ?>"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
<script>
document.getElementById('downloadBtn').addEventListener('click', () => showToast('Audit report download started'));
</script>
</body>
</html>
