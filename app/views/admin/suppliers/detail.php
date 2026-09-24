<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Supplier Details</title>
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
      <a class="back-link" href="<?= BASE_URL ?>/admin/suppliers"><i data-lucide="arrow-left"></i> Back to Suppliers</a>

      <div id="detailRoot"></div>

    </main>
  </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
<script>
const SUPPLIERS = {
  'PF': { name:'Pfizer Global', sub:'Primary Pharmaceutical', rating:'4.9', leadTime:'2-3 Days', activePOs:'12' },
  'ME': { name:'MedEquip Solutions', sub:'Medical Hardware', rating:'4.7', leadTime:'5 Days', activePOs:'8' },
  'AP': { name:'Apex Logistics', sub:'Shipping &amp; Logistics', rating:'4.2', leadTime:'1 Day', activePOs:'24' },
  'SN': { name:'SinoPharma', sub:'Global Chemicals', rating:'3.8', leadTime:'14 Days', activePOs:'4' },
};

const id = getParam('id') || 'PF';
const s = SUPPLIERS[id] || SUPPLIERS['PF'];

document.getElementById('detailRoot').innerHTML = `
  <div class="detail-grid">
    <div class="panel">
      <div class="detail-header">
        <div class="avatar" style="background:var(--teal-100);color:var(--teal-700);border-radius:9px;">${id}</div>
        <div>
          <h2>${s.name}</h2>
          <div class="sub">${s.sub}</div>
        </div>
      </div>
      <div class="detail-section">
        <h3>Vendor Details</h3>
        <div class="detail-row"><span class="k">Rating</span><span class="v">★ ${s.rating}</span></div>
        <div class="detail-row"><span class="k">Lead Time</span><span class="v">${s.leadTime}</span></div>
        <div class="detail-row"><span class="k">Active Purchase Orders</span><span class="v">${s.activePOs}</span></div>
      </div>
    </div>
    <div class="panel">
      <div class="panel-header"><h2>Actions</h2></div>
      <div class="action-list">
        <a href="<?= BASE_URL ?>/admin/procurement/create?vendor=${encodeURIComponent(s.name)}" class="btn btn-primary"><i data-lucide="shopping-cart"></i> New Purchase Order</a>
        <button class="btn btn-outline" id="editBtn"><i data-lucide="pencil"></i> Edit Vendor</button>
        <button class="btn btn-outline" id="deactivateBtn"><i data-lucide="ban"></i> Deactivate Vendor</button>
      </div>
    </div>
  </div>
`;
if (window.lucide) lucide.createIcons();
document.getElementById('editBtn').addEventListener('click', () => showToast('Vendor editing coming soon'));
document.getElementById('deactivateBtn').addEventListener('click', () => showToast(s.name + ' has been deactivated', '<?= BASE_URL ?>/admin/suppliers'));
</script>
</body>
</html>
