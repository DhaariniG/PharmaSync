<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Add Stock</title>
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

      <div class="page-head">
        <div>
          <h1>Add Stock</h1>
          <p>Register a new batch into pharmaceutical inventory.</p>
        </div>
      </div>

      <form class="panel" id="stockForm">
        <div class="form-panel form-grid">
          <div class="form-group">
            <label for="productName">Product Name</label>
            <input type="text" id="productName" placeholder="e.g. Amoxicillin 500mg Caps" required />
          </div>
          <div class="form-group">
            <label for="batchId">Batch ID</label>
            <input type="text" id="batchId" placeholder="e.g. BT-99021-X" required />
          </div>
          <div class="form-group">
            <label for="qty">Quantity</label>
            <input type="text" id="qty" placeholder="e.g. 4,500 Units" required />
          </div>
          <div class="form-group">
            <label for="expiry">Expiration Date</label>
            <input type="date" id="expiry" required />
          </div>
          <div class="form-group">
            <label for="warehouse">Warehouse Location</label>
            <select id="warehouse">
              <option>WH-Alpha-R4</option>
              <option>WH-Beta-R2</option>
              <option>WH-Gamma-R12</option>
              <option>Cold-Storage-C1</option>
            </select>
          </div>
          <div class="form-group">
            <label for="category">Category</label>
            <select id="category">
              <option>Prescription Medication</option>
              <option>OTC Product</option>
              <option>Medical Supply</option>
              <option>Refrigerated Item</option>
            </select>
          </div>
        </div>
        <div class="form-actions">
          <a href="<?= BASE_URL ?>/admin/inventory" class="btn btn-outline">Cancel</a>
          <button type="submit" class="btn btn-primary"><i data-lucide="plus"></i> Add Stock</button>
        </div>
      </form>

    </main>
  </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
<script>
document.getElementById('stockForm').addEventListener('submit', function(e){
  e.preventDefault();
  showToast('Stock batch added to inventory', '<?= BASE_URL ?>/admin/inventory');
});
</script>
</body>
</html>
