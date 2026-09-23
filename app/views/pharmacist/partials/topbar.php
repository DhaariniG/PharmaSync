<?php
// Top bar, shared by every Pharmacist page.
// $page_title is set by the controller. The name and role come from the
// signed-in user in the session.
?>
<header class="top-navbar">
    <div class="header-left">
        <h2 class="page-title"><?= e($page_title ?? 'Pharmacist Portal') ?></h2>
    </div>
    <div class="header-right-profile">
        <div class="notification-wrapper">
            <i data-lucide="bell" class="header-bell-icon"></i>
            <span class="notification-indicator"></span>
        </div>
        <div class="profile-details">
            <span class="profile-name"><?= e(Session::name()) ?></span>
            <span class="profile-role"><?= e(strtoupper((string) Session::role())) ?></span>
        </div>
        <div class="profile-avatar"></div>
    </div>
</header>
