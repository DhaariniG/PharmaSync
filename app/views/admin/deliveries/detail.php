<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Delivery Details</title>
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

      <div id="detailRoot"></div>

    </main>
  </div>
</div>

<script src="<?= asset('assets/js/lucide.min.js') ?>"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
<script>
const DELIVERIES = {
  '220': { dest:'Southside Community Clinic', driver:'Sarah Jenkins', initials:'SJ', badge:'badge-cyan', status:'Loading', eta:'11:15 AM' },
  '221': { dest:'University Research Lab', driver:'David Miller', initials:'DM', badge:'badge-gray', status:'Scheduled', eta:'1:00 PM' },
  '194': { dest:'North Hills Hospice', driver:'Elena Rodriguez', initials:'ER', badge:'badge-amber', status:'Delayed', eta:'11:30 AM*' },
  '195': { dest:"City Children's Hospital", driver:'Tom Baker', initials:'TB', badge:'badge-teal', status:'En Route', eta:'11:45 AM' },
};

const id = getParam('id') || '220';
const d = DELIVERIES[id] || DELIVERIES['220'];

document.getElementById('detailRoot').innerHTML = `
  <div class="page-head">
    <div>
      <h1>Delivery #${id}</h1>
      <p>${d.dest}</p>
    </div>
    <div class="page-head-actions">
      <span class="badge ${d.badge}">${d.status}</span>
    </div>
  </div>
  <div class="detail-grid">
    <div class="panel">
      <div class="detail-section">
        <h3>Delivery Details</h3>
        <div class="detail-row"><span class="k">Destination</span><span class="v">${d.dest}</span></div>
        <div class="detail-row"><span class="k">Driver</span><span class="v">${d.driver}</span></div>
        <div class="detail-row"><span class="k">Status</span><span class="v">${d.status}</span></div>
        <div class="detail-row"><span class="k">ETA</span><span class="v">${d.eta}</span></div>
      </div>
    </div>
    <div class="panel">
      <div class="panel-header"><h2>Actions</h2></div>
      <div class="action-list">
        <button class="btn btn-primary" id="deliveredBtn"><i data-lucide="check"></i> Mark Delivered</button>
        <button class="btn btn-outline" id="reassignBtn"><i data-lucide="user-cog"></i> Reassign Driver</button>
        <button class="btn btn-outline" id="contactBtn"><i data-lucide="phone"></i> Contact Driver</button>
      </div>
    </div>
  </div>
`;
if (window.lucide) lucide.createIcons();
document.getElementById('deliveredBtn').addEventListener('click', () => showToast('Delivery #' + id + ' marked delivered', '<?= BASE_URL ?>/admin/deliveries'));
document.getElementById('reassignBtn').addEventListener('click', () => showToast('Opening driver reassignment…'));
document.getElementById('contactBtn').addEventListener('click', () => showToast('Calling ' + d.driver + '…'));
</script>
</body>
</html>
