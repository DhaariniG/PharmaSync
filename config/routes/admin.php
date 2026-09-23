<?php
/**
 * Admin routes.  Owner: <put your name here>.
 *
 * Paths are written WITHOUT the /admin segment - config/routes.php adds
 * it. So 'GET /dashboard' below is reachable at /admin/dashboard.
 *
 * Rules:
 *   - Controllers are FLAT in app/controllers/ and named
 *     Admin<Feature>Controller.php
 *   - Views go in app/views/admin/<feature>/<page>.php
 *   - Stylesheets go in public/assets/css/Admin/
 *   - Longer paths first: the first matching route wins.
 */
return [

    'GET  /'          => ['AdminDashboardController', 'index'],
    'GET  /dashboard' => ['AdminDashboardController', 'index'],

    // Add your pages below, for example:
    // 'GET  /orders'        => ['AdminOrderController', 'index'],
    // 'GET  /orders/{id}'   => ['AdminOrderController', 'show'],
    // 'POST /orders/update' => ['AdminOrderController', 'update'],

];
