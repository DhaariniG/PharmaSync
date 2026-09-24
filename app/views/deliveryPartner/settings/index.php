<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Settings</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="settings" data-page-title="Settings" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="On Shift" data-user-initials="AR">

<div class="app">
  <div id="sidebar-root"><?php require __DIR__ . '/../partials/sidebar.php'; ?></div>
  <div class="main">
    <div id="topbar-root"><?php require __DIR__ . '/../partials/topbar.php'; ?></div>

    <main class="content">

      <div class="page-header-row">
        <div>
          <h2 class="page-heading">Settings</h2>
          <p class="page-subheading">Manage notifications, app preferences and security.</p>
        </div>
      </div>

      <form class="card" id="settingsForm">
        <div class="form-panel">
          <div class="detail-section" style="padding:0 0 1.5rem;">
            <h3>Notifications</h3>
            <div class="info-list">
              <div class="info-row">
                <span class="info-label">New delivery assignments</span>
                <label class="switch"><input type="checkbox" checked /><span class="switch-track"></span></label>
              </div>
              <div class="info-row">
                <span class="info-label">Route &amp; ETA updates</span>
                <label class="switch"><input type="checkbox" checked /><span class="switch-track"></span></label>
              </div>
              <div class="info-row">
                <span class="info-label">Weekly earnings summary</span>
                <label class="switch"><input type="checkbox" checked /><span class="switch-track"></span></label>
              </div>
              <div class="info-row">
                <span class="info-label">Promotions &amp; product news</span>
                <label class="switch"><input type="checkbox" /><span class="switch-track"></span></label>
              </div>
            </div>
          </div>

          <div class="detail-section" style="padding:0 0 1.5rem;">
            <h3>App Preferences</h3>
            <div class="form-grid">
              <div class="form-group">
                <label for="units">Distance Units</label>
                <select id="units">
                  <option>Kilometers</option>
                  <option>Miles</option>
                </select>
              </div>
              <div class="form-group">
                <label for="language">Language</label>
                <select id="language">
                  <option>English</option>
                  <option>Sinhala</option>
                  <option>Tamil</option>
                </select>
              </div>
            </div>
          </div>

          <div class="detail-section" style="padding:0; border-bottom:none;">
            <h3>Security</h3>
            <div class="form-group full" style="max-width:320px;">
              <label for="pass">Change Password</label>
              <input type="password" id="pass" placeholder="New password" />
            </div>
          </div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn-primary">Save Settings</button>
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
  document.getElementById('settingsForm').addEventListener('submit', function (e) {
    e.preventDefault();
    showToast('Settings saved');
  });
</script>
</body>
</html>
