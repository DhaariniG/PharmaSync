<header class="top-navbar">
    <div class="header-left">
        <h2 class="page-title"><?= htmlspecialchars($pageTitle ?? 'Pharmacist Portal') ?></h2>
    </div>
    <div class="header-right-profile">
        <div class="notification-wrapper">
            <i data-lucide="bell" class="header-bell-icon"></i>
            <span class="notification-indicator"></span>
        </div>
        <div class="profile-details">
            <span class="profile-name">
                <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['name'] ?? 'Sarah Jenkins') ?>
            </span>
            <span class="profile-role">
                <?= strtoupper(htmlspecialchars($_SESSION['role'] ?? 'PHARMACIST')) ?>
            </span>
        </div>
        <div class="profile-avatar"></div>
    </div>
</header>