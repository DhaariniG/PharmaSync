<aside class="sidebar">
    <div class="logo-area">
        <h1>PharmaSync</h1>
        <p>Pharmacist Portal</p>
    </div>
    
    <nav class="nav-links">
    <?php 
    $current_url = strtolower($_GET['url'] ?? 'pharmacist/dashboard'); 
    ?>
    
    <a href="<?= BASE_URL ?>/index.php?url=pharmacist/dashboard/index" class="nav-item <?= ($current_url === 'pharmacist/dashboard' || $current_url === 'pharmacist/dashboard/index') ? 'active' : ''; ?>">
        <i data-lucide="layout-dashboard"></i> Dashboard
    </a>
    <a href="<?= BASE_URL ?>/index.php?url=pharmacist/dashboard/prescriptions" class="nav-item <?= (strpos($current_url, 'dashboard/prescriptions') !== false) ? 'active' : ''; ?>">
        <i data-lucide="clipboard-list"></i> Prescription Queue
    </a>
    <a href="<?= BASE_URL ?>/index.php?url=pharmacist/dashboard/medicines" class="nav-item <?= (strpos($current_url, 'dashboard/medicines') !== false) ? 'active' : ''; ?>">
        <i data-lucide="package"></i> Medicine Availability
    </a>
    <a href="<?= BASE_URL ?>/index.php?url=pharmacist/dashboard/sales" class="nav-item <?= (strpos($current_url, 'dashboard/sales') !== false) ? 'active' : ''; ?>">
        <i data-lucide="shopping-cart"></i> Physical Sale
    </a>
    <a href="<?= BASE_URL ?>/index.php?url=pharmacist/dashboard/history" class="nav-item <?= (strpos($current_url, 'dashboard/history') !== false) ? 'active' : ''; ?>">
        <i data-lucide="history"></i> Prescription History
    </a>

    </nav>
    <div class="sidebar-footer">
        <a href="<?= BASE_URL ?>/index.php?url=pharmacist/dashboard/notifications" class="nav-item <?= (strpos($current_url, 'dashboard/notifications') !== false) ? 'active' : ''; ?>">
            <i data-lucide="bell"></i> Notifications
        </a>
        <a href="<?= BASE_URL ?>/index.php?url=pharmacist/dashboard/settings" class="nav-item <?= (strpos($current_url, 'dashboard/settings') !== false) ? 'active' : ''; ?>">
            <i data-lucide="settings"></i> Settings
        </a>
        <a href="<?= BASE_URL ?>/index.php?url=auth/logout" class="nav-item logout">
    <i data-lucide="log-out"></i> Logout
</a>
    </div>
</aside>