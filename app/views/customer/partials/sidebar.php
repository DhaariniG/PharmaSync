<?php
function ps_initials(string $name): string {
    $parts = preg_split('/\s+/', trim($name));
    $initials = '';
    foreach (array_slice($parts, 0, 2) as $p) {
        $initials .= mb_strtoupper(mb_substr($p, 0, 1));
    }
    return $initials ?: 'U';
}

$__navItems = [
    ['path' => '/',                      'match' => '#^/(dashboard)?$#', 'icon' => 'layout-grid', 'label' => 'Dashboard'],
    ['path' => '/catalog',               'match' => '#^/(catalog|search|alternate|product)#', 'icon' => 'pill', 'label' => 'Medicines'],
    ['path' => '/prescription/upload',   'match' => '#^/prescription#', 'icon' => 'file-up', 'label' => 'Upload Prescription'],
    ['path' => '/orders',                'match' => '#^/(orders|order|cart|checkout)#', 'icon' => 'shopping-cart', 'label' => 'Orders'],
    ['path' => '/notifications',         'match' => '#^/notifications#', 'icon' => 'bell', 'label' => 'Notifications'],
    ['path' => '/profile',               'match' => '#^/profile#', 'icon' => 'user', 'label' => 'Profile'],
    ['path' => '/settings',              'match' => '#^/settings#', 'icon' => 'settings', 'label' => 'Settings'],
];
?>
<div class="ps-sidebar-backdrop" id="psSidebarBackdrop"></div>
<aside class="ps-sidebar" id="psSidebar">
  <div class="brand-block">
    <a href="<?= BASE_URL ?>/" class="nounderline">
      <div class="brand"><?= icon('pill', 'me-1') ?> Pharmasync</div>
    </a>
    <div class="sub">Customer Portal</div>
  </div>

  <nav class="ps-nav">
    <?php foreach ($__navItems as $item): ?>
      <a href="<?= BASE_URL . $item['path'] ?>" class="nav-link <?= preg_match($item['match'], $__currentPath) ? 'active' : '' ?>">
        <?= icon($item['icon']) ?>
        <span><?= $item['label'] ?></span>
        <?php if ($item['label'] === 'Notifications' && $__unreadCount > 0): ?>
          <span class="tag ps-badge-rx ms-auto"><?= $__unreadCount ?></span>
        <?php endif; ?>
      </a>
    <?php endforeach; ?>
  </nav>

  <?php if ($__user): ?>
    <a href="<?= BASE_URL ?>/profile" class="user-card nounderline text-dark">
      <div class="ps-avatar"><?= ps_initials($__user['name']) ?></div>
      <div class="grow">
        <div class="semibold small"><?= htmlspecialchars($__user['name']) ?></div>
        <div class="muted" style="font-size:.72rem;">Premium Member</div>
      </div>
    </a>
  <?php endif; ?>
</aside>
