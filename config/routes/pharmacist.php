<?php
/**
 * Pharmacist routes.  Owner: Pharmacist module owner.
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

    // Counter (physical) sales
    'GET  /sales/{id}/completed' => ['PharmacistSaleController', 'completed'],  // saleCompleted
    'GET  /sales/{id}/bill'      => ['PharmacistSaleController', 'show'],       // viewBill
    'GET  /sales/{id}/edit'      => ['PharmacistSaleController', 'edit'],       // editSale
    'POST /sales/{id}/update'    => ['PharmacistSaleController', 'update'],     // updateSale
    'POST /sales/{id}/cancel'    => ['PharmacistSaleController', 'cancel'],     // cancelSale
    'POST /sales'                => ['PharmacistSaleController', 'store'],      // addSale
    'GET  /sales'                => ['PharmacistSaleController', 'create'],     // sales

    // History (counter sales + online prescriptions)
    'GET  /history' => ['PharmacistHistoryController', 'index'],                // history

    // Prescriptions Workflow
    'GET  /prescriptions/review'  => ['PharmacistPrescriptionController', 'review'],
    'GET  /review-prescription'   => ['PharmacistPrescriptionController', 'review'], // Alias link
    'GET  /prescriptions'         => ['PharmacistPrescriptionController', 'index'],  // prescriptions queue

    // Medicines & Alternatives Workflow
    'GET  /medicines/alternatives' => ['PharmacistMedicineController', 'alternate'],
    'GET  /alternative-review'     => ['PharmacistMedicineController', 'alternate'], // Alias link
    'GET  /medicines'              => ['PharmacistMedicineController', 'index'],     // medicines list

    // Notifications
    'GET  /notifications' => ['PharmacistNotificationController', 'index'],     // notifications

    // Order screens (static mock-ups)
    'GET  /orders/confirmation' => ['PharmacistOrderController', 'confirmation'],
    'GET  /orders/details'      => ['PharmacistOrderController', 'details'],
    'GET  /orders/process'      => ['PharmacistOrderController', 'process'],

    // Settings
    'POST /settings' => ['PharmacistSettingsController', 'save'],
    'GET  /settings' => ['PharmacistSettingsController', 'index'],              // settings

];