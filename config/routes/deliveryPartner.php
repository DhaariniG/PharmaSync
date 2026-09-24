<?php
/**
 * Delivery Partner routes.  Owner: <put your name here>.
 *
 * Paths are written WITHOUT the /deliveryPartner segment - config/routes.php adds
 * it. So 'GET /dashboard' below is reachable at /deliveryPartner/dashboard.
 *
 * Rules:
 *   - Controllers are FLAT in app/controllers/ and named
 *     DeliveryPartner<Feature>Controller.php
 *   - Views go in app/views/deliveryPartner/<feature>/<page>.php
 *   - Stylesheets go in public/assets/css/DeliveryPartner/
 *   - Longer paths first: the first matching route wins.
 */
return [

    'GET  /'          => ['DeliveryPartnerDashboardController', 'index'],
    'GET  /dashboard' => ['DeliveryPartnerDashboardController', 'index'],

    'GET  /{page}'    => ['DeliveryPartnerPageController', 'show'],

    // Add your pages below, for example:
    // 'GET  /orders'        => ['DeliveryPartnerOrderController', 'index'],
    // 'GET  /orders/{id}'   => ['DeliveryPartnerOrderController', 'show'],
    // 'POST /orders/update' => ['DeliveryPartnerOrderController', 'update'],

];
