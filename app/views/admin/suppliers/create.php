<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Add Supplier</title>
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

      <div class="page-head">
        <div>
          <h1>Add Supplier</h1>
          <p>Onboard a new vendor to the global supply network.</p>
        </div>
      </div>

      <form class="panel" id="supplierForm">
        <div class="form-panel form-grid">
          <div class="form-group">
            <label for="vendorName">Vendor Name</label>
            <input type="text" id="vendorName" placeholder="e.g. Pfizer Global" required />
          </div>
          <div class="form-group">
            <label for="category">Category</label>
            <select id="category">
              <option>Primary Pharmaceutical</option>
              <option>Medical Hardware</option>
              <option>Shipping &amp; Logistics</option>
              <option>Global Chemicals</option>
            </select>
          </div>
          <div class="form-group">
            <label for="email">Contact Email</label>
            <input type="email" id="email" placeholder="contact@vendor.com" required />
          </div>
          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" placeholder="+1 (555) 000-0000" />
          </div>
          <div class="form-group">
            <label for="leadTime">Average Lead Time (days)</label>
            <input type="number" id="leadTime" placeholder="e.g. 5" />
          </div>
          <div class="form-group">
            <label for="rating">Initial Rating</label>
            <select id="rating">
              <option>5.0</option><option>4.5</option><option>4.0</option><option>3.5</option><option>3.0</option>
            </select>
          </div>
          <div class="form-group full">
            <label for="address">Business Address</label>
            <input type="text" id="address" placeholder="Street, City, Country" />
          </div>
        </div>
        <div class="form-actions">
          <a href="<?= BASE_URL ?>/admin/suppliers" class="btn btn-outline">Cancel</a>
          <button type="submit" class="btn btn-primary"><i data-lucide="truck"></i> Add Supplier</button>
        </div>
      </form>

    </main>
  </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
<script>
document.getElementById('supplierForm').addEventListener('submit', function(e){
  e.preventDefault();
  showToast('Supplier added to the network', '<?= BASE_URL ?>/admin/suppliers');
});
</script>
</body>
</html>
