<?php
/**
 * Session - the only place session keys are written or read.
 *
 * AGREED KEYS (do not invent your own):
 *   $_SESSION['user_id']  int
 *   $_SESSION['role']     string, database ENUM value e.g. 'Delivery_Partner'
 *   $_SESSION['name']     string, full_name
 */
class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    /* ---------- Auth state ---------- */

    /** Call this on successful login. */
    public static function login(int $userId, string $role, string $name): void
    {
        session_regenerate_id(true);       // prevents session fixation
        $_SESSION['user_id'] = $userId;
        $_SESSION['role']    = $role;
        $_SESSION['name']    = $name;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function userId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    /** Database ENUM value, e.g. 'Inventory_Manager'. */
    public static function role(): ?string
    {
        return $_SESSION['role'] ?? null;
    }

    /** URL slug for the current role, e.g. 'inventory'. */
    public static function roleSlug(): ?string
    {
        $role = self::role();
        return $role ? (ROLE_SLUGS[$role] ?? null) : null;
    }

    public static function name(): ?string
    {
        return $_SESSION['name'] ?? null;
    }

    /* ---------- Flash messages ---------- */
    /* Shown once on the next page load, then removed. */

    public static function flash(string $type, string $message): void
    {
        $_SESSION['flash'][$type][] = $message;
    }

    public static function takeFlash(): array
    {
        $messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $messages;
    }

    /* ---------- Form data retention ---------- */
    /* Keeps what the user typed when validation fails. */

    public static function keepOld(array $data): void
    {
        $_SESSION['old'] = $data;
    }

    public static function takeOld(): array
    {
        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);
        return $old;
    }
}
