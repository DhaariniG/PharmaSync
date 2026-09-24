<?php

$name      = $account['name'] ?? '';
$email     = $account['email'] ?? '';
$phone     = $account['phone'] ?? '';
$address   = $account['address'] ?? '';
$role      = $account['role'] ?? '';
$status    = $account['status'] ?? '';
$id        = (int) ($account['id'] ?? 0);
$createdAt = $account['created_at'] ?? null;
$lastLogin = $account['last_login'] ?? null;


/*
 * Are we in edit mode?
 *
 * Normal:
 * detail?id=1
 *
 * Edit:
 * detail?id=1&mode=edit
 */
$editMode = ($_GET['mode'] ?? '') === 'edit';


/*
 * Create initials.
 *
 * thehara h -> TH
 */
$nameParts = preg_split('/\s+/', trim($name));

$initials = '';

foreach (array_slice($nameParts, 0, 2) as $part) {

    if ($part !== '') {
        $initials .= strtoupper(substr($part, 0, 1));
    }
}


/*
 * Role badge colour.
 */
$roleClass = match ($role) {

    'Pharmacist'        => 'badge-cyan',
    'Delivery_Partner'  => 'badge-amber',
    'Customer'          => 'badge-gray',
    'Admin'             => 'badge-teal',
    'Inventory_Manager' => 'badge-green',

    default             => 'badge-gray',
};


/*
 * Make database role readable.
 */
$displayRole = str_replace('_', ' ', $role);


/*
 * Status CSS.
 */
$statusClass = match ($status) {

    'Active'    => 'active',
    'Inactive'  => 'inactive',
    'Suspended' => 'suspended',

    default     => 'inactive',
};


/*
 * Joined date.
 */
$joined = $createdAt
    ? date('M d, Y', strtotime($createdAt))
    : '—';


/*
 * Last activity.
 */
$lastActivity = $lastLogin
    ? date('M d, Y H:i', strtotime($lastLogin))
    : 'Never';


?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8" />

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
/>

<title>
    PharmaSync — Account Details
</title>

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/assets/css/Admin/tokens.css"
/>

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/assets/css/Admin/chrome.css"
/>

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/assets/css/Admin/pages.css"
/>

</head>


<body
    data-page="accounts"
    data-user-name="Admin User"
    data-user-role="Administrator"
    data-user-initials="AU"
>

<div class="app-shell">


    <!-- SIDEBAR -->

    <div id="sidebar-root">

        <?php
        require APP_PATH . '/views/admin/partials/sidebar.php';
        ?>

    </div>


    <div class="main-col">


        <!-- TOPBAR -->

        <div id="topbar-root">

            <?php
            require APP_PATH . '/views/admin/partials/topbar.php';
            ?>

        </div>


        <main class="page-content">


            <!-- BACK -->

            <a
                class="back-link"
                href="<?= BASE_URL ?>/admin/accounts"
            >

                <i data-lucide="arrow-left"></i>

                Back to Accounts

            </a>


            <!-- SUCCESS / ERROR MESSAGES (set with $this->flash()) -->

            <?php require APP_PATH . '/views/admin/partials/flash.php'; ?>


            <div class="detail-grid">


                <!-- =================================================
                     LEFT PANEL
                ================================================== -->

                <div class="panel">


                    <!-- USER HEADER -->

                    <div class="detail-header">


                        <div
                            class="avatar"
                            style="
                                background:var(--teal-100);
                                color:var(--teal-700);
                            "
                        >

                            <?= htmlspecialchars($initials) ?>

                        </div>


                        <div>

                            <h2>
                                <?= htmlspecialchars($name) ?>
                            </h2>


                            <div class="sub">

                                <span
                                    class="badge <?= $roleClass ?>"
                                >

                                    <?= htmlspecialchars($displayRole) ?>

                                </span>

                                &nbsp;

                                ID: <?= $id ?>

                            </div>

                        </div>


                    </div>


                    <?php if ($editMode): ?>


                        <!-- =========================================
                             EDIT MODE
                        ========================================== -->

                        <div class="detail-section">

                            <h3>
                                Edit Profile
                            </h3>


                            <form
                                method="POST"
                                action="<?= BASE_URL ?>/admin/accounts/update"
                            >

                                <?= csrf_field() ?>


                                <!--
                                    Very important.

                                    This tells update() which database
                                    user should be updated.
                                -->

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= $id ?>"
                                >


                                <div class="form-grid">


                                    <!-- FULL NAME -->

                                    <div class="form-group">

                                        <label for="full_name">
                                            Full Name *
                                        </label>

                                        <input
                                            type="text"
                                            id="full_name"
                                            name="full_name"
                                            value="<?= htmlspecialchars($name) ?>"
                                            required
                                        >

                                    </div>


                                    <!-- EMAIL -->

                                    <div class="form-group">

                                        <label for="email">
                                            Email Address *
                                        </label>

                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            value="<?= htmlspecialchars($email) ?>"
                                            required
                                        >

                                    </div>


                                    <!-- PHONE -->

                                    <div class="form-group">

                                        <label for="phone">
                                            Phone Number *
                                        </label>

                                        <input
                                            type="text"
                                            id="phone"
                                            name="phone"
                                            value="<?= htmlspecialchars($phone) ?>"
                                            required
                                        >

                                    </div>


                                    <!-- ADDRESS -->

                                    <div class="form-group">

                                        <label for="address">
                                            Address
                                        </label>

                                        <input
                                            type="text"
                                            id="address"
                                            name="address"
                                            value="<?= htmlspecialchars($address) ?>"
                                        >

                                    </div>


                                    <!-- ROLE -->

                                    <div class="form-group">

                                        <label for="role">
                                            Role *
                                        </label>

                                        <select
                                            id="role"
                                            name="role"
                                            required
                                        >

                                            <option
                                                value="Customer"
                                                <?= $role === 'Customer'
                                                    ? 'selected'
                                                    : '' ?>
                                            >
                                                Customer
                                            </option>


                                            <option
                                                value="Pharmacist"
                                                <?= $role === 'Pharmacist'
                                                    ? 'selected'
                                                    : '' ?>
                                            >
                                                Pharmacist
                                            </option>


                                            <option
                                                value="Admin"
                                                <?= $role === 'Admin'
                                                    ? 'selected'
                                                    : '' ?>
                                            >
                                                Admin
                                            </option>


                                            <option
                                                value="Inventory_Manager"
                                                <?= $role === 'Inventory_Manager'
                                                    ? 'selected'
                                                    : '' ?>
                                            >
                                                Inventory Manager
                                            </option>


                                            <option
                                                value="Delivery_Partner"
                                                <?= $role === 'Delivery_Partner'
                                                    ? 'selected'
                                                    : '' ?>
                                            >
                                                Delivery Partner
                                            </option>

                                        </select>

                                    </div>


                                    <!-- STATUS -->

                                    <div class="form-group">

                                        <label for="status">
                                            Status *
                                        </label>

                                        <select
                                            id="status"
                                            name="status"
                                            required
                                        >

                                            <option
                                                value="Active"
                                                <?= $status === 'Active'
                                                    ? 'selected'
                                                    : '' ?>
                                            >
                                                Active
                                            </option>


                                            <option
                                                value="Inactive"
                                                <?= $status === 'Inactive'
                                                    ? 'selected'
                                                    : '' ?>
                                            >
                                                Inactive
                                            </option>


                                            <option
                                                value="Suspended"
                                                <?= $status === 'Suspended'
                                                    ? 'selected'
                                                    : '' ?>
                                            >
                                                Suspended
                                            </option>

                                        </select>

                                    </div>


                                </div>


                                <!-- FORM BUTTONS -->

                                <div class="form-actions">


                                    <a
                                        href="<?= BASE_URL ?>/admin/accounts/detail?id=<?= $id ?>"
                                        class="btn btn-outline"
                                    >

                                        Cancel

                                    </a>


                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >

                                        <i data-lucide="check"></i>

                                        Save Changes

                                    </button>


                                </div>


                            </form>


                        </div>


                    <?php else: ?>


                        <!-- =========================================
                             NORMAL VIEW MODE
                        ========================================== -->

                        <div class="detail-section">


                            <h3>
                                Profile Information
                            </h3>


                            <!-- EMAIL -->

                            <div class="detail-row">

                                <span class="k">
                                    Email
                                </span>

                                <span class="v">

                                    <?= htmlspecialchars($email) ?>

                                </span>

                            </div>


                            <!-- PHONE -->

                            <div class="detail-row">

                                <span class="k">
                                    Phone
                                </span>

                                <span class="v">

                                    <?= htmlspecialchars(
                                        $phone ?: '—'
                                    ) ?>

                                </span>

                            </div>


                            <!-- ADDRESS -->

                            <div class="detail-row">

                                <span class="k">
                                    Address
                                </span>

                                <span class="v">

                                    <?= htmlspecialchars(
                                        $address ?: '—'
                                    ) ?>

                                </span>

                            </div>


                            <!-- ROLE -->

                            <div class="detail-row">

                                <span class="k">
                                    Role
                                </span>

                                <span class="v">

                                    <?= htmlspecialchars(
                                        $displayRole
                                    ) ?>

                                </span>

                            </div>


                            <!-- STATUS -->

                            <div class="detail-row">

                                <span class="k">
                                    Status
                                </span>

                                <span class="v">

                                    <span
                                        class="
                                            status-text
                                            <?= $statusClass ?>
                                        "
                                    >

                                        ●
                                        <?= htmlspecialchars($status) ?>

                                    </span>

                                </span>

                            </div>


                            <!-- JOINED -->

                            <div class="detail-row">

                                <span class="k">
                                    Joined
                                </span>

                                <span class="v">

                                    <?= htmlspecialchars($joined) ?>

                                </span>

                            </div>


                        </div>


                        <!-- ACTIVITY -->

                        <div class="detail-section">

                            <h3>
                                Recent Activity
                            </h3>


                            <div class="detail-row">

                                <span class="k">
                                    Last Login
                                </span>

                                <span class="v">

                                    <?= htmlspecialchars(
                                        $lastActivity
                                    ) ?>

                                </span>

                            </div>


                        </div>


                    <?php endif; ?>


                </div>


                <!-- =================================================
                     RIGHT ACTION PANEL
                ================================================== -->

                <div class="panel">


                    <div class="panel-header">

                        <h2>
                            Actions
                        </h2>

                    </div>


                    <div class="action-list">


                        <?php if (!$editMode): ?>


                            <!-- EDIT PROFILE -->

                            <a
                                href="<?= BASE_URL ?>/admin/accounts/detail?id=<?= $id ?>&mode=edit"
                                class="btn btn-outline"
                            >

                                <i data-lucide="pencil"></i>

                                Edit Profile

                            </a>


                            <!-- SUSPEND -->

                            <?php if ($status !== 'Suspended'): ?>

                                <button
                                    type="button"
                                    class="btn btn-outline"
                                >

                                    <i data-lucide="ban"></i>

                                    Suspend Account

                                </button>

                            <?php endif; ?>


                            <!-- ACTIVATE -->

                            <?php if ($status !== 'Active'): ?>

                                <button
                                    type="button"
                                    class="btn btn-outline"
                                >

                                    <i data-lucide="check"></i>

                                    Activate Account

                                </button>

                            <?php endif; ?>


                            <!-- RESET PASSWORD -->

                            <button
                                type="button"
                                class="btn btn-outline"
                            >

                                <i data-lucide="rotate-ccw"></i>

                                Reset Password

                            </button>
                            <!-- DELETE ACCOUNT -->

            <form
                method="POST"
                action="<?= BASE_URL ?>/admin/accounts/delete"
                onsubmit="return confirm('Are you sure you want to delete this account? This action cannot be undone.');"
            >

                <?= csrf_field() ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?= $id ?>"
                >

                <button
                    type="submit"
                    class="btn btn-outline"
                    style="
                        width:100%;
                        color:#dc2626;
                        border-color:#fecaca;
                    "
                >

                    <i data-lucide="trash-2"></i>

                    Delete Account

                </button>

            </form>


                        <?php else: ?>


                            <a
                                href="<?= BASE_URL ?>/admin/accounts/detail?id=<?= $id ?>"
                                class="btn btn-outline"
                            >

                                <i data-lucide="x"></i>

                                Cancel Editing

                            </a>


                        <?php endif; ?>


                    </div>


                </div>


            </div>


        </main>


    </div>


</div>


<script src="https://unpkg.com/lucide@latest"></script>

<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>


<script>

if (window.lucide) {
    lucide.createIcons();
}

</script>


</body>

</html>