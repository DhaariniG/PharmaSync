<?php
function ps_initials(string $name): string {
    $parts = preg_split('/\s+/', trim($name));
    $initials = '';
    foreach (array_slice($parts, 0, 2) as $p) {
        $initials .= mb_strtoupper(mb_substr($p, 0, 1));
    }
    return $initials ?: 'U';
}

// 'guest' => true means a visitor who is not logged in can open it.
// The others still show for guests, with a lock, and lead to the login page.
$__navItems = [
    ['path' => '/',                      'match' => '#^/(dashboard)?$#', 'icon' => 'layout-grid', 'label' => 'Dashboard', 'guest' => false],
    ['path' => '/catalog',               'match' => '#^/(catalog|search|alternate|product)#', 'icon' => 'pill', 'label' => 'Medicines', 'guest' => true],
    ['path' => '/prescription/upload',   'match' => '#^/prescription#', 'icon' => 'file-up', 'label' => 'Upload Prescription', 'guest' => false],
    ['path' => '/cart',                  'match' => '#^/cart#', 'icon' => 'shopping-cart', 'label' => 'Cart', 'guest' => true, 'guestOnly' => true],
    ['path' => '/orders',                'match' => '#^/(orders|order|cart|checkout)#', 'icon' => 'package', 'label' => 'Orders', 'guest' => false],
    ['path' => '/notifications',         'match' => '#^/notifications#', 'icon' => 'bell', 'label' => 'Notifications', 'guest' => false],
    ['path' => '/profile',               'match' => '#^/profile#', 'icon' => 'user', 'label' => 'Profile', 'guest' => false],
    ['path' => '/settings',              'match' => '#^/settings#', 'icon' => 'settings', 'label' => 'Settings', 'guest' => false],
];

if (!$__user) {
    // Guests get the public landing page instead of a dashboard.
    $__navItems[0] = ['path' => null, 'href' => url('/'), 'match' => '#^/home$#', 'icon' => 'house', 'label' => 'Home', 'guest' => true];
    $__navItems[4]['match'] = '#^/(orders|order|checkout)#';   // Cart has its own item for guests
} else {
    $__navItems = array_values(array_filter($__navItems, fn($i) => empty($i['guestOnly'])));
}
?>
<div class="ps-sidebar-backdrop" id="psSidebarBackdrop"></div>
<aside class="ps-sidebar" id="psSidebar">
  <div class="brand-block">
    <a href="<?= url('/') ?>" class="nounderline">
      <div class="brand"><?= icon('pill', 'me-1') ?> Pharmasync</div>
    </a>
    <div class="sub">Customer Portal</div>
  </div>

  <nav class="ps-nav">
    <?php foreach ($__navItems as $item): ?>
      <?php $__locked = !$__user && !$item['guest']; ?>
      <a href="<?= $item['href'] ?? url('/customer' . $item['path']) ?>" class="nav-link <?= preg_match($item['match'], $__currentPath) ? 'active' : '' ?>"<?= $__locked ? ' title="Log in to use this"' : '' ?>>
        <?= icon($item['icon']) ?>
        <span><?= $item['label'] ?></span>
        <?php if ($__locked): ?><span class="ms-auto muted"><?= icon('lock', 'small') ?></span><?php endif; ?>
        <?php if ($item['label'] === 'Notifications' && $__unreadCount > 0): ?>
          <span class="tag ps-badge-rx ms-auto"><?= $__unreadCount ?></span>
        <?php endif; ?>
      </a>
    <?php endforeach; ?>
  </nav>

  <?php if ($__user): ?>
    <a href="<?= BASE_URL ?>/customer/profile" class="user-card nounderline text-dark">
      <div class="ps-avatar"><?= ps_initials($__user['name']) ?></div>
      <div class="grow">
        <div class="semibold small"><?= htmlspecialchars($__user['name']) ?></div>
        <div class="muted" style="font-size:.72rem;">Premium Member</div>
      </div>
    </a>
  <?php else: ?>
    <div class="user-card guest-card">
      <div class="semibold small">Browsing as a guest</div>
      <div class="muted mb-2" style="font-size:.78rem;">Log in to check out, upload a prescription or track orders.</div>
      <a href="<?= url('/' . AUTH_SLUG . '/login') ?>" class="btn btn-ps-primary btn-sm w-100 mb-1">Log in</a>
      <a href="<?= url('/' . AUTH_SLUG . '/register') ?>" class="btn btn-ps-outline btn-sm w-100">Create account</a>
    </div>
  <?php endif; ?>
</aside>
