<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Upload Document</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="profile" data-page-title="Upload Document" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="Senior Delivery Partner" data-user-initials="SJ">

<div class="app">
  <div id="sidebar-root"><?php require __DIR__ . '/../partials/sidebar.php'; ?></div>
  <div class="main">
    <div id="topbar-root"><?php require __DIR__ . '/../partials/topbar.php'; ?></div>

    <main class="content">
      <a class="back-link" href="<?= url('/deliveryPartner/profile') ?>">
        <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Back to Profile
      </a>

      <div class="page-header-row">
        <div>
          <h2 class="page-heading">Upload New Document</h2>
          <p class="page-subheading" id="docSubheading">Submit a compliance document for verification.</p>
        </div>
      </div>

      <form class="card" id="uploadForm">
        <div class="form-panel form-grid">
          <div class="form-group full">
            <label for="docType">Document Type</label>
            <select id="docType">
              <option value="driver-license">Driver License</option>
              <option value="vehicle-insurance">Vehicle Insurance</option>
              <option value="pharma-handling-cert">Pharma Handling Cert</option>
              <option value="background-check">Background Check</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group full">
            <label for="expiry">Expiration Date</label>
            <input type="date" id="expiry" />
          </div>
          <div class="form-group full">
            <label>File</label>
            <div class="upload-zone" id="dropZone">
              <svg class="icon" viewBox="0 0 24 24"><path d="M12 3v12M7 8l5-5 5 5M5 21h14"/></svg>
              <div><strong>Drag &amp; drop</strong> a file here, or click to browse</div>
              <div style="font-size:.75rem;">PDF, JPG or PNG &bull; up to 10MB</div>
              <input type="file" id="fileInput" accept=".pdf,.jpg,.jpeg,.png" style="display:none;" />
              <div class="upload-filename" id="fileName"></div>
            </div>
          </div>
        </div>
        <div class="form-actions">
          <a href="<?= url('/deliveryPartner/profile') ?>" class="btn-outline">Cancel</a>
          <button type="submit" class="btn-primary">Submit Document</button>
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
  // Pre-select document type from ?doc= query param (linked from Compliance Status rows)
  const DOC_LABELS = {
    'driver-license': 'Driver License',
    'vehicle-insurance': 'Vehicle Insurance',
    'pharma-handling-cert': 'Pharma Handling Cert',
    'background-check': 'Background Check',
  };
  const docParam = getParam('doc');
  if (docParam) {
    const select = document.getElementById('docType');
    if ([...select.options].some(o => o.value === docParam)) {
      select.value = docParam;
      document.getElementById('docSubheading').textContent =
        'Submit a new ' + (DOC_LABELS[docParam] || docParam) + ' document for verification.';
    }
  }

  const dropZone = document.getElementById('dropZone');
  const fileInput = document.getElementById('fileInput');
  const fileNameEl = document.getElementById('fileName');

  dropZone.addEventListener('click', () => fileInput.click());
  fileInput.addEventListener('change', () => {
    if (fileInput.files.length) fileNameEl.textContent = 'Selected: ' + fileInput.files[0].name;
  });

  ['dragenter', 'dragover'].forEach(evt => {
    dropZone.addEventListener(evt, (e) => { e.preventDefault(); dropZone.classList.add('dragover'); });
  });
  ['dragleave', 'drop'].forEach(evt => {
    dropZone.addEventListener(evt, (e) => { e.preventDefault(); dropZone.classList.remove('dragover'); });
  });
  dropZone.addEventListener('drop', (e) => {
    if (e.dataTransfer.files.length) {
      fileInput.files = e.dataTransfer.files;
      fileNameEl.textContent = 'Selected: ' + e.dataTransfer.files[0].name;
    }
  });

  document.getElementById('uploadForm').addEventListener('submit', function (e) {
    e.preventDefault();
    showToast('Document submitted for verification', '<?= url('/deliveryPartner/profile') ?>');
  });
</script>
</body>
</html>
