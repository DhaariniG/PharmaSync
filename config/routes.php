<?php
// Route table: 'METHOD /path' => [Controller, method]. {id} is a parameter.
return [

    // Login, registration and logout are NOT part of this module. They belong
    // to the shared PharmaSync login, which puts the signed-in customer in
    // $_SESSION['user'] before any of these routes are reached. Every page
    // here assumes a signed-in customer.

    // --- Dashboard ---
    'GET  /'                    => ['CustomerDashboardController', 'index'],
    'GET  /dashboard'           => ['CustomerDashboardController', 'index'],

    // --- Catalog / Search ---
    'GET  /catalog'              => ['CustomerCatalogController', 'index'],
    'GET  /search'               => ['CustomerCatalogController', 'search'],
    'GET  /alternate/{id}'       => ['CustomerCatalogController', 'alternate'],

    // --- Product ---
    'GET  /product/{id}'         => ['CustomerProductController', 'show'],

    // --- Cart ---
    'GET  /cart'                  => ['CustomerCartController', 'index'],
    'POST /cart/add'              => ['CustomerCartController', 'add'],
    'POST /cart/update'           => ['CustomerCartController', 'update'],
    'POST /cart/remove'           => ['CustomerCartController', 'remove'],
    'POST /cart/save-for-later'   => ['CustomerCartController', 'saveForLater'],
    'POST /cart/move-to-cart'     => ['CustomerCartController', 'moveToCart'],
    'POST /cart/apply-promo'      => ['CustomerCartController', 'applyPromo'],

    // --- Checkout ---
    'GET  /checkout'              => ['CustomerCheckoutController', 'index'],
    'POST /checkout/place-order'  => ['CustomerCheckoutController', 'placeOrder'],

    // --- Orders ---
    'GET  /order/confirmation/{id}' => ['CustomerOrderController', 'confirmation'],
    'GET  /orders'                  => ['CustomerOrderController', 'myOrders'],
    'GET  /orders/{id}'             => ['CustomerOrderController', 'show'],
    'POST /orders/reorder/{id}'     => ['CustomerOrderController', 'reorder'],

    // --- Prescriptions ---
    'GET  /prescription/upload'    => ['CustomerPrescriptionController', 'uploadForm'],
    'POST /prescription/upload'    => ['CustomerPrescriptionController', 'upload'],
    'GET  /prescription/status/{id}' => ['CustomerPrescriptionController', 'status'],
    'GET  /prescription/file/{id}'   => ['CustomerPrescriptionController', 'file'],
    'POST /prescription/approve-alternative/{id}' => ['CustomerPrescriptionController', 'approveAlternative'],
    'POST /prescription/continue-waiting/{id}'    => ['CustomerPrescriptionController', 'continueWaiting'],
    'POST /prescription/confirm/{id}'             => ['CustomerPrescriptionController', 'confirmPrepared'],

    // --- Profile ---
    'GET  /profile'                => ['CustomerProfileController', 'show'],
    'POST /profile/update'         => ['CustomerProfileController', 'update'],
    'POST /profile/add-allergy'    => ['CustomerProfileController', 'addAllergy'],
    'POST /profile/add-condition'  => ['CustomerProfileController', 'addCondition'],
    'POST /profile/add-member'     => ['CustomerProfileController', 'addFamilyMember'],

    // --- Settings ---
    'GET  /settings'               => ['CustomerSettingsController', 'index'],

    // --- Notifications ---
    'GET  /notifications'                 => ['CustomerNotificationController', 'index'],
    'POST /notifications/mark-all-read'   => ['CustomerNotificationController', 'markAllRead'],
    'POST /notifications/mark-all-unread' => ['CustomerNotificationController', 'markAllUnread'],
    'POST /notifications/toggle-read'     => ['CustomerNotificationController', 'toggleRead'],
    'POST /notifications/clear'           => ['CustomerNotificationController', 'clearAll'],

];
