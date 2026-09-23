<?php
/**
 * Inventory Manager routes.  Owner: Zaidh.
 *
 * Paths are written WITHOUT the /InventoryManager segment - config/routes.php adds
 * it. So 'GET /dashboard' below is reachable at /InventoryManager/dashboard.
 *
 * Rules:
 *   - Controllers are FLAT in app/controllers/ and named
 *     InventoryManager<Feature>Controller.php
 *   - Views go in app/views/InventoryManager/<feature>/<page>.php
 *   - Stylesheets go in public/assets/css/InventoryManager/
 *   - Longer paths first: the first matching route wins.
 */
return [

    'GET  /'          => ['InventoryManagerDashboardController', 'index'],
    'GET  /dashboard' => ['InventoryManagerDashboardController', 'index'],

    // Stock & batches ('/batches/create' is listed before '/batches')
    'GET  /batches/create' => ['InventoryManagerBatchController', 'create'],
    'GET  /batches'        => ['InventoryManagerBatchController', 'index'],

    // Suppliers
    'GET  /suppliers/create' => ['InventoryManagerSupplierController', 'create'],
    'GET  /suppliers'        => ['InventoryManagerSupplierController', 'index'],

    // Purchase orders
    'GET  /purchase-orders/create' => ['InventoryManagerPurchaseOrderController', 'create'],
    'GET  /purchase-orders'        => ['InventoryManagerPurchaseOrderController', 'index'],

    // Alerts
    'GET  /low-stock'     => ['InventoryManagerAlertController', 'lowStock'],
    'GET  /expiry-alerts' => ['InventoryManagerAlertController', 'expiry'],

    // Activity
    'GET  /stock-movements' => ['InventoryManagerStockMovementController', 'index'],
    'GET  /reports'         => ['InventoryManagerReportController', 'index'],

    // Own profile
    'GET  /profile' => ['InventoryManagerProfileController', 'index'],

    // Notifications
    'GET  /notifications' => ['InventoryManagerNotificationController', 'index'],

    // Medicines (real database CRUD). Most specific paths first:
    // '/medicines/create' must come before '/medicines/{id}/...'.
    'GET  /medicines/create'        => ['InventoryManagerMedicineController', 'create'],
    'GET  /medicines/{id}/edit'     => ['InventoryManagerMedicineController', 'edit'],
    'POST /medicines/{id}/update'   => ['InventoryManagerMedicineController', 'update'],
    'POST /medicines/{id}/delete'   => ['InventoryManagerMedicineController', 'delete'],
    'POST /medicines'               => ['InventoryManagerMedicineController', 'store'],
    'GET  /medicines'               => ['InventoryManagerMedicineController', 'index'],

];
