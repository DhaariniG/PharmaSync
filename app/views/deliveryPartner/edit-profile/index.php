<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Edit Profile</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="profile" data-page-title="Edit Profile" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="Senior Delivery Partner" data-user-initials="SJ">

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
          <h2 class="page-heading">Edit Profile</h2>
          <p class="page-subheading">Update your account and vehicle details.</p>
        </div>
      </div>

      <form class="card" id="editProfileForm">
        <div class="form-panel form-grid">
          <div class="form-group">
            <label for="fullName">Full Name</label>
            <input type="text" id="fullName" value="Sarah Jenkins" required />
          </div>
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" value="s.jenkins@pharmaroute.com" required />
          </div>
          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" value="+1 (555) 012-3456" />
          </div>
          <div class="form-group">
            <label for="region">Operating Region</label>
            <input type="text" id="region" value="Metropolitan North (MN-04)" />
          </div>
          <div class="form-group">
            <label for="vehicleType">Vehicle Type</label>
            <select id="vehicleType">
              <option selected>Climate Controlled Van</option>
              <option>Standard Van</option>
              <option>Motorbike</option>
            </select>
          </div>
          <div class="form-group">
            <label for="plate">License Plate</label>
            <input type="text" id="plate" value="PH-772-RX" />
          </div>
        </div>
        <div class="form-actions">
          <a href="<?= url('/deliveryPartner/profile') ?>" class="btn-outline">Cancel</a>
          <button type="submit" class="btn-primary">Save Changes</button>
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
  document.getElementById('editProfileForm').addEventListener('submit', function (e) {
    e.preventDefault();
    showToast('Profile updated', '<?= url('/deliveryPartner/profile') ?>');
  });
</script>
</body>
</html>
