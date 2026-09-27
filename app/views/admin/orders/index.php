<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>PharmaSync — Orders Management</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/tokens.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/chrome.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/pages.css" />
</head>

<body
    data-page="orders"
    data-user-name="Admin User"
    data-user-role="Administrator"
    data-user-initials="AU"
>

<div class="app-shell">

    <!-- =========================
         SIDEBAR
    ========================== -->
    <div id="sidebar-root">
        <?php require APP_PATH . '/views/admin/partials/sidebar.php'; ?>
    </div>


    <!-- =========================
         MAIN COLUMN
    ========================== -->
    <div class="main-col">

        <!-- Topbar -->
        <div id="topbar-root">
            <?php require APP_PATH . '/views/admin/partials/topbar.php'; ?>
        </div>


        <!-- =========================
             PAGE CONTENT
        ========================== -->
        <main class="page-content">


            <!-- =========================
                 PAGE HEADER
            ========================== -->
            <div class="page-head">

                <div>
                    <h1>Orders Management</h1>

                    <p>
                        Real-time pharmaceutical supply chain monitoring.
                    </p>
                </div>


                <div class="page-head-actions">

                    <button class="btn btn-outline">
                        <i data-lucide="filter"></i>
                        Filter View
                    </button>

                    <a
                        href="<?= BASE_URL ?>/admin/orders/create"
                        class="btn btn-primary"
                    >
                        <i data-lucide="plus"></i>
                        Create New Order
                    </a>

                </div>

            </div>



            <!-- =========================
                 ORDER STATISTICS
            ========================== -->
            <div class="stat-grid cols-4">


                <!-- Active Orders -->
                <div class="stat-card">

                    <div class="stat-label">

                        Active Orders

                        <i
                            data-lucide="shopping-basket"
                            style="
                                width:16px;
                                height:16px;
                                color:var(--teal-600);
                            "
                        ></i>

                    </div>

                    <div class="stat-value">
                        1,284
                    </div>

                </div>



                <!-- Pending Fulfillment -->
                <div class="stat-card">

                    <div class="stat-label">

                        Pending Fulfillment

                        <i
                            data-lucide="clipboard"
                            style="
                                width:16px;
                                height:16px;
                                color:#b45309;
                            "
                        ></i>

                    </div>

                    <div class="stat-value">
                        42
                    </div>

                </div>



                <!-- In Transit -->
                <div class="stat-card">

                    <div class="stat-label">

                        In Transit

                        <i
                            data-lucide="truck"
                            style="
                                width:16px;
                                height:16px;
                                color:var(--teal-600);
                            "
                        ></i>

                    </div>

                    <div class="stat-value">
                        156
                    </div>

                </div>



                <!-- Completed -->
                <div class="stat-card">

                    <div class="stat-label">

                        Completed (24H)

                        <i
                            data-lucide="circle-check"
                            style="
                                width:16px;
                                height:16px;
                                color:var(--green-600);
                            "
                        ></i>

                    </div>

                    <div class="stat-value">
                        892
                    </div>

                </div>

            </div>



            <!-- =========================
                 URGENT ORDERS WARNING
            ========================== -->
            <div class="banner-warn orders-banner">

                <div class="banner-warn-left">

                    <i data-lucide="triangle-alert"></i>

                    <div>

                        <div class="banner-warn-title">
                            Urgent Orders Attention Required
                        </div>

                        <div class="banner-warn-body">
                            3 Life-critical medication orders have exceeded
                            the 30-minute fulfillment window.
                        </div>

                    </div>

                </div>


                <a
                    href="<?= BASE_URL ?>/admin/orders/urgent"
                    class="btn btn-dark"
                >
                    View Urgent Queue
                </a>

            </div>



            <!-- ==================================================
                 ORDER KANBAN GRID

                 Row 1:
                 Pending Fulfillment | In Transit

                 Row 2:
                 Completed Orders - Full Width
            =================================================== -->
            <div class="kanban-grid">


                <!-- =============================================
                     PENDING FULFILLMENT
                ============================================== -->
                <div class="kanban-col">

                    <div class="kanban-col-header">

                        <div class="kanban-col-title">

                            <span class="dot dot-amber"></span>

                            Pending Fulfillment

                            <span class="kanban-col-count">
                                14
                            </span>

                        </div>


                        <button
                            class="icon-btn"
                            style="
                                border:none;
                                width:26px;
                                height:26px;
                            "
                        >
                            <i
                                data-lucide="more-vertical"
                                style="
                                    width:16px;
                                    height:16px;
                                "
                            ></i>
                        </button>

                    </div>


                    <div class="kanban-col-body">


                        <!-- Pending Order 1 -->
                        <div class="order-card">

                            <div class="order-card-top">

                                <span class="badge badge-amber">
                                    Urgent
                                </span>

                                <span class="oc-id">
                                    #ORD-9021
                                </span>

                            </div>


                            <div class="oc-title">
                                22, Kotte Road, Kotte
                            </div>


                            <div class="oc-desc">
                                Insulin Glargine - 50 Vials
                            </div>


                            <div class="oc-meta">

                                <span></span>

                                <a
                                    class="oc-link"
                                    href="<?= BASE_URL ?>/admin/orders/detail?id=ORD-9021"
                                >
                                    Begin Packing
                                </a>

                            </div>

                        </div>



                        <!-- Pending Order 2 -->
                        <div class="order-card">

                            <div class="order-card-top">

                                <span class="badge badge-gray">
                                    Normal
                                </span>

                                <span class="oc-id">
                                    #ORD-9022
                                </span>

                            </div>


                            <div class="oc-title">
                                64, Navinna Road
                            </div>


                            <div class="oc-desc">
                                Amoxicillin 500mg - 200 Capsules
                            </div>


                            <div class="oc-meta">

                                <span></span>

                                <a
                                    class="oc-link"
                                    href="<?= BASE_URL ?>/admin/orders/detail?id=ORD-9022"
                                >
                                    Begin Packing
                                </a>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- =============================================
                     IN TRANSIT
                ============================================== -->
                <div class="kanban-col">

                    <div class="kanban-col-header">

                        <div class="kanban-col-title">

                            <span class="dot dot-cyan"></span>

                            In Transit

                            <span class="kanban-col-count">
                                28
                            </span>

                        </div>


                        <button
                            class="icon-btn"
                            style="
                                border:none;
                                width:26px;
                                height:26px;
                            "
                        >

                            <i
                                data-lucide="more-vertical"
                                style="
                                    width:16px;
                                    height:16px;
                                "
                            ></i>

                        </button>

                    </div>


                    <div class="kanban-col-body">


                        <!-- Transit Order 1 -->
                        <div class="order-card">

                            <div class="order-card-top">

                                <span class="badge badge-cyan">
                                    Dispatched
                                </span>

                                <span class="oc-id">
                                    #ORD-8995
                                </span>

                            </div>


                            <div class="oc-title">
                                23, Astoria, Re de Mel R
                            </div>


                            <div class="oc-desc">
                                Epinephrine Auto-Injectors - 12 Units
                            </div>


                            <div class="oc-progress-row">
                                <b></b>
                            </div>


                            <!-- Corrected Progress Bar -->
                            <div class="bar-track">

                                <div
                                    class="bar-fill"
                                    style="width:75%;"
                                ></div>

                            </div>


                            <div class="oc-loc">

                                <span></span>

                                <a
                                    class="oc-link"
                                    href="<?= BASE_URL ?>/admin/orders/detail?id=ORD-8995"
                                ></a>

                            </div>

                        </div>



                        <!-- Transit Order 2 -->
                        <div class="order-card">

                            <div class="order-card-top">

                                <span class="badge badge-cyan">
                                    Dispatched
                                </span>

                                <span class="oc-id">
                                    #ORD-8991
                                </span>

                            </div>


                            <div class="oc-title">
                                24, Reid Avenue
                            </div>


                            <div class="oc-desc">
                                Lisinopril 10mg - 15 Bottles
                            </div>


                            <!-- Corrected Progress Bar -->
                            <div class="bar-track">

                                <div
                                    class="bar-fill"
                                    style="width:20%;"
                                ></div>

                            </div>


                            <div class="oc-loc">

                                <span></span>

                                <a
                                    class="oc-link"
                                    href="<?= BASE_URL ?>/admin/orders/detail?id=ORD-8991"
                                ></a>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- =============================================
                     COMPLETED ORDERS
                     This section spans BOTH grid columns
                ============================================== -->
                <div class="kanban-col completed-orders">

                    <div class="kanban-col-header">

                        <div class="kanban-col-title">

                            <span class="dot dot-green"></span>

                            Completed Orders

                            <span class="kanban-col-count">
                                452
                            </span>

                        </div>


                        <button
                            class="icon-btn"
                            style="
                                border:none;
                                width:26px;
                                height:26px;
                            "
                        >

                            <i
                                data-lucide="more-vertical"
                                style="
                                    width:16px;
                                    height:16px;
                                "
                            ></i>

                        </button>

                    </div>


                    <div class="kanban-col-body">


                        <!-- Completed Order 1 -->
                        <div class="order-card">

                            <div class="order-card-top">

                                <span class="badge badge-teal">
                                    Delivered
                                </span>

                                <span class="oc-id">
                                    #ORD-8940
                                </span>

                            </div>


                            <div class="oc-title">
                                Science Faculty,UOC
                            </div>


                            <div class="oc-desc">
                                Mixed Pharmaceutical Supplies - Bulk
                            </div>


                            <div class="oc-meta">

                                


                                <a
                                    class="oc-link"
                                    href="<?= BASE_URL ?>/admin/orders/detail?id=ORD-8940"
                                >
                                    View
                                </a>

                            </div>

                        </div>



                        <!-- Completed Order 2 -->
                        <div class="order-card">

                            <div class="order-card-top">

                                <span class="badge badge-teal">
                                    Delivered
                                </span>

                                <span class="oc-id">
                                    #ORD-8938
                                </span>

                            </div>


                            <div class="oc-title">
                                Faculty of Arts, UOC
                            </div>


                            <div class="oc-desc">
                                Gabapentin 300mg - 400 Units
                            </div>


                            <div class="oc-meta">

                                


                                <a
                                    class="oc-link"
                                    href="<?= BASE_URL ?>/admin/orders/detail?id=ORD-8938"
                                >
                                    View
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <!-- END KANBAN GRID -->


        </main>
        <!-- END PAGE CONTENT -->

    </div>
    <!-- END MAIN COLUMN -->

</div>
<!-- END APP SHELL -->


<script src="<?= asset('assets/js/lucide.min.js') ?>"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>

</body>
</html>