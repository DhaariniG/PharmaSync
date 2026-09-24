<div class="sidebar-scrim" id="sidebarScrim"></div>

<aside class="sidebar" id="sidebar">
  <div class="sidebar-top">
    <div class="brand">
      <h1>PharmaSync</h1>
      <p>Driver Logistics</p>
    </div>

    <nav class="nav-group sidebar-nav">
      <a href="<?= url('/deliveryPartner/dashboard') ?>" class="nav-link" data-nav="dashboard">
        <svg class="icon" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
        Dashboard
      </a>
      <a href="<?= url('/deliveryPartner/deliveries') ?>" class="nav-link" data-nav="deliveries">
        <svg class="icon" viewBox="0 0 24 24"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
        Deliveries
      </a>
      <a href="<?= url('/deliveryPartner/earnings') ?>" class="nav-link" data-nav="earnings">
        <svg class="icon" viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/></svg>
        Earnings
      </a>
      <a href="<?= url('/deliveryPartner/profile') ?>" class="nav-link" data-nav="profile">
        <svg class="icon" viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/></svg>
        Profile
      </a>
    </nav>

    <a href="<?= url('/deliveryPartner/new-delivery') ?>" class="go-online-btn">
      <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
      New Delivery
    </a>

    <nav class="nav-group with-border sidebar-nav">
      <a href="<?= url('/deliveryPartner/settings') ?>" class="nav-link" data-nav="settings">
        <svg class="icon" viewBox="0 0 24 24"><path d="M12 2a5 5 0 015 5v3a5 5 0 01-10 0V7a5 5 0 015-5z"/></svg>
        Settings
      </a>
      <a href="<?= url('/deliveryPartner/support') ?>" class="nav-link" data-nav="support">
        <svg class="icon" viewBox="0 0 24 24"><path d="M12 16v-4M12 8h.01"/><circle cx="12" cy="12" r="9"/></svg>
        Support
      </a>
    </nav>
  </div>

  <div class="profile-card">
    <div class="profile-avatar" id="sidebarAvatarInitials">AU</div>
    <div class="profile-meta">
      <p id="sidebarUserName">Admin User</p>
      <p id="sidebarUserRole">Dispatcher ID: 9402</p>
    </div>
  </div>
</aside>
