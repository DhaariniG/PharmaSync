<?php
/** Admin routes. Paths are written without the /admin segment. */
return [
    'GET  /'                    => ['AdminDashboardController', 'index'],
    'GET  /dashboard'           => ['AdminDashboardController', 'index'],

    'GET  /accounts/create' => ['AdminAccountController', 'create'],
    'POST /accounts/create' => ['AdminAccountController', 'store'],

    'GET  /accounts/detail' => ['AdminAccountController', 'detail'],
    'POST /accounts/update' => ['AdminAccountController', 'update'],
    'POST /accounts/delete' => ['AdminAccountController', 'delete'],

    'GET  /accounts'        => ['AdminAccountController', 'index'],
    'GET  /orders/urgent'       => ['AdminOrderController', 'urgent'],
    'GET  /orders/create'       => ['AdminOrderController', 'create'],
    'GET  /orders/detail'       => ['AdminOrderController', 'detail'],
    'GET  /orders'              => ['AdminOrderController', 'index'],

    'GET  /deliveries/optimize' => ['AdminDeliveryController', 'optimize'],
    'GET  /deliveries/create'   => ['AdminDeliveryController', 'create'],
    'GET  /deliveries/detail'   => ['AdminDeliveryController', 'detail'],
    'GET  /deliveries'          => ['AdminDeliveryController', 'index'],

    // Inventory and suppliers are read-only for Admin; the Inventory
    // Manager adds and edits them and raises purchase orders.
    'GET  /inventory/detail'    => ['AdminInventoryController', 'detail'],
    'GET  /inventory/audit'     => ['AdminInventoryController', 'audit'],
    'GET  /inventory'           => ['AdminInventoryController', 'index'],

    'GET  /purchase-orders'     => ['AdminPurchaseOrderController', 'index'],

    'GET  /suppliers/detail'    => ['AdminSupplierController', 'detail'],
    'GET  /suppliers'           => ['AdminSupplierController', 'index'],

    'GET  /reports'             => ['AdminReportController', 'index'],
    'GET  /settings'            => ['AdminSettingsController', 'index'],
];
