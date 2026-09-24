<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Urgent Queue</title>
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
          <h1>Urgent Order Queue</h1>
          <p>Life-critical medication orders that have exceeded the 30-minute fulfillment window.</p>
        </div>
      </div>

      <div class="panel">
        <table class="data-table">
          <thead>
            <tr><th>Order ID</th><th>Facility</th><th>Item</th><th>Status</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <tr>
              <td style="color:var(--teal-700);font-weight:700;">#ORD-9021</td>
              <td>St. Jude Medical Center</td>
              <td>Insulin Glargine (100 Units/mL) - 50 Vials</td>
              <td><span class="badge badge-amber">Urgent</span></td>
              <td><a href="<?= BASE_URL ?>/admin/orders/detail?id=ORD-9021" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
            </tr>
            <tr>
              <td style="color:var(--teal-700);font-weight:700;">#ORD-4452</td>
              <td>Address Verification Needed</td>
              <td>Flagged shipment — driver on hold</td>
              <td><span class="badge badge-red">Delayed</span></td>
              <td><a href="<?= BASE_URL ?>/admin/orders/detail?id=ORD-4452" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
            </tr>
            <tr>
              <td style="color:var(--teal-700);font-weight:700;">#ORD-7756</td>
              <td>Anna Richards</td>
              <td>General pharmacy order</td>
              <td><span class="badge badge-red">Urgent</span></td>
              <td><a href="<?= BASE_URL ?>/admin/orders/detail?id=ORD-7756" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
            </tr>
          </tbody>
        </table>
      </div>

    </main>
  </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
</body>
</html>
