<?php
/**
 * PharmaSync - front controller.
 *
 * Every request in the whole system enters here. Nothing else in the
 * project is meant to be reachable directly from a browser.
 */

require_once dirname(__DIR__) . '/config/config.php';

/* --------------------------------------------------------------------------
 * Autoloader. No Composer, so classes are found by filename:
 *   Cart                     -> app/models/Cart.php
 *   CustomerCartController   -> app/controllers/CustomerCartController.php
 *   Router                   -> app/core/Router.php
 * Controllers and models are FLAT - the role is part of the file name.
 * -------------------------------------------------------------------------- */
spl_autoload_register(function (string $class): void {
    if (!preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $class)) {
        return;                       // never let a class name walk the disk
    }

    foreach (['/core/', '/controllers/', '/models/'] as $dir) {
        $file = APP_PATH . $dir . $class . '.php';

        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

/* --------------------------------------------------------------------------
 * Drop sample data left over from an older seed shape. See SEED_VERSION in
 * config/config.php. Each model re-seeds itself on its next call. The
 * signed-in user and the CSRF token are deliberately left alone, so this
 * never signs anyone out or breaks a form that is already open.
 * -------------------------------------------------------------------------- */
if (($_SESSION['seed_version'] ?? null) !== SEED_VERSION) {
    foreach (SEEDED_SESSION_KEYS as $staleKey) {
        unset($_SESSION[$staleKey]);
    }
    $_SESSION['seed_version'] = SEED_VERSION;
}

/* --------------------------------------------------------------------------
 * TEMPORARY - delete this block once authentication is merged.
 *
 * The shared login is what normally fills $_SESSION['user']. Until it is
 * merged, this signs in a demo user so any module can be opened on its own.
 * Change AUTO_SIGN_IN_DEMO_ROLE in config.php while working on your role.
 * -------------------------------------------------------------------------- */
if (AUTO_SIGN_IN_DEMO_USER && !Session::isLoggedIn()) {
    Session::login(AuthenticationController::demoUser(AUTO_SIGN_IN_DEMO_ROLE));
}

/* -------------------------------------------------------------------------- */

$router = new Router(require CONFIG_PATH . '/routes.php');

$router->dispatch(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    $_SERVER['REQUEST_URI'] ?? '/'
);
