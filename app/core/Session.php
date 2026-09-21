<?php
/**
 * Session - the one place that knows how a signed-in user is stored.
 *
 * The signed-in user is ONE array in $_SESSION['user']:
 *
 *   [
 *     'id'    => 1,             // users.id
 *     'role'  => 'Customer',    // users.role - the DATABASE spelling
 *     'name'  => 'Nadeesha Perera',
 *     'email' => 'customer@example.com',
 *     ...    // anything else your module needs about the signed-in person
 *   ]
 *
 * 'id', 'role' and 'name' are the three keys every module may rely on.
 * Read them through the methods below rather than touching $_SESSION
 * directly, so that when login changes, only this file changes.
 *
 * The session itself is started in config/config.php.
 */
class Session
{
    /* ---------------------------------------------------------------- */
    /* Auth state                                                        */
    /* ---------------------------------------------------------------- */

    /** Call this from Authentication after the password check passes. */
    public static function login(array $user): void
    {
        session_regenerate_id(true);          // prevents session fixation
        $_SESSION['user'] = $user;
    }

    /** Clear everything and drop the cookie. */
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
        return !empty($_SESSION['user']);
    }

    /** The whole signed-in user array, or null. */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function id(): ?int
    {
        $id = $_SESSION['user']['id'] ?? null;
        return $id === null ? null : (int) $id;
    }

    /** Database ENUM value, e.g. 'Inventory_Manager'. */
    public static function role(): ?string
    {
        return $_SESSION['user']['role'] ?? null;
    }

    /** URL segment for the current role, e.g. 'InventoryManager'. */
    public static function roleSlug(): ?string
    {
        $role = self::role();
        return $role ? (ROLE_SLUGS[$role] ?? null) : null;
    }

    public static function name(): ?string
    {
        return $_SESSION['user']['name'] ?? null;
    }

    /** Update one field on the signed-in user (after a profile edit). */
    public static function set(string $key, $value): void
    {
        if (isset($_SESSION['user'])) {
            $_SESSION['user'][$key] = $value;
        }
    }

    /* ---------------------------------------------------------------- */
    /* Flash messages                                                    */
    /* ---------------------------------------------------------------- */
    /*
     * $_SESSION['flash'] is ['success' => '...', 'error' => '...'].
     * The shared layout prints and clears them, so a message survives
     * exactly one redirect.
     */

    public static function flash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    /** Read and clear every pending message. */
    public static function takeFlash(): array
    {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }

    /* ---------------------------------------------------------------- */
    /* Old form input                                                    */
    /* ---------------------------------------------------------------- */

    /** Keep submitted values so a failed form can be re-filled. */
    public static function keepOld(array $data): void
    {
        unset($data['_csrf'], $data['password'], $data['password_confirm']);
        $_SESSION['old'] = $data;
    }

    public static function takeOld(): array
    {
        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);
        return $old;
    }
}
