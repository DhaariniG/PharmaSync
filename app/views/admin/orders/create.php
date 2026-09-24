<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Create Order</title>
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

      <div class="page-head">
        <div>
          <h1>Create New Order</h1>
          <p>Log a new pharmaceutical order for fulfillment and dispatch.</p>
        </div>
      </div>

      <form class="panel" id="orderForm">
        <div class="form-panel form-grid">
          <div class="form-group">
            <label for="facility">Facility / Customer Name</label>
            <input type="text" id="facility" placeholder="e.g. St. Jude Medical Center" required />
          </div>
          <div class="form-group">
            <label for="priority">Priority</label>
            <select id="priority">
              <option>Normal</option>
              <option>Urgent</option>
              <option>Critical</option>
            </select>
          </div>
          <div class="form-group full">
            <label for="medication">Medication / Item</label>
            <input type="text" id="medication" placeholder="e.g. Insulin Glargine (100 Units/mL)" required />
          </div>
          <div class="form-group">
            <label for="qty">Quantity</label>
            <input type="text" id="qty" placeholder="e.g. 50 Vials" required />
          </div>
          <div class="form-group">
            <label for="eta">Requested Delivery Time</label>
            <input type="text" id="eta" placeholder="e.g. Within 30 minutes" />
          </div>
          <div class="form-group full">
            <label for="address">Delivery Address</label>
            <input type="text" id="address" placeholder="Street, Suite, City" required />
          </div>
          <div class="form-group full">
            <label for="notes">Notes</label>
            <textarea id="notes" placeholder="Special handling instructions, cold chain requirements, etc."></textarea>
          </div>
        </div>
        <div class="form-actions">
          <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-outline">Cancel</a>
          <button type="submit" class="btn btn-primary"><i data-lucide="plus"></i> Create Order</button>
        </div>
      </form>

    </main>
  </div>
</div>

<script src="<?= asset('assets/js/lucide.min.js') ?>"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
<script>
document.getElementById('orderForm').addEventListener('submit', function(e){
  e.preventDefault();
  showToast('Order created and queued for fulfillment', '<?= BASE_URL ?>/admin/orders');
});
</script>
</body>
</html>
