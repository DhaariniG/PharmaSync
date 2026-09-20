<?php
/**
 * Global helper functions, available in every controller and view.
 */

/**
 * Escape output. Use this on EVERY value that came from a user or the
 * database before printing it.
 *
 *   <td><?= e($medicine->name) ?></td>
 *
 * Forgetting this is how XSS happens.
 */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Build a full URL from an app-relative path.
 *
 *   <a href="<?= url('/customer/cart') ?>">Cart</a>
 *
 * Never hardcode paths - links break the moment the folder name changes.
 */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/** URL for a file in public/, e.g. asset('css/main.css') */
function asset(string $path): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/** The CSRF token for this session, created on first use. */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Hidden CSRF input. Put this inside every POST form.
 *
 *   <form method="post" action="<?= url('/customer/cart/add') ?>">
 *       <?= csrf_field() ?>
 *       ...
 *   </form>
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/** Format money the same way everywhere. */
function money($amount): string
{
    return 'Rs. ' . number_format((float) $amount, 2);
}

/** Format a datetime the same way everywhere. */
function dt(?string $datetime, string $format = 'd M Y, g:i a'): string
{
    if (empty($datetime)) {
        return '-';
    }
    return date($format, strtotime($datetime));
}

/** Re-fill a form field after a failed validation. */
function old(string $key, array $oldData, string $default = ''): string
{
    return e($oldData[$key] ?? $default);
}

/** Turn a database role into its URL slug. */
function role_slug(string $role): string
{
    return ROLE_SLUGS[$role] ?? '';
}
