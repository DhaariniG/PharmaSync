<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Batch Details</title>
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

      <div id="detailRoot"></div>

    </main>
  </div>
</div>

<script src="<?= asset('assets/js/lucide.min.js') ?>"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
<script>
const ITEMS = {
  'BT-99021-X': { name:'Amoxicillin 500mg Caps', qty:'4,500 Units', expiry:'Oct 12, 2025', location:'WH-Alpha-R4', badge:'badge-teal', status:'Optimal' },
  'BT-11204-Y': { name:'Insulin Glargine 100U', qty:'120 Units', expiry:'Aug 04, 2024', location:'Cold-Storage-C1', badge:'badge-red', status:'Low Stock' },
  'BT-55670-Z': { name:'Lisinopril 10mg Tabs', qty:'12,800 Units', expiry:'Mar 15, 2024', location:'WH-Gamma-R12', badge:'badge-amber', status:'Expiring Soon' },
  'BT-88432-A': { name:'Metformin HCl 850mg', qty:'8,200 Units', expiry:'Nov 22, 2026', location:'WH-Alpha-R9', badge:'badge-teal', status:'Optimal' },
  'BT-23419-B': { name:'Atorvastatin 20mg', qty:'150 Units', expiry:'Jan 30, 2025', location:'WH-Beta-R2', badge:'badge-red', status:'Critical Stock' },
};

const id = getParam('id') || 'BT-99021-X';
const editMode = getParam('mode') === 'edit';
const it = ITEMS[id] || ITEMS['BT-99021-X'];

function fieldRow(label, value) {
  if (editMode) {
    return `<div class="form-group"><label>${label}</label><input type="text" value="${value}" /></div>`;
  }
  return `<div class="detail-row"><span class="k">${label}</span><span class="v">${value}</span></div>`;
}

document.getElementById('detailRoot').innerHTML = `
  <div class="page-head">
    <div>
      <h1>${it.name}</h1>
      <p>Batch ${id}</p>
    </div>
    <div class="page-head-actions">
      <span class="badge ${it.badge}">${it.status}</span>
    </div>
  </div>
  <div class="detail-grid">
    <div class="panel">
      <div class="detail-section">
        <h3>${editMode ? 'Edit Batch' : 'Batch Details'}</h3>
        <div class="${editMode ? 'form-grid' : ''}">
          ${fieldRow('Stock Quantity', it.qty)}
          ${fieldRow('Expiration Date', it.expiry)}
          ${fieldRow('Warehouse Location', it.location)}
          ${fieldRow('Status', it.status)}
        </div>
      </div>
      ${editMode ? '<div class="form-actions"><a href="<?= BASE_URL ?>/admin/inventory/detail?id='+id+'" class="btn btn-outline">Cancel</a><button class="btn btn-primary" id="saveBtn"><i data-lucide="check"></i> Save Changes</button></div>' : ''}
    </div>
    <div class="panel">
      <div class="panel-header"><h2>Actions</h2></div>
      <div class="action-list">
        <a href="<?= BASE_URL ?>/admin/inventory/detail?id=${id}&mode=edit" class="btn btn-outline"><i data-lucide="pencil"></i> Edit Batch</a>
        <button class="btn btn-outline" id="disposalBtn"><i data-lucide="trash-2"></i> Flag for Disposal</button>
        <button class="btn btn-outline" id="redistributeBtn"><i data-lucide="shuffle"></i> Request Redistribution</button>
      </div>
    </div>
  </div>
`;
if (window.lucide) lucide.createIcons();
const save = document.getElementById('saveBtn');
if (save) save.addEventListener('click', () => showToast('Batch updated', '<?= BASE_URL ?>/admin/inventory/detail?id=' + id));
document.getElementById('disposalBtn').addEventListener('click', () => showToast('Batch ' + id + ' flagged for disposal'));
document.getElementById('redistributeBtn').addEventListener('click', () => showToast('Redistribution request submitted'));
</script>
</body>
</html>
