<?php
/**
 * AuthenticationController - login, registration, logout.
 *
 * STUB. Whoever owns authentication replaces the bodies below with real
 * lookups against the `users` table in database/001_schema.sql. Nothing
 * else in the project needs to change, because every other module only
 * depends on two things:
 *
 *   1. Session::login($user) is handed an array containing at least
 *      'id', 'role' (the database spelling) and 'name'.
 *   2. Session::logout() is called on the way out.
 *
 * Password rules for whoever picks this up: store password_hash() output,
 * check it with password_verify(), never store the plain password.
 */
class AuthenticationController extends Controller
{
    protected string $viewBase = 'authentication';

    public function loginForm(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirectToDashboard();
        }

        $this->renderBare('login', ['flash' => Session::takeFlash()]);
    }

    public function login(): void
    {
        $this->verifyCsrf();

        /*
         * Replace this whole block with:
         *   $user = (new User())->findByEmail($this->text('email'));
         *   if (!$user || !password_verify($this->text('password'), $user['password_hash'])) { ... }
         */
        $role = $this->text('role', 'Customer');

        if (!isset(ROLE_SLUGS[$role])) {
            Session::flash('error', 'Unknown role.');
            $this->redirect('/' . AUTH_SLUG . '/login');
        }

        Session::login(self::demoUser($role));

        // Send them back to the page they originally asked for, if any.
        $back = $_SESSION['redirect_after_login'] ?? null;
        unset($_SESSION['redirect_after_login']);

        if ($back) {
            header('Location: ' . $back);
            exit;
        }

        $this->redirectToDashboard();
    }

    public function registerForm(): void
    {
        $this->renderBare('register', ['flash' => Session::takeFlash()]);
    }

    public function register(): void
    {
        $this->verifyCsrf();

        Session::flash('error', 'Registration is not built yet.');
        $this->redirect('/' . AUTH_SLUG . '/register');
    }

    public function logout(): void
    {
        Session::logout();

        // AUTO_SIGN_IN_DEMO_USER would sign the demo user straight back in,
        // so say so plainly rather than looking like logout is broken.
        if (AUTO_SIGN_IN_DEMO_USER) {
            echo 'Signed out. AUTO_SIGN_IN_DEMO_USER is still true in '
               . 'config/config.php, so the next page signs the demo user '
               . 'back in.';
            exit;
        }

        $this->redirect('/' . AUTH_SLUG . '/login');
    }

    /* ==================================================================
     * Demo users - delete with the rest of the stub
     * ==================================================================
     * One per role so everybody can open their own module before login
     * exists. The keys match the columns in `users` plus whatever that
     * role's pages read off the signed-in user.
     */
    public static function demoUser(string $role = 'Customer'): array
    {
        $users = [
            'Customer' => [
                'id'               => 1,
                'role'             => 'Customer',
                'name'             => 'Nadeesha Perera',
                'email'            => 'customer@example.com',
                'phone'            => '+94 77 123 4567',
                'address'          => '12 Galle Road, Colombo 03',
                'dob'              => '1990-05-14',
                'health_points'    => 2450,
                'allergies'        => ['Penicillin', 'Peanuts'],
                'conditions'       => ['Hypertension'],
                'profile_complete' => 85,
            ],
            'Pharmacist' => [
                'id'    => 2,
                'role'  => 'Pharmacist',
                'name'  => 'Ravindu Silva',
                'email' => 'pharmacist@example.com',
            ],
            'Admin' => [
                'id'    => 3,
                'role'  => 'Admin',
                'name'  => 'Ishara Fernando',
                'email' => 'admin@example.com',
            ],
            'Inventory_Manager' => [
                'id'    => 4,
                'role'  => 'Inventory_Manager',
                'name'  => 'Tharindu Jayasuriya',
                'email' => 'inventory@example.com',
            ],
            'Delivery_Partner' => [
                'id'    => 5,
                'role'  => 'Delivery_Partner',
                'name'  => 'Kasun Bandara',
                'email' => 'delivery@example.com',
            ],
        ];

        return $users[$role] ?? $users['Customer'];
    }
}
