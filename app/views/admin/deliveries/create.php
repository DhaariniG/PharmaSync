<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — New Shipment</title>
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
          <h1>New Shipment</h1>
          <p>Assign a delivery to a driver and schedule dispatch.</p>
        </div>
      </div>

      <form class="panel" id="deliveryForm">
        <div class="form-panel form-grid">
          <div class="form-group">
            <label for="orderRef">Related Order ID</label>
            <input type="text" id="orderRef" placeholder="e.g. ORD-9021" />
          </div>
          <div class="form-group">
            <label for="driver">Assign Driver</label>
            <select id="driver">
              <option>Sarah Jenkins</option>
              <option>David Miller</option>
              <option>Elena Rodriguez</option>
              <option>Tom Baker</option>
              <option>Auto-assign nearest available</option>
            </select>
          </div>
          <div class="form-group full">
            <label for="destination">Destination</label>
            <input type="text" id="destination" placeholder="e.g. Southside Community Clinic" required />
          </div>
          <div class="form-group">
            <label for="priority">Priority</label>
            <select id="priority">
              <option>Routine</option>
              <option>Urgent</option>
              <option>Critical</option>
            </select>
          </div>
          <div class="form-group">
            <label for="vehicle">Vehicle Type</label>
            <select id="vehicle">
              <option>Standard Van</option>
              <option>Cold Chain Vehicle</option>
              <option>Motorbike</option>
            </select>
          </div>
          <div class="form-group full">
            <label for="notes">Notes</label>
            <textarea id="notes" placeholder="Handling instructions, access codes, etc."></textarea>
          </div>
        </div>
        <div class="form-actions">
          <a href="<?= BASE_URL ?>/admin/deliveries" class="btn btn-outline">Cancel</a>
          <button type="submit" class="btn btn-primary"><i data-lucide="truck"></i> Schedule Shipment</button>
        </div>
      </form>

    </main>
  </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
<script>
document.getElementById('deliveryForm').addEventListener('submit', function(e){
  e.preventDefault();
  showToast('Shipment scheduled and driver notified', '<?= BASE_URL ?>/admin/deliveries');
});
</script>
</body>
</html>
