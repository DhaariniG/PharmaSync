<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — New Delivery</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="deliveries" data-page-title="New Delivery" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="On Shift" data-user-initials="AR">

<div class="app">
  <div id="sidebar-root"><?php require __DIR__ . '/../partials/sidebar.php'; ?></div>
  <div class="main">
    <div id="topbar-root"><?php require __DIR__ . '/../partials/topbar.php'; ?></div>

    <main class="content">
      <a class="back-link" href="<?= url('/deliveryPartner/deliveries') ?>">
        <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Back to Deliveries
      </a>

      <div class="page-header-row">
        <div>
          <h2 class="page-heading">New Delivery</h2>
          <p class="page-subheading">Log a new pickup and route it for dispatch.</p>
        </div>
      </div>

      <form class="card" id="newDeliveryForm">
        <div class="form-panel form-grid">
          <div class="form-group">
            <label for="orderRef">Related Order ID</label>
            <input type="text" id="orderRef" placeholder="e.g. PH-2024-9001" required />
          </div>
          <div class="form-group">
            <label for="deliveryType">Delivery Type</label>
            <select id="deliveryType">
              <option>Standard</option>
              <option>Cold Chain</option>
              <option>Hazmat</option>
            </select>
          </div>
          <div class="form-group full">
            <label for="destName">Destination / Patient Name</label>
            <input type="text" id="destName" placeholder="e.g. Saman Kumara" required />
          </div>
          <div class="form-group full">
            <label for="destAddress">Destination Address</label>
            <input type="text" id="destAddress" placeholder="e.g. 452/11, jayanthipura, Delkanda" required />
          </div>
          <div class="form-group">
            <label for="priority">Priority</label>
            <select id="priority">
              <option>Standard</option>
              <option>High</option>
              <option>Urgent</option>
            </select>
          </div>
          <div class="form-group">
            <label for="etaTime">Scheduled</label>
            <input type="time" id="etaTime" />
          </div>
          <div class="form-group full">
            <label for="notes">Handling Notes</label>
            <textarea id="notes" placeholder="Access codes, cold-chain requirements, patient instructions, etc."></textarea>
          </div>
        </div>
        <div class="form-actions">
          <a href="<?= url('/deliveryPartner/deliveries') ?>" class="btn-outline">Cancel</a>
          <button type="submit" class="btn-primary">Schedule Delivery</button>
        </div>
      </form>

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
  document.getElementById('newDeliveryForm').addEventListener('submit', function (e) {
    e.preventDefault();
    showToast('Delivery scheduled and added to your route', '<?= url('/deliveryPartner/deliveries') ?>');
  });
</script>
</body>
</html>
