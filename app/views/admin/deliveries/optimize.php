<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Optimize Schedule</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/tokens.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/chrome.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/pages.css" />
</head>
<body data-page="deliveries" data-user-name="Admin User" data-user-role="Administrator" data-user-initials="AU">

<div class="app-shell">
  <div id="sidebar-root">
    <?php require APP_PATH . '/views/admin/partials/sidebar.php'; ?>
  </div>

  <div class="main-col">
    <div id="topbar-root">
      <?php require APP_PATH . '/views/admin/partials/topbar.php'; ?>
    </div>

    <main class="page-content">
      <a class="back-link" href="<?= BASE_URL ?>/admin/deliveries"><i data-lucide="arrow-left"></i> Back to Deliveries</a>

      <div class="page-head">
        <div>
          <h1>Optimize Schedule</h1>
          <p>Recalculated routing based on current driver availability and traffic conditions.</p>
        </div>
      </div>

      <div class="stat-grid cols-3">
        <div class="stat-card">
          <div class="stat-label">Avg. Delivery Time <span class="badge badge-green">-6m</span></div>
          <div class="stat-value">36m</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Routes Consolidated</div>
          <div class="stat-value">18</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Driver Utilization <span class="badge badge-teal">+9%</span></div>
          <div class="stat-value">95%</div>
        </div>
      </div>

      <div class="panel">
        <div class="panel-header"><h2>Proposed Changes</h2></div>
        <div class="log-item">
          <div class="log-icon teal"><i data-lucide="route"></i></div>
          <div class="log-body">
            <div class="log-top"><div class="log-title">Merge Southside &amp; North Hills routes</div><div class="log-time">Saves 14 mins</div></div>
            <div class="log-desc">Sarah Jenkins can cover both stops on a single route given current load.</div>
          </div>
        </div>
        <div class="log-item">
          <div class="log-icon amber"><i data-lucide="user-cog"></i></div>
          <div class="log-body">
            <div class="log-top"><div class="log-title">Reassign University Research Lab</div><div class="log-time">Saves 9 mins</div></div>
            <div class="log-desc">Reassign from David Miller to Tom Baker, who is already en route nearby.</div>
          </div>
        </div>
      </div>

      <div class="form-actions" style="justify-content:flex-start;padding:20px 0;">
        <button class="btn btn-primary" id="applyBtn"><i data-lucide="check"></i> Apply Optimized Schedule</button>
        <a href="<?= BASE_URL ?>/admin/deliveries" class="btn btn-outline">Discard</a>
      </div>

    </main>
  </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
<script>
document.getElementById('applyBtn').addEventListener('click', () => showToast('Optimized schedule applied', '<?= BASE_URL ?>/admin/deliveries'));
</script>
</body>
</html>
