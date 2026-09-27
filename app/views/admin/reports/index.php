<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Reports &amp; Analytics</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/tokens.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/chrome.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/pages.css" />
</head>
<body data-page="reports" data-user-name="Admin User" data-user-role="Administrator" data-user-initials="AU">

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
          <h1>Reports &amp; Analytics</h1>
        </div>
        <div class="page-head-actions">
          <button class="btn btn-outline" id="exportCsvBtn"><i data-lucide="download"></i> Export CSV</button>
          <button class="btn btn-dark" id="generatePdfBtn"><i data-lucide="file-bar-chart"></i> Generate PDF Report</button>
        </div>
      </div>

      <div class="stat-grid cols-3">
        <div class="stat-card">
          <div class="stat-label">Fulfillment Accuracy</div>
          <div class="stat-value">98.4%</div>
          <div class="kpi-sub" style="margin-bottom:2px;">vs last month</div>
          <div class="mini-bars">
            <span style="height:38%"></span><span style="height:48%"></span><span style="height:55%"></span>
            <span style="height:50%"></span><span style="height:62%"></span><span style="height:58%"></span>
            <span style="height:70%"></span><span class="peak" style="height:100%"></span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Avg. Delivery Time</div>
          <div class="stat-value">4.2h</div>
          <div class="kpi-sub" style="margin-bottom:2px;">target: &lt; 4h</div>
          <div class="mini-bars">
            <span style="height:60%"></span><span style="height:64%"></span><span style="height:45%"></span>
            <span style="height:50%"></span><span style="height:40%"></span><span style="height:58%"></span>
            <span style="height:65%"></span><span class="peak" style="height:100%"></span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Inventory Value</div>
          <div class="stat-value">Rs.133,444,000</div>
          <div class="kpi-sub" style="margin-bottom:2px;">Total Assets</div>
          <div class="mini-bars">
            <span style="height:30%"></span><span style="height:38%"></span><span style="height:42%"></span>
            <span style="height:50%"></span><span style="height:60%"></span><span style="height:68%"></span>
            <span style="height:78%"></span><span class="peak" style="height:100%"></span>
          </div>
        </div>
      </div>

      <div class="body-grid">
        <div class="panel">
          <div class="panel-header">
            <h2>Inventory Value over Time</h2>
            <div class="report-tabs" id="reportTabs">
              <button data-range="7D">7D</button>
              <button class="active" data-range="30D">30D</button>
              <button data-range="90D">90D</button>
            </div>
          </div>
          <div class="chart-wrap">
            <svg viewBox="0 0 1000 300" width="100%" style="display:block;">
              <defs>
                <linearGradient id="areaFill" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stop-color="var(--teal-500)" stop-opacity="0.28" />
                  <stop offset="100%" stop-color="var(--teal-500)" stop-opacity="0" />
                </linearGradient>
              </defs>
              <line x1="0" y1="40" x2="1000" y2="40" stroke="var(--ink-100)" stroke-width="1" />
              <line x1="0" y1="110" x2="1000" y2="110" stroke="var(--ink-100)" stroke-width="1" />
              <line x1="0" y1="180" x2="1000" y2="180" stroke="var(--ink-100)" stroke-width="1" />
              <line x1="0" y1="250" x2="1000" y2="250" stroke="var(--ink-100)" stroke-width="1" />

              <path d="M0,225 C60,232 110,238 160,232 C230,222 260,140 320,95
                       C380,55 410,60 440,95 C480,140 500,205 520,225
                       C560,245 570,248 600,235 C660,205 690,90 750,55
                       C800,28 840,25 880,35 C920,45 950,60 1000,50
                       L1000,300 L0,300 Z" fill="url(#areaFill)" />
              <path d="M0,225 C60,232 110,238 160,232 C230,222 260,140 320,95
                       C380,55 410,60 440,95 C480,140 500,205 520,225
                       C560,245 570,248 600,235 C660,205 690,90 750,55
                       C800,28 840,25 880,35 C920,45 950,60 1000,50"
                    fill="none" stroke="var(--teal-700)" stroke-width="2.5" />
            </svg>
            <div class="chart-axis">
              <span>01 Oct</span><span>07 Oct</span><span>14 Oct</span><span>21 Oct</span><span>28 Oct</span>
            </div>
          </div>
        </div>

       
        </div>
      </div>
    </main>
  </div>
</div>

<script src="<?= asset('assets/js/lucide.min.js') ?>"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
<script>
document.getElementById('exportCsvBtn').addEventListener('click', () => showToast('CSV export started — check your downloads'));
document.getElementById('generatePdfBtn').addEventListener('click', () => showToast('Generating PDF report…'));
document.querySelectorAll('#reportTabs button').forEach((btn) => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('#reportTabs button').forEach((b) => b.classList.remove('active'));
    btn.classList.add('active');
    showToast('Showing ' + btn.dataset.range + ' view');
  });
});
</script>
</body>
</html>
