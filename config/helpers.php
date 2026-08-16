<?php
// Small helper functions used across the views.

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
    // Hidden CSRF input for POST forms.
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="'
            . htmlspecialchars(csrf_token(), ENT_QUOTES) . '">';
    }
}

if (!function_exists('e')) {
    // Escape a value for safe HTML output.
    function e($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('medicine_image')) {
    // Main image URL for a medicine (placeholder if none set).
    function medicine_image(?array $medicine): string
    {
        $filename = $medicine['image'] ?? '';
        if ($filename === '') {
            return BASE_URL . '/assets/images/medicines/_placeholder.svg';
        }
        return BASE_URL . '/assets/images/medicines/' . rawurlencode($filename);
    }
}

if (!function_exists('medicine_gallery')) {
    // All image URLs for a medicine: main image first, then any extra angles.
    function medicine_gallery(?array $medicine): array
    {
        $urls = [medicine_image($medicine)];

        foreach ($medicine['images'] ?? [] as $filename) {
            $filename = trim((string) $filename);
            if ($filename !== '') {
                $urls[] = BASE_URL . '/assets/images/medicines/' . rawurlencode($filename);
            }
        }

        return array_values(array_unique($urls));
    }
}

if (!function_exists('icon')) {
    // Inline a Lucide SVG icon from public/assets/icons/.
    //
    // The SVGs are plain files in this repo — no icon font, no JavaScript, no
    // CDN. They use stroke="currentColor" so they take the surrounding text
    // colour, and they're sized in `em` (see .lucide in style.css) so the
    // existing size-1..size-6 and inline font-size rules still work.
    //
    // Icons are read once per request and kept in memory, because the same
    // icon is usually used several times on a page.
    function icon(string $name, string $classes = '', string $style = ''): string
    {
        static $cache = [];

        $name = preg_replace('/[^a-z0-9-]/', '', strtolower($name));

        if (!array_key_exists($name, $cache)) {
            $file = __DIR__ . '/../public/assets/icons/' . $name . '.svg';
            $cache[$name] = is_file($file) ? trim(file_get_contents($file)) : '';
        }

        if ($cache[$name] === '') {
            return ''; // unknown icon: render nothing rather than a broken box
        }

        // Always keep the `lucide` class — the em-based sizing in style.css
        // hangs off it — and append whatever the caller asked for.
        $class = 'lucide lucide-' . $name;
        if ($classes !== '') {
            $class .= ' ' . $classes;
        }

        $attrs = ' class="' . htmlspecialchars($class, ENT_QUOTES) . '" aria-hidden="true"';
        if ($style !== '') {
            $attrs .= ' style="' . htmlspecialchars($style, ENT_QUOTES) . '"';
        }

        // Swap the SVG's own class attribute for ours and mark it decorative,
        // so screen readers skip it and read the adjacent label instead.
        return preg_replace('/^<svg class="[^"]*"/', '<svg' . $attrs, $cache[$name], 1);
    }
}

if (!function_exists('pickup_slots')) {
    // Pickup slots for the next few days. Sundays are shorter (store closes at
    // 5 PM) and same-day slots less than the prep time away are skipped.
    // Each slot: ['value' => 'Y-m-d H:i', 'label' => 'Mon, 21 Jul — 11:00 AM'].
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
                    'label' => date('D, j M', $start) . ' — ' . date('g:i A', $start),
                ];
            }
        }
        return $slots;
    }
}
