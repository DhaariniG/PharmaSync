<?php
// Left navigation, shared by every Pharmacist page.
// $active_page is set by the controller and decides which link is highlighted.
$active_page = $active_page ?? '';
?>
<aside class="sidebar">
    <div class="logo-area">
        <h1>PharmaSync</h1>
        <p>Pharmacist Portal</p>
    </div>

    <nav class="nav-links">
        <a href="<?= url('/pharmacist/dashboard') ?>" class="nav-item <?= $active_page === 'dashboard' ? 'active' : '' ?>">
            <i data-lucide="layout-dashboard"></i> Dashboard
        </a>
        <a href="<?= url('/pharmacist/prescriptions') ?>" class="nav-item <?= $active_page === 'prescriptions' ? 'active' : '' ?>">
            <i data-lucide="clipboard-list"></i> Prescription Queue
        </a>
        <a href="<?= url('/pharmacist/medicines') ?>" class="nav-item <?= $active_page === 'medicines' ? 'active' : '' ?>">
            <i data-lucide="package"></i> Medicine Availability
        </a>
        <a href="<?= url('/pharmacist/sales') ?>" class="nav-item <?= $active_page === 'sales' ? 'active' : '' ?>">
            <i data-lucide="shopping-cart"></i> Physical Sale
        </a>
        <a href="<?= url('/pharmacist/history') ?>" class="nav-item <?= $active_page === 'history' ? 'active' : '' ?>">
            <i data-lucide="history"></i> Order History
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= url('/pharmacist/notifications') ?>" class="nav-item <?= $active_page === 'notifications' ? 'active' : '' ?>">
            <i data-lucide="bell"></i> Notifications
        </a>
        <a href="<?= url('/pharmacist/settings') ?>" class="nav-item <?= $active_page === 'settings' ? 'active' : '' ?>">
            <i data-lucide="settings"></i> Settings
        </a>
        <?php /* Logout is POST-only + CSRF (a GET link could be triggered by another site). */ ?>
        <form method="post" action="<?= url('/' . AUTH_SLUG . '/logout') ?>" class="sidebar-logout-form">
            <?= csrf_field() ?>
            <button type="submit" class="nav-item logout">
                <i data-lucide="log-out"></i> Logout
            </button>
        </form>
    </div>
</aside>
