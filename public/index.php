<?php
// Front controller — every request comes through here (see .htaccess).

require __DIR__ . '/../config/config.php';

// Autoloader (no Composer)
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../app/core/' . $class . '.php',
        __DIR__ . '/../app/controllers/' . $class . '.php',
        __DIR__ . '/../app/models/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

// Drop mock data left over from an older seed shape (see SEED_VERSION in
// config.php). Each model re-seeds itself on the next call. The signed-in
// user and the CSRF token are deliberately left alone, so this doesn't sign
// anyone out or break a form that's already open.
if (($_SESSION['seed_version'] ?? null) !== SEED_VERSION) {
    foreach (['orders', 'prescriptions', 'family_members', 'addresses',
              'notifications', 'cart', 'saved_for_later', 'promo_code',
              'recently_viewed', 'reorder_prescription_id'] as $staleKey) {
        unset($_SESSION[$staleKey]);
    }
    $_SESSION['seed_version'] = SEED_VERSION;
}

// The shared PharmaSync login is what normally fills $_SESSION['user'].
// Until it's merged, stand in the demo customer so this module runs on its
// own. Delete this block (and the flag in config.php) after integration.
if (AUTO_SIGN_IN_DEMO_CUSTOMER && empty($_SESSION['user'])) {
    $_SESSION['user'] = (new Customer())->demoUser();
}

$routes = require __DIR__ . '/../config/routes.php';

$router = new Router($routes);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
