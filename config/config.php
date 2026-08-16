<?php
// App configuration. No database yet, so DB_ENABLED is false and the models
// return sample data from the session. When the DB is ready, fill in the
// DB_* values, set DB_ENABLED to true and swap the sample data in each model.

date_default_timezone_set('Asia/Colombo');

define('BASE_URL', '/PharmaSync/public');

// Authentication is not part of this module. The shared PharmaSync login
// (owned by the login/auth part of the project) signs the customer in and
// puts them in $_SESSION['user']. This module only ever reads that value.
//
// While the shared login isn't merged yet, this flag signs in the demo
// customer automatically so the customer pages can be opened on their own.
// Set it to false once the shared login is wired up.
define('AUTO_SIGN_IN_DEMO_CUSTOMER', true);

// All the mock data (orders, prescriptions, family members, addresses,
// notifications, cart) lives in the session until the DB is connected, and
// each model only seeds when its session key is empty. That means an old
// browser session keeps serving data in the *old shape* after the seed
// changes — fields added later come out blank or fall back to placeholders.
//
// Bump this whenever you change the shape of any seed. Sessions still on an
// older number are re-seeded on their next request. Once DB_ENABLED is true
// and the models read from MySQL, this can go.
define('SEED_VERSION', 3);

// Database (not connected yet)
define('DB_HOST', 'localhost');
define('DB_NAME', 'pharmasync');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_ENABLED', false);

// Pricing
define('DELIVERY_FEE', 150.00);
define('TAX_RATE', 0.02);

// Store pickup (single branch)
define('STORE_NAME',    'PharmaSync Pharmacy — Colombo');
define('STORE_ADDRESS', '12 Galle Road, Colombo 03');
define('STORE_PHONE',   '+94 11 234 5678');
define('STORE_HOURS',   'Mon–Sat 8:00 AM – 8:00 PM · Sun 9:00 AM – 5:00 PM');
define('STORE_PICKUP_HOURS',     [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19]); // Mon–Sat
define('STORE_PICKUP_HOURS_SUN', [9, 10, 11, 12, 13, 14, 15, 16]);             // Sunday (closes 5 PM)
define('STORE_PICKUP_PREP_HOURS', 2);

// Mail (PHPMailer)
define('MAIL_HOST', 'smtp.example.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'no-reply@pharmasync.test');
define('MAIL_PASSWORD', '');
define('MAIL_FROM', 'no-reply@pharmasync.test');
define('MAIL_FROM_NAME', 'PharmaSync');

// Prescription uploads are kept outside public/ and served through a
// controller that checks ownership.
define('PRESCRIPTION_UPLOAD_DIR', __DIR__ . '/../storage/prescriptions/');
define('PRESCRIPTION_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('PRESCRIPTION_ALLOWED_TYPES', ['image/jpeg', 'image/png', 'application/pdf']);
define('PRESCRIPTION_MIME_EXT', [
    'image/jpeg'      => 'jpg',
    'image/png'       => 'png',
    'application/pdf' => 'pdf',
]);

if (session_status() === PHP_SESSION_NONE) {
    // Keep the session cookie away from JavaScript and off cross-site
    // requests. 'secure' switches itself on when the site is served over HTTPS.
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']),
        'path'     => '/',
    ]);
    session_start();
}

require_once __DIR__ . '/helpers.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
