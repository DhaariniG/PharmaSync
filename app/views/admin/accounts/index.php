<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>PharmaSync — Accounts Management</title>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/tokens.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/chrome.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/pages.css" />
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
        <?php require APP_PATH . '/views/admin/partials/sidebar.php'; ?>
    </div>


    <div class="main-col">

        <!-- TOPBAR -->
        <div id="topbar-root">
            <?php require APP_PATH . '/views/admin/partials/topbar.php'; ?>
        </div>


        <main class="page-content">

            <!-- =====================================================
                 PAGE HEADER
            ====================================================== -->

            <div class="page-head">

                <div>
                    <h1>Accounts Management</h1>

                    <p>
                        Oversee and manage system users across the
                        pharmaceutical network.
                    </p>
                </div>


                <div class="page-head-actions">

                    <a
                        href="<?= BASE_URL ?>/admin/accounts/create"
                        class="btn btn-dark"
                    >
                        <i data-lucide="user-plus"></i>
                        Create New Account
                    </a>

                </div>

            </div>


            <!-- =====================================================
                 STAT CARDS
            ====================================================== -->

            <div class="stat-grid cols-4">

                <div class="stat-card">

                    <div
                        class="stat-label"
                        style="
                            text-transform:uppercase;
                            font-size:11.5px;
                            font-weight:700;
                        "
                    >
                        Total Users
                    </div>

                    <div class="stat-value">
                        <?= count($accounts) ?>
                    </div>

                </div>


                <div class="stat-card">

                    <div
                        class="stat-label"
                        style="
                            text-transform:uppercase;
                            font-size:11.5px;
                            font-weight:700;
                        "
                    >
                        Active Pharmacists
                    </div>

                    <div class="stat-value">
                        342
                    </div>

                </div>


                <div class="stat-card">

                    <div
                        class="stat-label"
                        style="
                            text-transform:uppercase;
                            font-size:11.5px;
                            font-weight:700;
                        "
                    >
                        On-Duty Drivers
                    </div>

                    <div class="stat-value">
                        86
                    </div>

                </div>


                <div class="stat-card">

                    <div
                        class="stat-label"
                        style="
                            text-transform:uppercase;
                            font-size:11.5px;
                            font-weight:700;
                        "
                    >
                        System Health
                    </div>

                    <div class="stat-value">
                        99.9%
                    </div>

                </div>

            </div>


            <!-- =====================================================
                 ACCOUNTS PANEL
            ====================================================== -->

            <div class="panel">


                <!-- FILTER BAR -->

                <div class="filter-bar">

                    <span class="label">
                        Filter by:
                    </span>


                    <span class="select-chip">

                        All Roles

                        <i data-lucide="chevron-down"></i>

                    </span>


                    <span class="select-chip">

                        All Statuses

                        <i data-lucide="chevron-down"></i>

                    </span>


                    <a
                        class="export-link"
                        href="javascript:void(0)"
                        id="exportAccountsBtn"
                    >

                        <i data-lucide="download"></i>

                        Export CSV

                    </a>

                </div>


                <!-- =================================================
                     ACCOUNTS TABLE
                ================================================== -->

                <table class="data-table">

                    <thead>

                        <tr>

                            <th>User Details</th>

                            <th>Role</th>

                            <th>Status</th>

                            <th>Last Activity</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php if (empty($accounts)): ?>


                        <!-- No users -->

                        <tr>

                            <td
                                colspan="5"
                                style="
                                    text-align:center;
                                    padding:40px;
                                "
                            >

                                No accounts found.

                            </td>

                        </tr>


                    <?php else: ?>


                        <?php foreach ($accounts as $account): ?>


                            <?php

                            /*
                             * -----------------------------------------
                             * USER INITIALS
                             * -----------------------------------------
                             *
                             * Example:
                             *
                             * Nadeesha Perera -> NP
                             *
                             */

                            $nameParts = preg_split(
                                '/\s+/',
                                trim($account['name'])
                            );


                            $initials = '';


                            foreach (
                                array_slice($nameParts, 0, 2)
                                as $part
                            ) {

                                if ($part !== '') {

                                    $initials .= strtoupper(
                                        substr($part, 0, 1)
                                    );

                                }

                            }



                            /*
                             * -----------------------------------------
                             * ROLE BADGE
                             * -----------------------------------------
                             */

                            $roleClass = match ($account['role']) {

                                'Pharmacist'
                                    => 'badge-cyan',

                                'Delivery_Partner'
                                    => 'badge-amber',

                                'Customer'
                                    => 'badge-gray',

                                'Admin'
                                    => 'badge-teal',

                                'Inventory_Manager'
                                    => 'badge-green',

                                default
                                    => 'badge-gray',

                            };


                            /*
                             * Database:
                             *
                             * Delivery_Partner
                             *
                             * Display:
                             *
                             * Delivery Partner
                             */

                            $displayRole = str_replace(
                                '_',
                                ' ',
                                $account['role']
                            );



                            /*
                             * -----------------------------------------
                             * STATUS
                             * -----------------------------------------
                             */

                            $statusClass = match ($account['status']) {

                                'Active'
                                    => 'active',

                                'Inactive'
                                    => 'inactive',

                                'Suspended'
                                    => 'suspended',

                                default
                                    => 'inactive',

                            };



                            /*
                             * -----------------------------------------
                             * LAST LOGIN
                             * -----------------------------------------
                             */

                            $lastActivity =
                                !empty($account['last_login'])

                                ? date(
                                    'M d, Y H:i',
                                    strtotime(
                                        $account['last_login']
                                    )
                                )

                                : 'Never';

                            ?>


                            <tr>


                                <!-- USER DETAILS -->

                                <td>

                                    <div class="user-cell">


                                        <div
                                            class="avatar"
                                            style="
                                                background:var(--teal-100);
                                                color:var(--teal-700);
                                            "
                                        >

                                            <?= htmlspecialchars(
                                                $initials
                                            ) ?>

                                        </div>


                                        <div>


                                            <div class="u-name">

                                                <?= htmlspecialchars(
                                                    $account['name']
                                                ) ?>

                                            </div>


                                            <div class="u-id">

                                                ID:
                                                <?= (int)$account['id'] ?>

                                            </div>


                                        </div>


                                    </div>

                                </td>



                                <!-- ROLE -->

                                <td>

                                    <span
                                        class="badge <?= $roleClass ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $displayRole
                                        ) ?>

                                    </span>

                                </td>



                                <!-- STATUS -->

                                <td>

                                    <span
                                        class="
                                            status-text
                                            <?= $statusClass ?>
                                        "
                                    >

                                        ●
                                        <?= htmlspecialchars(
                                            $account['status']
                                        ) ?>

                                    </span>

                                </td>



                                <!-- LAST ACTIVITY -->

                                <td>

                                    <?= htmlspecialchars(
                                        $lastActivity
                                    ) ?>

                                </td>



                                <!-- =====================================
                                     ACTIONS
                                ====================================== -->

                                <td>

                                    <div class="row-actions">


                                        <!--
                                            EDIT BUTTON

                                            Example result:

                                            /admin/accounts/detail
                                            ?id=1&mode=edit
                                        -->

                                        <a
                                            href="<?= BASE_URL ?>/admin/accounts/detail?id=<?= (int)$account['id'] ?>&mode=edit"
                                            title="Edit account"
                                        >

                                            <i data-lucide="pencil"></i>

                                        </a>



                                        <!--
                                            VIEW BUTTON

                                            Example result:

                                            /admin/accounts/detail?id=1
                                        -->

                                        <a
                                            href="<?= BASE_URL ?>/admin/accounts/detail?id=<?= (int)$account['id'] ?>"
                                            title="View account"
                                        >

                                            <i data-lucide="eye"></i>

                                        </a>


                                    </div>

                                </td>


                            </tr>


                        <?php endforeach; ?>


                    <?php endif; ?>


                    </tbody>

                </table>



                <!-- =================================================
                     PAGER
                ================================================== -->

                <div class="pager">

                    <span>

                        Showing
                        <?= count($accounts) ?>
                        account<?= count($accounts) === 1 ? '' : 's' ?>

                    </span>


                    <div class="pager-btns">

                        <button type="button">

                            <i
                                data-lucide="chevron-left"
                                style="
                                    width:14px;
                                    height:14px;
                                "
                            ></i>

                        </button>


                        <button
                            type="button"
                            class="active"
                        >
                            1
                        </button>


                        <button type="button">

                            <i
                                data-lucide="chevron-right"
                                style="
                                    width:14px;
                                    height:14px;
                                "
                            ></i>

                        </button>

                    </div>

                </div>



                <!-- =================================================
                     WARNING BANNER
                ================================================== -->

                <div class="banner-warn">

                    <div class="banner-warn-left">

                        <i data-lucide="triangle-alert"></i>


                        <div>

                            <div class="banner-warn-title">

                                Critical Verification Required

                            </div>


                            <div class="banner-warn-body">

                                Driver verification information
                                will appear here when connected
                                to delivery partner data.

                            </div>

                        </div>

                    </div>


                    <!--
                        Removed the old fake link:

                        detail?id=DR-4432

                        because the real users table uses
                        integer user_id values.
                    -->

                </div>


            </div>

        </main>

    </div>

</div>


<script src="https://unpkg.com/lucide@latest"></script>

<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>


<script>

const exportButton =
    document.getElementById('exportAccountsBtn');


if (exportButton) {

    exportButton.addEventListener(
        'click',
        () => {

            showToast(
                'CSV export started — check your downloads'
            );

        }
    );

}

</script>


</body>
</html>