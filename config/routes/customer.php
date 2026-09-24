<?php
/**
 * Customer routes.  Owner: Mithun.
 *
 * Paths are written WITHOUT the /customer segment - config/routes.php adds
 * it. So 'GET /dashboard' below is reachable at /customer/dashboard.
 *
 * Rules:
 *   - Controllers are FLAT in app/controllers/ and named
 *     Customer<Feature>Controller.php
 *   - Views go in app/views/customer/<feature>/<page>.php
 *   - Stylesheets go in public/assets/css/Customer/
 *   - Longer paths first: the first matching route wins.
 */
return [

    'GET  /'          => ['CustomerDashboardController', 'index'],
    'GET  /dashboard' => ['CustomerDashboardController', 'index'],

    // Add your pages below, for example:
    // 'GET  /catalog'       => ['CustomerCatalogController', 'index'],
    // 'GET  /product/{id}'  => ['CustomerProductController', 'show'],
    // 'POST /cart/add'      => ['CustomerCartController', 'add'],

];
