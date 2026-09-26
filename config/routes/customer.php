<?php
/**
 * Customer routes.  Owner: Mithun.
 *
 * Paths here are written WITHOUT the /customer segment - config/routes.php
 * adds it. So 'GET /cart' below is reachable at /customer/cart.
 *
 * Guests (not logged in) can open the landing page, catalog, search,
 * product pages, alternatives and the cart - see CustomerGuestAccess.
 * Every other page needs a signed-in Customer and sends guests to the
 * login page, then back here.
 */
return [

    // --- Dashboard ---
    'GET  /'                    => ['CustomerDashboardController', 'index'],
    'GET  /dashboard'           => ['CustomerDashboardController', 'index'],

    // --- Public landing page (also shown at the site root for guests) ---
    'GET  /home'                => ['CustomerHomeController', 'index'],

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
    'POST /orders/cancel/{id}'      => ['CustomerOrderController', 'cancel'],
    'POST /orders/reschedule/{id}'  => ['CustomerOrderController', 'reschedule'],

    // --- Prescriptions ---
    'GET  /prescription/upload'    => ['CustomerPrescriptionController', 'uploadForm'],
    'POST /prescription/upload'    => ['CustomerPrescriptionController', 'upload'],
    'GET  /prescription/status/{id}' => ['CustomerPrescriptionController', 'status'],
    'GET  /prescription/file/{id}'   => ['CustomerPrescriptionController', 'file'],
    'POST /prescription/approve-alternative/{id}' => ['CustomerPrescriptionController', 'approveAlternative'],
    'POST /prescription/continue-waiting/{id}'    => ['CustomerPrescriptionController', 'continueWaiting'],
    'POST /prescription/confirm/{id}'             => ['CustomerPrescriptionController', 'confirmPrepared'],
    'POST /prescription/add-to-cart/{id}'         => ['CustomerPrescriptionController', 'addToCart'],

    // --- Profile ---
    'GET  /profile'                => ['CustomerProfileController', 'show'],
    'POST /profile/update'         => ['CustomerProfileController', 'update'],

    // Family members: the four CRUD operations for this module.
    // READ one, then CREATE, UPDATE, DELETE.
    'GET  /profile/member/{id}'         => ['CustomerProfileController', 'editMember'],
    'POST /profile/add-member'          => ['CustomerProfileController', 'addFamilyMember'],
    'POST /profile/update-member/{id}'  => ['CustomerProfileController', 'updateFamilyMember'],
    'POST /profile/delete-member/{id}'  => ['CustomerProfileController', 'deleteFamilyMember'],

    // Allergies and chronic conditions, recorded against one family member.
    'POST /profile/member/{id}/add-flag' => ['CustomerProfileController', 'addMemberFlag'],
    'POST /profile/delete-flag'          => ['CustomerProfileController', 'deleteMemberFlag'],

    // Shortcuts for the account holder's own health profile card. They write
    // to the 'Self' family member, so there is only one place the data lives.
    'POST /profile/add-allergy'    => ['CustomerProfileController', 'addAllergy'],
    'POST /profile/add-condition'  => ['CustomerProfileController', 'addCondition'],

    // Addresses: create, update, delete, and choosing the default.
    'POST /profile/add-address'          => ['CustomerProfileController', 'addAddress'],
    'POST /profile/update-address/{id}'  => ['CustomerProfileController', 'updateAddress'],
    'POST /profile/delete-address/{id}'  => ['CustomerProfileController', 'deleteAddress'],
    'POST /profile/default-address/{id}' => ['CustomerProfileController', 'setDefaultAddress'],

    // --- Settings ---
    'GET  /settings'               => ['CustomerSettingsController', 'index'],
    'POST /settings/notifications' => ['CustomerSettingsController', 'saveNotifications'],
    'POST /settings/password'      => ['CustomerSettingsController', 'changePassword'],
    'GET  /settings/download-data' => ['CustomerSettingsController', 'downloadData'],
    'POST /settings/close-account' => ['CustomerSettingsController', 'closeAccount'],

    // --- Notifications ---
    'GET  /notifications'                 => ['CustomerNotificationController', 'index'],
    'POST /notifications/mark-all-read'   => ['CustomerNotificationController', 'markAllRead'],
    'POST /notifications/mark-all-unread' => ['CustomerNotificationController', 'markAllUnread'],
    'POST /notifications/toggle-read'     => ['CustomerNotificationController', 'toggleRead'],
    'POST /notifications/clear'           => ['CustomerNotificationController', 'clearAll'],

];
