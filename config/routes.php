<?php
/**
 * Route loader.
 *
 * Every role owns ONE file in config/routes/. Edit only your own file and
 * git will never make you resolve a routing conflict.
 *
 * Inside your file, write paths WITHOUT your role segment:
 *
 *     'GET  /dashboard' => ['PharmacistDashboardController', 'index'],
 *
 * This loader turns that into /pharmacist/dashboard. The prefix is what
 * keeps five dashboards, five notification pages and five settings pages
 * from overwriting each other.
 *
 * Order matters: longer, more specific paths must come before shorter ones
 * inside a file, because the first match wins.
 */

$prefixed = [];

/** Add every route in $file under $prefix. */
$load = function (string $file, string $prefix) use (&$prefixed) {
    $path = CONFIG_PATH . '/routes/' . $file . '.php';

    if (!is_file($path)) {
        return;
    }

    foreach (require $path as $pattern => $action) {
        [$method, $uri] = preg_split('/\s+/', trim($pattern), 2);

        $uri  = '/' . ltrim($uri, '/');
        $full = rtrim($prefix . ($uri === '/' ? '' : $uri), '/');

        $prefixed[strtoupper($method) . ' ' . ($full === '' ? '/' : $full)] = $action;
    }
};

/* Shared: the site root and anything that belongs to nobody in particular. */
$load('shared', '');

/* Login, registration, logout: /authentication/... */
$load('authentication', '/' . AUTH_SLUG);

/* One line per role. The prefix comes from ROLE_SLUGS, never typed by hand. */
$load('customer',          '/' . ROLE_SLUGS['Customer']);
$load('pharmacist',        '/' . ROLE_SLUGS['Pharmacist']);
$load('admin',             '/' . ROLE_SLUGS['Admin']);
$load('inventoryManager',  '/' . ROLE_SLUGS['Inventory_Manager']);
$load('deliveryPartner',   '/' . ROLE_SLUGS['Delivery_Partner']);

return $prefixed;
