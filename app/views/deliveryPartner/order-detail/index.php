<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Order Detail</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="deliveries" data-page-title="Order Detail" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="On Shift" data-user-initials="AR">

<div class="app">
  <div id="sidebar-root"><?php require __DIR__ . '/../partials/sidebar.php'; ?></div>
  <div class="main">
    <div id="topbar-root"><?php require __DIR__ . '/../partials/topbar.php'; ?></div>

    <main class="content">
      <a class="back-link" href="<?= url('/deliveryPartner/deliveries') ?>">
        <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Back to Deliveries
      </a>

      <div id="detailRoot"></div>

    </main>

    <footer class="app-footer">
      <div class="footer-left">
        <span class="footer-dot"></span>
        <span>PharmaSync Driver Logistics &bull; System status: All services operational</span>
      </div>
      <div class="footer-links">
        <a href="<?= url('/deliveryPartner/privacy') ?>">Privacy</a>
        <a href="<?= url('/deliveryPartner/terms') ?>">Terms</a>
        <a href="<?= url('/deliveryPartner/support') ?>">Contact Dispatch</a>
      </div>
    </footer>
  </div>
</div>

<script src="<?= asset('assets/js/delivery-partner.js') ?>"></script>
<script>
const ORDERS = {
  'PH-9021-A':      { name:'City General Pharmacy', address:'Warehouse Alpha &rarr; City General Pharmacy', type:'Cold Chain', status:'transit', statusLabel:'In Transit', date:'2024-10-23 14:12', notes:'Insulin batch — keep between 2&ndash;8&deg;C throughout transit.' },
  'PH-8812-B':      { name:'Riverside Clinic', address:'Warehouse Alpha &rarr; Riverside Clinic', type:'Standard', status:'delivered', statusLabel:'Completed', date:'2024-10-23 13:45', notes:'Signed for by front desk.' },
  'CH-9921':        { name:'Vehicle Unit #882', address:'Cold-chain manifest — temperature deviation flagged', type:'Cold Chain', status:'issue', statusLabel:'Issue', date:'Today, 14:22', notes:'Unit reporting +2.4&deg;C above threshold. Confirm cargo integrity before continuing route and action the manifest.' },
  'PH-2024-8841':   { name:'Jonathan Edwards', address:'452 Oak Avenue, Medical Heights', type:'Standard', status:'issue', statusLabel:'Issue', date:'Today', notes:'Driver reported heavy traffic in East Sector. Estimated delay: 15 minutes.', priority:'High', driver:'Michael S.' },
  'PH-2024-8842':   { name:'901 Bulk Way, West Sector', address:'901 Bulk Way, West Sector', type:'Standard', status:'transit', statusLabel:'In Transit', date:'Today', notes:'ETA 14:20 (9 min out).', priority:'Standard', driver:'Jessica L.' },
  'PH-2024-8843':   { name:'Dr. Amanda Lee', address:'Clinic B, Suite 400', type:'Standard', status:'pending', statusLabel:'Pending', date:'Scheduled 15:00', notes:'Awaiting driver assignment.', priority:'Standard', driver:'Unassigned' },
  'PH-2024-8839':   { name:'Robert Thompson', address:'12 Silver Dr, Apt 2B', type:'Standard', status:'delivered', statusLabel:'Delivered', date:'13:45 (Arrived)', notes:'Delivered and signed for.', priority:'Standard', driver:'David A.' },
  'PH-2024-8840':   { name:'City General Hospital', address:'Emergency Intake Dock', type:'Standard', status:'transit', statusLabel:'In Transit', date:'ETA 14:05 (3 min)', notes:'Urgent — emergency intake dock, radio on arrival.', priority:'Urgent', driver:'Robert H.' },
  'PH-889021':      { name:'Cold Chain Delivery', address:'Route completed', type:'Cold Chain', status:'delivered', statusLabel:'Completed', date:'Jun 28, 2024', notes:'Base pay $45.00, tips +$12.50.' },
  'PH-889104':      { name:'Standard Delivery', address:'Route pending payout', type:'Standard', status:'pending', statusLabel:'Pending', date:'Jun 28, 2024', notes:'Base pay $22.00, no tip recorded yet.' },
  'PH-888442':      { name:'Hazmat Delivery', address:'Route completed', type:'Hazmat', status:'delivered', statusLabel:'Completed', date:'Jun 27, 2024', notes:'Base pay $65.00, tips +$25.00.' },
  'PH-888320':      { name:'Standard Delivery', address:'Route completed', type:'Standard', status:'delivered', statusLabel:'Completed', date:'Jun 27, 2024', notes:'Base pay $18.50, tips +$5.00.' },
};

const id = getParam('id') || 'PH-9021-A';
const o = ORDERS[id] || { name:'Unknown Order', address:'No record found for this ID', type:'Standard', status:'pending', statusLabel:'Unknown', date:'—', notes:'This order could not be found in the current route.' };

document.getElementById('detailRoot').innerHTML = `
  <div class="page-header-row">
    <div>
      <h2 class="page-heading">Order ${id}</h2>
      <p class="page-subheading">${o.name}</p>
    </div>
    <span class="status-badge ${o.status}">${o.statusLabel}</span>
  </div>

  <div class="lower-grid detail-grid">
    <div class="card">
      <div class="detail-section">
        <h3>Delivery Details</h3>
        <div class="detail-row"><span class="k">Destination</span><span class="v">${o.address}</span></div>
        <div class="detail-row"><span class="k">Delivery Type</span><span class="v">${o.type}</span></div>
        ${o.priority ? `<div class="detail-row"><span class="k">Priority</span><span class="v">${o.priority}</span></div>` : ''}
        ${o.driver ? `<div class="detail-row"><span class="k">Assigned Driver</span><span class="v">${o.driver}</span></div>` : ''}
        <div class="detail-row"><span class="k">Date / ETA</span><span class="v">${o.date}</span></div>
      </div>
      <div class="detail-section">
        <h3>Notes</h3>
        <p style="font-size:.875rem; color:var(--color-body); line-height:1.6;">${o.notes}</p>
      </div>
    </div>

    <div class="card">
      <div class="card-header" style="padding:1.5rem 1.5rem 0;">
        <span class="card-title">Actions</span>
      </div>
      <div class="action-list">
        <button class="btn-primary" id="markDeliveredBtn">Mark as Delivered</button>
        <button class="btn-outline" id="trackBtn">Track on Map</button>
        <a class="btn-outline" href="<?= url('/deliveryPartner/support') ?>">Report an Issue</a>
      </div>
    </div>
  </div>
`;

document.getElementById('markDeliveredBtn').addEventListener('click', () => showToast('Order ' + id + ' marked as delivered', '<?= url('/deliveryPartner/deliveries') ?>'));
document.getElementById('trackBtn').addEventListener('click', () => showToast('Opening live GPS tracking…'));
</script>
</body>
</html>
