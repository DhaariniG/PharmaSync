<?php
/**
 * Inventory Manager routes.  Owner: <put your name here>.
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

    // Add your pages below, for example:
    // 'GET  /orders'        => ['InventoryManagerOrderController', 'index'],
    // 'GET  /orders/{id}'   => ['InventoryManagerOrderController', 'show'],
    // 'POST /orders/update' => ['InventoryManagerOrderController', 'update'],

];
