<?php
$__user = $_SESSION['user'] ?? null;
$__cartCount = 0;
if (class_exists('Cart')) {
    $__cartModel = new Cart();
    $__cartCount = $__cartModel->count();
}
$__unreadCount = 0;
if (class_exists('Notification')) {
    $__unreadCount = (new Notification())->unreadCount();
}
$__currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
if ($base !== '' && strpos($__currentPath, $base) === 0) {
    $__currentPath = substr($__currentPath, strlen($base));
}
if ($__currentPath === '') {
    $__currentPath = '/';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaSync — Customer</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Customer/style.css">
</head>
<body>

<div class="ps-shell">
  <?php require __DIR__ . '/sidebar.php'; ?>

  <div class="ps-main">
    <header class="ps-topbar">
      <button class="btn btn-sm lg-hidden" type="button" id="psSidebarToggle"><?= icon('menu') ?></button>

      <form action="<?= BASE_URL ?>/search" method="GET" class="ps-search-wrap">
        <?= icon('search', 'small') ?>
        <input type="text" name="q" class="ps-search w-100" placeholder="Search medicines, orders...">
      </form>

      <div class="ms-auto flex middle gap-3">
        <a href="<?= BASE_URL ?>/cart" class="relative text-dark" title="Cart">
          <?= icon('shopping-cart', 'size-5') ?>
          <?php if ($__cartCount > 0): ?>
            <span class="ps-cart-badge absolute"><?= $__cartCount ?></span>
          <?php endif; ?>
        </a>
        <?php if ($__user): ?>
        <a href="<?= BASE_URL ?>/notifications" class="ps-bell nounderline" title="Notifications">
          <?= icon('bell') ?>
          <?php if ($__unreadCount > 0): ?><span class="dot"></span><?php endif; ?>
        </a>
        <?php endif; ?>
        <?php if ($__user): ?>
          <div class="ps-menu">
            <a class="flex middle gap-2 text-dark nounderline" href="#" role="button" data-menu-toggle>
              <span class="hidden md-inline semibold small"><?= htmlspecialchars($__user['name']) ?></span>
              <div class="ps-avatar sm"><?= function_exists('ps_initials') ? ps_initials($__user['name']) : '' ?></div>
            </a>
            <div class="ps-menu-list">
              <a href="<?= BASE_URL ?>/profile"><?= icon('user', 'me-2') ?>Profile</a>
              <a href="<?= BASE_URL ?>/orders"><?= icon('package', 'me-2') ?>My Orders</a>
              <a href="<?= BASE_URL ?>/settings"><?= icon('settings', 'me-2') ?>Settings</a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </header>

    <?php if ($__flash = $_SESSION['flash'] ?? null): ?>
      <?php foreach ($__flash as $__type => $__msg): ?>
        <div class="note note-<?= htmlspecialchars($__type) ?> show rounded-0 mb-0 text-center" data-autohide role="alert">
          <?= htmlspecialchars($__msg) ?>
          <button type="button" class="close-x" data-dismiss-alert aria-label="Close"></button>
        </div>
      <?php endforeach; unset($_SESSION['flash']); endif; ?>

    <main class="ps-content">
