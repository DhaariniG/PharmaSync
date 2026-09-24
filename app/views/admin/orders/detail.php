<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Order Details</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/tokens.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/chrome.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/pages.css" />
</head>
<body data-page="orders" data-user-name="Admin User" data-user-role="Administrator" data-user-initials="AU">

<div class="app-shell">
  <div id="sidebar-root">
    <?php require APP_PATH . '/views/admin/partials/sidebar.php'; ?>
  </div>

  <div class="main-col">
    <div id="topbar-root">
      <?php require APP_PATH . '/views/admin/partials/topbar.php'; ?>
    </div>

    <main class="page-content">
      <a class="back-link" href="<?= BASE_URL ?>/admin/orders"><i data-lucide="arrow-left"></i> Back to Orders</a>

      <div id="detailRoot"></div>

    </main>
  </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
<script>
const ORDERS = {
  'ORD-9021': { facility:'St. Jude Medical Center', item:'Insulin Glargine (100 Units/mL) - 50 Vials', badge:'badge-amber', badgeLabel:'Urgent', status:'Pending Fulfillment', placed:'12 mins ago', address:'St. Jude Medical Center, Wing C' },
  'ORD-9022': { facility:'Green Valley Pharmacy', item:'Amoxicillin 500mg - 200 Capsules', badge:'badge-gray', badgeLabel:'Normal', status:'Pending Fulfillment', placed:'45 mins ago', address:'Green Valley Pharmacy, Main St' },
  'ORD-8995': { facility:'Northside General', item:'Epinephrine Auto-Injectors - 12 Units', badge:'badge-cyan', badgeLabel:'Dispatched', status:'In Transit — 75% (3.2km to destination)', placed:'1 hour ago', address:'Northside General Hospital' },
  'ORD-8991': { facility:'City Wellness Clinic', item:'Lisinopril 10mg - 15 Bottles', badge:'badge-cyan', badgeLabel:'Dispatched', status:'In Transit — 20% (12.5km to destination)', placed:'40 mins ago', address:'City Wellness Clinic' },
  'ORD-8940': { facility:'Metro Health Hub', item:'Mixed Pharmaceutical Supplies - Bulk', badge:'badge-teal', badgeLabel:'Delivered', status:'Confirmed by Nurse R. Sims', placed:'1 hour ago', address:'Metro Health Hub' },
  'ORD-8938': { facility:'Pioneer Rehab Center', item:'Gabapentin 300mg - 400 Units', badge:'badge-teal', badgeLabel:'Delivered', status:'Auto-confirmed (Geofence)', placed:'2 hours ago', address:'Pioneer Rehab Center' },
  'ORD-4452': { facility:'Address Verification Needed', item:'Flagged shipment — driver on hold', badge:'badge-red', badgeLabel:'Delayed', status:'Address Verification Required', placed:'—', address:'Unverified address' },
  'ORD-8843': { facility:'David Chen', item:'General pharmacy order', badge:'badge-gray', badgeLabel:'In Transit', status:'Driver: Mike R.', placed:'—', address:"15 King's Cross Road" },
  'ORD-9110': { facility:'Hospital Central', item:'General pharmacy order', badge:'badge-gray', badgeLabel:'Normal', status:'ETA 12m', placed:'—', address:'Medical Zone, Wing B' },
  'ORD-7756': { facility:'Anna Richards', item:'General pharmacy order', badge:'badge-red', badgeLabel:'Urgent', status:'Delivered 5m ago', placed:'—', address:'422 Oak Street, Suite 10' },
};

const id = getParam('id') || 'ORD-9021';
const o = ORDERS[id] || ORDERS['ORD-9021'];

document.getElementById('detailRoot').innerHTML = `
  <a class="back-link" style="display:none;"></a>
  <div class="page-head">
    <div>
      <h1>Order ${id}</h1>
      <p>${o.facility}</p>
    </div>
    <div class="page-head-actions">
      <span class="badge ${o.badge}">${o.badgeLabel}</span>
    </div>
  </div>
  <div class="detail-grid">
    <div class="panel">
      <div class="detail-section">
        <h3>Order Details</h3>
        <div class="detail-row"><span class="k">Item</span><span class="v">${o.item}</span></div>
        <div class="detail-row"><span class="k">Delivery Address</span><span class="v">${o.address}</span></div>
        <div class="detail-row"><span class="k">Placed</span><span class="v">${o.placed}</span></div>
        <div class="detail-row"><span class="k">Status</span><span class="v">${o.status}</span></div>
      </div>
    </div>
    <div class="panel">
      <div class="panel-header"><h2>Actions</h2></div>
      <div class="action-list">
        <button class="btn btn-primary" id="packBtn"><i data-lucide="package"></i> Mark as Packed</button>
        <button class="btn btn-outline" id="trackBtn"><i data-lucide="map-pin"></i> Track GPS</button>
        <button class="btn btn-outline" id="cancelBtn"><i data-lucide="x"></i> Cancel Order</button>
      </div>
    </div>
  </div>
`;
if (window.lucide) lucide.createIcons();
document.getElementById('packBtn').addEventListener('click', () => showToast('Order ' + id + ' marked as packed'));
document.getElementById('trackBtn').addEventListener('click', () => showToast('Opening live GPS tracking…'));
document.getElementById('cancelBtn').addEventListener('click', () => showToast('Order ' + id + ' cancelled', '<?= BASE_URL ?>/admin/orders'));
</script>
</body>
</html>
