<?php
// Top bar, shared by every Inventory Manager page.
// $page_title is set by the controller. Name and initials come from the
// signed-in user in the session.
$page_title = $page_title ?? '';
$signedIn   = Session::user() ?? [];
$fullName   = $signedIn['name'] ?? 'IM User';

// Initials: first letter of the first and last word of the name.
$words    = preg_split('/\s+/', trim($fullName));
$initials = strtoupper(substr($words[0], 0, 1) . (count($words) > 1 ? substr(end($words), 0, 1) : ''));

// Same static list the Notifications page shows, so the badge count
// always matches what is actually there.
$notificationCount = count(InventoryManagerNotificationController::sampleNotifications());
?>
<header class="topbar">
    <h1 class="topbar-title"><?= e($page_title) ?></h1>
    <div class="topbar-actions">
        <a class="notification-btn" href="<?= url('/InventoryManager/notifications') ?>">
            <?= icon('bell', 'material-symbols-outlined') ?>
            <?php if ($notificationCount > 0): ?>
                <span class="notification-badge"><?= $notificationCount ?></span>
            <?php endif; ?>
        </a>
        <a class="user-block" href="<?= url('/InventoryManager/profile') ?>">
            <div>
                <p class="user-name"><?= e($fullName) ?></p>
                <p class="user-role">Inventory Manager</p>
            </div>
            <div class="user-avatar"><?= e($initials) ?></div>
        </a>
    </div>
</header>
