<?php
/**
 * Pharmacist routes.  Owner: <put your name here>.
 *
 * Paths are written WITHOUT the /pharmacist segment - config/routes.php adds
 * it. So 'GET /dashboard' below is reachable at /pharmacist/dashboard.
 *
 * Rules:
 *   - Controllers are FLAT in app/controllers/ and named
 *     Pharmacist<Feature>Controller.php
 *   - Views go in app/views/pharmacist/<feature>/<page>.php
 *   - Stylesheets go in public/assets/css/Pharmacist/
 *   - Longer paths first: the first matching route wins.
 */
return [

    'GET  /'          => ['PharmacistDashboardController', 'index'],
    'GET  /dashboard' => ['PharmacistDashboardController', 'index'],

    // Add your pages below, for example:
    // 'GET  /orders'        => ['PharmacistOrderController', 'index'],
    // 'GET  /orders/{id}'   => ['PharmacistOrderController', 'show'],
    // 'POST /orders/update' => ['PharmacistOrderController', 'update'],

];
