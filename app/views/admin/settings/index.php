<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Settings</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/tokens.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/chrome.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/pages.css" />
</head>
<body
  data-page="settings"
  data-user-name="<?= htmlspecialchars($user['name'] ?? 'Admin User') ?>"
  data-user-role="Administrator"
  data-user-initials="AU"
>

<div class="app-shell">
  <div id="sidebar-root">
    <?php require APP_PATH . '/views/admin/partials/sidebar.php'; ?>
  </div>

  <div class="main-col">
    <div id="topbar-root">
      <?php require APP_PATH . '/views/admin/partials/topbar.php'; ?>
    </div>

    <main class="page-content">
      <div class="page-head">
        <div>
          <h1>Settings</h1>
          <p>Manage your administrator account and system preferences.</p>
        </div>
      </div>

      <div class="detail-grid">
        <section class="panel">
          <div class="panel-header">
            <h2><i data-lucide="user"></i> Account Settings</h2>
          </div>

          <div class="form-panel">
            <div class="form-grid">
              <div class="form-group">
                <label for="settings-name">Full Name</label>
                <input id="settings-name" type="text" value="<?= htmlspecialchars($user['name'] ?? 'Admin User') ?>" />
              </div>

              <div class="form-group">
                <label for="settings-email">Email Address</label>
                <input id="settings-email" type="email" value="<?= htmlspecialchars($user['email'] ?? 'admin@example.com') ?>" />
              </div>

              <div class="form-group">
                <label for="settings-role">Role</label>
                <input id="settings-role" type="text" value="Administrator" disabled />
              </div>

              <div class="form-group">
                <label for="settings-password">New Password <span class="hint">Optional</span></label>
                <input id="settings-password" type="password" placeholder="Enter a new password" />
              </div>
            </div>
          </div>

          <div class="form-actions">
            <button type="button" class="btn btn-primary" onclick="showToast('Settings saved successfully')">
              <i data-lucide="save"></i> Save Changes
            </button>
          </div>
        </section>

        <aside class="panel">
          <div class="panel-header">
            <h2><i data-lucide="shield-check"></i> Session</h2>
          </div>
          <div class="detail-section">
            <div class="detail-row">
              <span class="k">Signed in as</span>
              <span class="v"><?= htmlspecialchars($user['name'] ?? 'Admin User') ?></span>
            </div>
            <div class="detail-row">
              <span class="k">Access level</span>
              <span class="v">Administrator</span>
            </div>
          </div>
          <div class="action-list">
            <form method="post" action="<?= url('/' . AUTH_SLUG . '/logout') ?>">
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-outline" data-logout-open>
                <i data-lucide="log-out"></i> Logout
              </button>
            </form>
          </div>
        </aside>
      </div>
    </main>
  </div>
</div>

<script src="<?= asset('assets/js/lucide.min.js') ?>"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
</body>
</html>
