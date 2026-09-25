<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Purchase Order Approvals</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/tokens.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/chrome.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/pages.css" />
</head>
<body data-page="purchase-orders" data-user-name="Admin User" data-user-role="Administrator" data-user-initials="AU">

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
          <h1>Purchase Order Approvals</h1>
          <p>Review purchase orders raised by the Inventory Manager and approve or reject them.</p>
        </div>
      </div>

      <?php /* Sample data - the same POs are listed on the Inventory Manager's
               Purchase Orders page. Approve/Reject only change this page. */ ?>
      <div class="panel">
        <div class="tabs" role="tablist">
          <button type="button" class="tab active" data-filter="all">All <span class="tab-count" data-count="all">8</span></button>
          <button type="button" class="tab" data-filter="pending">Pending <span class="tab-count" data-count="pending">3</span></button>
          <button type="button" class="tab" data-filter="approved">Approved <span class="tab-count" data-count="approved">2</span></button>
          <button type="button" class="tab" data-filter="rejected">Rejected <span class="tab-count" data-count="rejected">1</span></button>
        </div>

        <table class="data-table" id="poTable">
          <thead>
            <tr><th>PO Number</th><th>Supplier</th><th>Items</th><th>Total Qty</th><th>Raised By</th><th>Date</th><th>Status</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <tr data-status="pending" data-po="PO-2026-0112">
              <td class="po-number">PO-2026-0112</td>
              <td>Ceylon Pharma Distributors</td>
              <td><ul class="po-items"><li>Amoxicillin 500mg <span class="qty">&times; 300</span></li><li>Azithromycin 250mg <span class="qty">&times; 120</span></li></ul></td>
              <td>420 Units</td>
              <td>Tharindu Jayasuriya</td>
              <td>Sep 22, 2026</td>
              <td class="po-status"><span class="badge badge-amber">Pending</span></td>
              <td class="po-actions-cell"><div class="po-actions">
                <button type="button" class="btn btn-primary btn-sm" data-action="approve"><i data-lucide="check"></i> Approve</button>
                <button type="button" class="btn btn-outline btn-sm" data-action="reject"><i data-lucide="x"></i> Reject</button>
              </div></td>
            </tr>
            <tr data-status="pending" data-po="PO-2026-0111">
              <td class="po-number">PO-2026-0111</td>
              <td>MedSupply Lanka (Pvt) Ltd</td>
              <td><ul class="po-items"><li>Paracetamol 500mg <span class="qty">&times; 500</span></li></ul></td>
              <td>500 Units</td>
              <td>Tharindu Jayasuriya</td>
              <td>Sep 20, 2026</td>
              <td class="po-status"><span class="badge badge-amber">Pending</span></td>
              <td class="po-actions-cell"><div class="po-actions">
                <button type="button" class="btn btn-primary btn-sm" data-action="approve"><i data-lucide="check"></i> Approve</button>
                <button type="button" class="btn btn-outline btn-sm" data-action="reject"><i data-lucide="x"></i> Reject</button>
              </div></td>
            </tr>
            <tr data-status="pending" data-po="PO-2026-0110">
              <td class="po-number">PO-2026-0110</td>
              <td>Colombo Wholesale Pharmaceuticals</td>
              <td><ul class="po-items"><li>Salbutamol Inhaler 100mcg <span class="qty">&times; 60</span></li><li>Cetirizine 10mg <span class="qty">&times; 200</span></li></ul></td>
              <td>260 Units</td>
              <td>Tharindu Jayasuriya</td>
              <td>Sep 17, 2026</td>
              <td class="po-status"><span class="badge badge-amber">Pending</span></td>
              <td class="po-actions-cell"><div class="po-actions">
                <button type="button" class="btn btn-primary btn-sm" data-action="approve"><i data-lucide="check"></i> Approve</button>
                <button type="button" class="btn btn-outline btn-sm" data-action="reject"><i data-lucide="x"></i> Reject</button>
              </div></td>
            </tr>
            <tr data-status="approved" data-po="PO-2026-0109">
              <td class="po-number">PO-2026-0109</td>
              <td>MedSupply Lanka (Pvt) Ltd</td>
              <td><ul class="po-items"><li>Metformin 500mg <span class="qty">&times; 400</span></li></ul></td>
              <td>400 Units</td>
              <td>Tharindu Jayasuriya</td>
              <td>Sep 12, 2026</td>
              <td class="po-status"><span class="badge badge-green">Approved</span></td>
              <td>&mdash;</td>
            </tr>
            <tr data-status="approved" data-po="PO-2026-0108">
              <td class="po-number">PO-2026-0108</td>
              <td>Colombo Wholesale Pharmaceuticals</td>
              <td><ul class="po-items"><li>Vitamin C 1000mg <span class="qty">&times; 600</span></li></ul></td>
              <td>600 Units</td>
              <td>Tharindu Jayasuriya</td>
              <td>Sep 08, 2026</td>
              <td class="po-status"><span class="badge badge-green">Approved</span></td>
              <td>&mdash;</td>
            </tr>
            <tr data-status="received" data-po="PO-2026-0107">
              <td class="po-number">PO-2026-0107</td>
              <td>Ceylon Pharma Distributors</td>
              <td><ul class="po-items"><li>Ibuprofen 400mg <span class="qty">&times; 250</span></li><li>Paracetamol 500mg <span class="qty">&times; 300</span></li></ul></td>
              <td>550 Units</td>
              <td>Tharindu Jayasuriya</td>
              <td>Sep 02, 2026</td>
              <td class="po-status"><span class="badge badge-cyan">Received</span></td>
              <td>&mdash;</td>
            </tr>
            <tr data-status="received" data-po="PO-2026-0106">
              <td class="po-number">PO-2026-0106</td>
              <td>MedSupply Lanka (Pvt) Ltd</td>
              <td><ul class="po-items"><li>Cetirizine 10mg <span class="qty">&times; 150</span></li></ul></td>
              <td>150 Units</td>
              <td>Tharindu Jayasuriya</td>
              <td>Aug 27, 2026</td>
              <td class="po-status"><span class="badge badge-cyan">Received</span></td>
              <td>&mdash;</td>
            </tr>
            <tr data-status="rejected" data-po="PO-2026-0105">
              <td class="po-number">PO-2026-0105</td>
              <td>Colombo Wholesale Pharmaceuticals</td>
              <td><ul class="po-items"><li>Azithromycin 250mg <span class="qty">&times; 80</span></li></ul></td>
              <td>80 Units</td>
              <td>Tharindu Jayasuriya</td>
              <td>Aug 21, 2026</td>
              <td class="po-status"><span class="badge badge-red">Rejected</span><span class="po-reason">Current stock is enough for this month.</span></td>
              <td>&mdash;</td>
            </tr>
          </tbody>
        </table>
        <div class="po-empty" id="poEmpty" hidden>No purchase orders in this list.</div>
      </div>
    </main>
  </div>
</div>

<!-- Approve / Reject confirmation (reuses the logout popup styles) -->
<div class="logout-modal po-modal" id="poModal" aria-hidden="true">
  <div class="logout-modal-card" role="dialog" aria-modal="true" aria-labelledby="poModalTitle">
    <div class="logout-modal-icon po-modal-icon" id="poModalIcon"><i data-lucide="check"></i></div>
    <h2 id="poModalTitle">Approve Purchase Order</h2>
    <p id="poModalText"></p>
    <div class="form-group" id="poReasonGroup" hidden>
      <label for="poReason">Reason for rejection</label>
      <textarea id="poReason" rows="3" placeholder="Tell the Inventory Manager why this order is rejected"></textarea>
      <span class="field-error" id="poReasonError">Please enter a reason.</span>
    </div>
    <div class="logout-modal-actions">
      <button type="button" class="btn btn-outline" id="poModalCancel">Cancel</button>
      <button type="button" class="btn btn-primary" id="poModalConfirm">Approve</button>
    </div>
  </div>
</div>

<script src="<?= asset('assets/js/lucide.min.js') ?>"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
<script>
// Static demo: Approve/Reject only update this page. Nothing is saved.
const rows    = Array.from(document.querySelectorAll('#poTable tbody tr'));
const tabs    = document.querySelectorAll('.tabs .tab');
const modal   = document.getElementById('poModal');
const reason  = document.getElementById('poReason');
const reasonG = document.getElementById('poReasonGroup');
const reasonE = document.getElementById('poReasonError');
const confirmBtn = document.getElementById('poModalConfirm');
let currentFilter = 'all';
let pendingRow = null;
let pendingAction = null;

function applyFilter() {
  let shown = 0;
  rows.forEach((row) => {
    const show = currentFilter === 'all' || row.dataset.status === currentFilter;
    row.hidden = !show;
    if (show) shown++;
  });
  document.getElementById('poEmpty').hidden = shown > 0;
}

function updateCounts() {
  ['pending', 'approved', 'rejected'].forEach((status) => {
    const n = rows.filter((r) => r.dataset.status === status).length;
    document.querySelector('[data-count="' + status + '"]').textContent = n;
    if (status === 'pending') {
      const badge = document.getElementById('poPendingBadge');
      if (badge) badge.textContent = n > 0 ? n : '';
    }
  });
}

tabs.forEach((tab) => {
  tab.addEventListener('click', () => {
    tabs.forEach((t) => t.classList.toggle('active', t === tab));
    currentFilter = tab.dataset.filter;
    applyFilter();
  });
});

function openModal(row, action) {
  pendingRow = row;
  pendingAction = action;
  const po = row.dataset.po;
  const isReject = action === 'reject';

  document.getElementById('poModalTitle').textContent = isReject ? 'Reject Purchase Order' : 'Approve Purchase Order';
  document.getElementById('poModalText').textContent = isReject
    ? 'Reject ' + po + '? The Inventory Manager will see your reason.'
    : 'Approve ' + po + '? The Inventory Manager can then send it to the supplier.';
  document.getElementById('poModalIcon').className = 'logout-modal-icon po-modal-icon' + (isReject ? '' : ' approve');
  document.getElementById('poModalIcon').innerHTML = '<i data-lucide="' + (isReject ? 'x' : 'check') + '"></i>';
  confirmBtn.className = 'btn ' + (isReject ? 'btn-danger' : 'btn-primary');
  confirmBtn.textContent = isReject ? 'Reject' : 'Approve';

  reasonG.hidden = !isReject;
  reason.value = '';
  reasonE.classList.remove('show');

  modal.classList.add('open');
  modal.setAttribute('aria-hidden', 'false');
  if (window.lucide) lucide.createIcons();
  (isReject ? reason : confirmBtn).focus();
}

function closeModal() {
  modal.classList.remove('open');
  modal.setAttribute('aria-hidden', 'true');
  pendingRow = null;
}

rows.forEach((row) => {
  row.querySelectorAll('[data-action]').forEach((btn) => {
    btn.addEventListener('click', () => openModal(row, btn.dataset.action));
  });
});

confirmBtn.addEventListener('click', () => {
  if (!pendingRow) return;
  const isReject = pendingAction === 'reject';
  const text = reason.value.trim();

  if (isReject && text === '') {
    reasonE.classList.add('show');
    reason.focus();
    return;
  }

  const status = pendingRow.querySelector('.po-status');
  status.innerHTML = '';
  const badge = document.createElement('span');
  badge.className = 'badge ' + (isReject ? 'badge-red' : 'badge-green');
  badge.textContent = isReject ? 'Rejected' : 'Approved';
  status.appendChild(badge);
  if (isReject) {
    const note = document.createElement('span');
    note.className = 'po-reason';
    note.textContent = text;
    status.appendChild(note);
  }

  pendingRow.dataset.status = isReject ? 'rejected' : 'approved';
  pendingRow.querySelector('.po-actions-cell').innerHTML = '&mdash;';
  const po = pendingRow.dataset.po;

  closeModal();
  updateCounts();
  applyFilter();
  showToast(po + (isReject ? ' rejected' : ' approved'));
});

document.getElementById('poModalCancel').addEventListener('click', closeModal);
modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
});
</script>
</body>
</html>
