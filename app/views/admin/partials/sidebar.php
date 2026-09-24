<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="brand-title">PharmaSync</div>
    <div class="brand-sub">Admin Terminal</div>
  </div>

  <nav class="sidebar-nav">
    <a href="<?= BASE_URL ?>/admin/dashboard" data-nav="dashboard">
      <i data-lucide="layout-grid"></i> Dashboard
    </a>
    <a href="<?= BASE_URL ?>/admin/accounts" data-nav="accounts">
      <i data-lucide="users"></i> Accounts
    </a>
    <a href="<?= BASE_URL ?>/admin/orders" data-nav="orders">
      <i data-lucide="shopping-cart"></i> Orders
    </a>
    <a href="<?= BASE_URL ?>/admin/deliveries" data-nav="deliveries">
      <i data-lucide="truck"></i> Deliveries
    </a>
    <a href="<?= BASE_URL ?>/admin/inventory" data-nav="inventory">
      <i data-lucide="clipboard-list"></i> Inventory
    </a>
    <a href="<?= BASE_URL ?>/admin/suppliers" data-nav="suppliers">
      <i data-lucide="warehouse"></i> Suppliers
    </a>
    <a href="<?= BASE_URL ?>/admin/reports" data-nav="reports">
      <i data-lucide="bar-chart-2"></i> Reports
    </a>
    <a href="<?= BASE_URL ?>/admin/settings" data-nav="settings">
      <i data-lucide="settings"></i> Settings
    </a>
    <a href="<?= BASE_URL ?>/authentication/logout" data-nav="logout" data-logout-open>
      <i data-lucide="log-out"></i> Logout
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="avatar sidebar-avatar" id="sidebarAvatarInitials">AU</div>
    <div>
      <div class="sidebar-user-name" id="sidebarUserName">Admin User</div>
      <div class="sidebar-user-role" id="sidebarUserRole">Administrator</div>
    </div>
  </div>
</aside>


<div class="logout-modal" id="logoutModal" aria-hidden="true">
  <div class="logout-modal-card" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle">
    <div class="logout-modal-icon"><i data-lucide="log-out"></i></div>
    <h2 id="logoutModalTitle">Logout</h2>
    <p>Are you sure you want to logout from PharmaSync?</p>
    <div class="logout-modal-actions">
      <button type="button" class="btn btn-outline" data-logout-cancel>Cancel</button>
      <a class="btn btn-danger logout-confirm-btn" href="<?= BASE_URL ?>/authentication/logout">
        <i data-lucide="log-out"></i> Logout
      </a>
    </div>
  </div>
</div>
