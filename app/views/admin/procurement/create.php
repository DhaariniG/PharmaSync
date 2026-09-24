<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — New Procurement Action</title>
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
          <h1>New Procurement Action</h1>
          <p>Create a purchase order with an existing supplier.</p>
        </div>
      </div>

      <form class="panel" id="procurementForm">
        <div class="form-panel form-grid">
          <div class="form-group">
            <label for="vendor">Vendor</label>
            <select id="vendor">
              <option>Pfizer Global</option>
              <option>MedEquip Solutions</option>
              <option>Apex Logistics</option>
              <option>SinoPharma</option>
            </select>
          </div>
          <div class="form-group">
            <label for="priority">Priority</label>
            <select id="priority">
              <option>Standard</option>
              <option>Urgent</option>
            </select>
          </div>
          <div class="form-group full">
            <label for="items">Item(s) to Order</label>
            <input type="text" id="items" placeholder="e.g. Insulin Glargine (Solostar)" />
          </div>
          <div class="form-group">
            <label for="qty">Quantity</label>
            <input type="text" id="qty" placeholder="e.g. 500 Units" />
          </div>
          <div class="form-group">
            <label for="expected">Expected Delivery</label>
            <input type="date" id="expected" />
          </div>
          <div class="form-group full">
            <label for="notes">Notes</label>
            <textarea id="notes" placeholder="Any special terms for this purchase order"></textarea>
          </div>
        </div>
        <div class="form-actions">
          <a href="<?= BASE_URL ?>/admin/suppliers" class="btn btn-outline">Cancel</a>
          <button type="submit" class="btn btn-primary"><i data-lucide="shopping-cart"></i> Submit Purchase Order</button>
        </div>
      </form>

    </main>
  </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
<script>
const vendorParam = getParam('vendor');
const itemParam = getParam('item');
if (vendorParam) {
  const sel = document.getElementById('vendor');
  for (const opt of sel.options) { if (opt.text === vendorParam) sel.value = vendorParam; }
}
if (itemParam) { document.getElementById('items').value = itemParam; }

document.getElementById('procurementForm').addEventListener('submit', function(e){
  e.preventDefault();
  showToast('Purchase order submitted', '<?= BASE_URL ?>/admin/suppliers');
});
</script>
</body>
</html>
