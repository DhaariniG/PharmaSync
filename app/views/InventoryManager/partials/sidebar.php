<?php
// Left navigation, shared by every Inventory Manager page.
// $active_page is set by the controller and decides which link is highlighted.
$active_page = $active_page ?? '';
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <?= icon('plus', 'material-symbols-outlined') ?>
        <span class="sidebar-brand-text">PharmaSync</span>
    </div>
    <nav class="sidebar-nav">
        <a href="<?= url('/InventoryManager/dashboard') ?>" class="nav-link <?= $active_page === 'dashboard' ? 'active' : '' ?>">
            <?= icon('layout-grid', 'material-symbols-outlined') ?>
            <span>Dashboard</span>
        </a>
        <a href="<?= url('/InventoryManager/medicines') ?>" class="nav-link <?= $active_page === 'medicines' ? 'active' : '' ?>">
            <?= icon('package', 'material-symbols-outlined') ?>
            <span>Medicines</span>
        </a>
        <a href="<?= url('/InventoryManager/batches') ?>" class="nav-link <?= $active_page === 'batches' ? 'active' : '' ?>">
            <?= icon('package-open', 'material-symbols-outlined') ?>
            <span>Stock &amp; Batches</span>
        </a>
        <a href="<?= url('/InventoryManager/purchase-orders') ?>" class="nav-link <?= $active_page === 'purchase_orders' ? 'active' : '' ?>">
            <?= icon('shopping-cart', 'material-symbols-outlined') ?>
            <span>Purchase Orders</span>
        </a>
        <a href="<?= url('/InventoryManager/suppliers') ?>" class="nav-link <?= $active_page === 'suppliers' ? 'active' : '' ?>">
            <?= icon('truck', 'material-symbols-outlined') ?>
            <span>Suppliers</span>
        </a>
        <div class="sidebar-section-label with-border">Alerts</div>
        <a href="<?= url('/InventoryManager/low-stock') ?>" class="nav-link <?= $active_page === 'low_stock' ? 'active' : '' ?>">
            <?= icon('triangle-alert', 'material-symbols-outlined') ?>
            <span>Low Stock Alerts</span>
        </a>
        <a href="<?= url('/InventoryManager/expiry-alerts') ?>" class="nav-link <?= $active_page === 'expiry_alerts' ? 'active' : '' ?>">
            <?= icon('bell', 'material-symbols-outlined') ?>
            <span>Expiry Alerts</span>
        </a>
        <div class="sidebar-section-label with-border">Activity</div>
        <a href="<?= url('/InventoryManager/stock-movements') ?>" class="nav-link <?= $active_page === 'stock_movements' ? 'active' : '' ?>">
            <?= icon('shuffle', 'material-symbols-outlined') ?>
            <span>Stock Movements</span>
        </a>
        <a href="<?= url('/InventoryManager/reports') ?>" class="nav-link <?= $active_page === 'reports' ? 'active' : '' ?>">
            <?= icon('activity', 'material-symbols-outlined') ?>
            <span>Reports</span>
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="<?= url('/InventoryManager/profile') ?>" class="nav-link <?= $active_page === 'profile' ? 'active' : '' ?>">
            <?= icon('user', 'material-symbols-outlined') ?>
            <span>Profile</span>
        </a>
        <?php /* Logout is POST-only + CSRF (a GET link could be triggered by another site). */ ?>
        <form method="post" action="<?= url('/' . AUTH_SLUG . '/logout') ?>" class="sidebar-logout-form">
            <?= csrf_field() ?>
            <button type="submit" class="nav-link logout">
                <?= icon('log-out', 'material-symbols-outlined') ?>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
