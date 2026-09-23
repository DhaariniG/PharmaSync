<?php
/**
 * Global helper functions. Loaded by config/config.php, so they are available
 * in every controller, model and view.
 *
 * Everything here is wrapped in function_exists() on purpose: if two people
 * ever load this file twice, PHP must not die with "cannot redeclare".
 */

/* ==========================================================================
   Output
   ========================================================================== */

if (!function_exists('e')) {
    /**
     * Escape a value for safe HTML output. Use it on EVERY value that came
     * from a user or the database before printing it.
     *
     *   <td><?= e($medicine['name']) ?></td>
     *
     * Forgetting this is how XSS happens.
     */
    function e($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('money')) {
    /** Format money the same way everywhere. */
    function money($amount): string
    {
        return CURRENCY . ' ' . number_format((float) $amount, 2);
    }
}

if (!function_exists('dt')) {
    /** Format a datetime the same way everywhere. */
    function dt(?string $datetime, string $format = 'd M Y, g:i a'): string
    {
        if (empty($datetime)) {
            return '-';
        }
        return date($format, strtotime($datetime));
    }
}

if (!function_exists('old')) {
    /** Re-fill a form field after a failed validation. */
    function old(string $key, array $oldData, string $default = ''): string
    {
        return e($oldData[$key] ?? $default);
    }
}

/* ==========================================================================
   URLs
   ========================================================================== */

if (!function_exists('url')) {
    /**
     * Full URL for an app path. Always include your role segment.
     *
     *   <a href="<?= url('/customer/cart') ?>">Cart</a>
     *   <a href="<?= url('/pharmacist/queue') ?>">Queue</a>
     *
     * Never hardcode the folder name - links break the moment someone
     * clones the project into a differently named folder.
     */
    function url(string $path = ''): string
    {
        return BASE_URL . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    /** URL for a file under public/, e.g. asset('assets/css/main.css'). */
    function asset(string $path): string
    {
        return BASE_URL . '/' . ltrim($path, '/');
    }
}

/* ==========================================================================
   CSRF
   ==========================================================================
   Every POST form needs <?= csrf_field() ?> and every POST handler needs
   $this->verifyCsrf() on its first line. The field name is _csrf.
*/

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
    }
}

/* ==========================================================================
   Role naming
   ==========================================================================
   Three spellings exist and each has one job. Never type them by hand.

     Database ENUM   'Inventory_Manager'   users.role
     URL segment     'InventoryManager'    /InventoryManager/dashboard
     Asset folder    'InventoryManager'    public/assets/css/<folder>/

   See the ROLES block in config/config.php.
*/

if (!function_exists('role_slug')) {
    /** Database role -> URL segment. */
    function role_slug(string $role): string
    {
        return ROLE_SLUGS[$role] ?? '';
    }
}

if (!function_exists('slug_role')) {
    /** URL segment -> database role. Case-insensitive. '' if unknown. */
    function slug_role(string $slug): string
    {
        return ROLE_SLUG_LOOKUP[strtolower($slug)] ?? '';
    }
}

if (!function_exists('role_view_dir')) {
    /** Database role -> its folder under app/views/ */
    function role_view_dir(string $role): string
    {
        return ROLE_VIEW_DIRS[$role] ?? '';
    }
}

if (!function_exists('role_asset_dir')) {
    /** Database role -> its folder under public/assets/css/ */
    function role_asset_dir(string $role): string
    {
        return ROLE_ASSET_DIRS[$role] ?? '';
    }
}

if (!function_exists('role_css')) {
    /**
     * URL of a stylesheet inside a role's own asset folder.
     *
     *   <link rel="stylesheet" href="<?= role_css('Customer', 'style.css') ?>">
     */
    function role_css(string $role, string $file): string
    {
        $dir = role_asset_dir($role);
        return asset('assets/css/' . ($dir === '' ? '' : $dir . '/') . $file);
    }
}

/* ==========================================================================
   Icons
   ========================================================================== */

if (!function_exists('icon')) {
    /**
     * Inline a Lucide SVG icon from public/assets/icons/.
     *
     * The SVGs are plain files in this repo - no icon font, no JavaScript,
     * no CDN. They use stroke="currentColor" so they take the surrounding
     * text colour, and they are sized in em.
     *
     * Each file is read once per request and kept in memory, because the
     * same icon is usually used several times on a page. An unknown name
     * renders nothing rather than a broken box.
     */
    function icon(string $name, string $classes = '', string $style = ''): string
    {
        static $cache = [];

        $name = preg_replace('/[^a-z0-9-]/', '', strtolower($name));

        if (!array_key_exists($name, $cache)) {
            $file = PUBLIC_PATH . '/assets/icons/' . $name . '.svg';
            $cache[$name] = is_file($file) ? trim(file_get_contents($file)) : '';
        }

        if ($cache[$name] === '') {
            return '';
        }

        $class = 'lucide lucide-' . $name;
        if ($classes !== '') {
            $class .= ' ' . $classes;
        }

        $attrs = ' class="' . e($class) . '" aria-hidden="true"';
        if ($style !== '') {
            $attrs .= ' style="' . e($style) . '"';
        }

        return preg_replace('/^<svg class="[^"]*"/', '<svg' . $attrs, $cache[$name], 1);
    }
}

/* ==========================================================================
   Medicines (shared - pharmacist and inventory show the same images)
   ========================================================================== */

if (!function_exists('medicine_image')) {
    /** Main image URL for a medicine, or the placeholder if none is set. */
    function medicine_image(?array $medicine): string
    {
        $filename = $medicine['image'] ?? '';
        if ($filename === '') {
            return asset('assets/images/medicines/_placeholder.svg');
        }
        return asset('assets/images/medicines/' . rawurlencode($filename));
    }
}

if (!function_exists('medicine_gallery')) {
    /** All image URLs for a medicine: main image first, then extra angles. */
    function medicine_gallery(?array $medicine): array
    {
        $urls = [medicine_image($medicine)];

        foreach ($medicine['images'] ?? [] as $filename) {
            $filename = trim((string) $filename);
            if ($filename !== '') {
                $urls[] = asset('assets/images/medicines/' . rawurlencode($filename));
            }
        }

        return array_values(array_unique($urls));
    }
}

/* ==========================================================================
   Store pickup
   ========================================================================== */

if (!function_exists('pickup_slots')) {
    /**
     * Pickup slots for the next few days. Sundays are shorter (the store
     * closes at 5 PM) and same-day slots less than the prep time away are
     * skipped. Each slot: ['value' => 'Y-m-d H:i', 'label' => '...'].
     */
    function pickup_slots(int $days = 7): array
    {
        $slots   = [];
        $prepEnd = time() + STORE_PICKUP_PREP_HOURS * 3600;

        for ($i = 0; $i < $days; $i++) {
            $day   = strtotime("+$i day");
            $date  = date('Y-m-d', $day);
            $hours = date('w', $day) == 0 ? STORE_PICKUP_HOURS_SUN : STORE_PICKUP_HOURS;

            foreach ($hours as $h) {
                $start = strtotime("$date " . sprintf('%02d:00', $h));
                if ($start < $prepEnd) {
                    continue;
                }
                $slots[] = [
                    'value' => date('Y-m-d H:i', $start),
                    'label' => date('D, j M', $start) . ' - ' . date('g:i A', $start),
                ];
            }
        }

        return $slots;
    }
}
