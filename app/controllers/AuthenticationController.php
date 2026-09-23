<?php
/**
 * AuthenticationController - login, registration, forgot password, logout.
 *
 * Login and registration logic is ported from the group's Auth controller
 * (feature/Pharmacist branch) and fitted to this scaffold: Router instead of
 * App, _csrf on every POST, rows as arrays, and the one-array session user
 * that every module already reads.
 *
 * The rest of the project depends on exactly two things from this file:
 *   1. Session::login($user) receives an array with at least id, role, name.
 *   2. Session::logout() is called on the way out.
 */
class AuthenticationController extends Controller
{
    protected string $viewBase = 'authentication';

    /**
     * A real bcrypt hash of a random string. Checked when the email is not
     * found, so a wrong email takes as long as a wrong password and the
     * response time does not reveal which emails have accounts.
     */
    private const DUMMY_HASH = '$2y$10$MMVvXYQtL5DE.cfYikzJ4OwMQcYnbTakR8t/6wKPwNNC37HztbxIW';

    /* ==================================================================
     * Login
     * ================================================================== */

    public function loginForm(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirectToDashboard();
        }

        $this->showAuthPage('login');
    }

    public function login(): void
    {
        $this->verifyCsrf();

        $errors = $this->validate($_POST, [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($errors) {
            $this->backTo('login', reset($errors));
        }

        $userModel = new User();
        $user      = $userModel->findByEmail($this->text('email'));
        $password  = (string) ($_POST['password'] ?? '');   // never trim a password

        $valid = password_verify($password, $user['password_hash'] ?? self::DUMMY_HASH);

        if (!$user || !$valid) {
            $this->backTo('login', 'Invalid email or password.');
        }

        if (($user['status'] ?? 'Active') !== 'Active') {
            $this->backTo('login', 'Your account is suspended or inactive. Please contact the pharmacy.');
        }

        Session::login(User::toSessionUser($user));
        $userModel->recordLogin((int) $user['user_id']);

        // Send them back to the page they originally asked for, if it was
        // one of their own pages. requireAuth() stores it.
        $back = $_SESSION['redirect_after_login'] ?? null;
        unset($_SESSION['redirect_after_login']);

        if ($back && $this->isOwnRolePath($back)) {
            header('Location: ' . $back);
            exit;
        }

        $this->redirectToDashboard();
    }

    /* ==================================================================
     * Registration (customers only - staff accounts are made by Admin)
     * ================================================================== */

    public function registerForm(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirectToDashboard();
        }

        $this->showAuthPage('register');
    }

    public function register(): void
    {
        $this->verifyCsrf();

        $errors = $this->validate($_POST, [
            'full_name'        => 'required|min:3|max:150',
            'phone'            => 'required|min:10|max:20',
            'email'            => 'required|email|max:150',
            'password'         => 'required|min:8',
            'password_confirm' => 'required|match:password',
        ]);

        if (isset($errors['password_confirm']) && $this->text('password_confirm') !== '') {
            $errors['password_confirm'] = 'The two passwords do not match.';
        }

        if (!isset($errors['phone']) && !preg_match('/^\+?[0-9 ]{10,20}$/', $this->text('phone'))) {
            $errors['phone'] = 'Enter a valid phone number, e.g. 077 1234567.';
        }

        if ($errors) {
            $this->backTo('register', reset($errors));
        }

        $userModel = new User();

        if ($userModel->emailExists($this->text('email'))) {
            $this->backTo('register', 'An account with that email already exists. Try logging in.');
        }

        $newId = $userModel->registerCustomer([
            'full_name' => $this->text('full_name'),
            'email'     => $this->text('email'),
            'phone'     => $this->text('phone'),
            'password'  => (string) $_POST['password'],
        ]);

        if (!$newId) {
            $this->backTo('register', 'Something went wrong while creating your account. Please try again.');
        }

        Session::flash('success', 'Account created. You can now log in.');
        Session::keepOld(['email' => $this->text('email')]);
        $this->redirect('/' . AUTH_SLUG . '/login');
    }

   /* ==================================================================
     * Forgot & Reset Password
     * ================================================================== */

    public function forgotForm(): void
    {
        $this->renderBare('forgot-password', [
            'flash' => Session::takeFlash(),
            'old'   => Session::takeOld(),
        ]);
    }

    public function forgot(): void
    {
        $this->verifyCsrf();

        $errors = $this->validate($_POST, ['email' => 'required|email']);
        if ($errors) {
            Session::flash('error', reset($errors));
            Session::keepOld($_POST);
            $this->redirect('/' . AUTH_SLUG . '/forgot-password');
        }

        $email     = $this->text('email');
        $userModel = new User();
        $user      = $userModel->findByEmail($email);

        if ($user) {
            $token     = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Store reset token
            $userModel->createPasswordResetToken($email, $token, $expiresAt);

            // Build full URL
            $resetUrl = url('/' . AUTH_SLUG . '/reset-password?token=' . $token);

            // Send Email using Mailer
            Mailer::sendPasswordReset(
                $email, 
                $user['full_name'] ?? $user['name'] ?? 'User', 
                $resetUrl
            );
        }

        Session::flash('success', 'If an account exists for that email, a password reset link has been sent.');
        $this->redirect('/' . AUTH_SLUG . '/login');
    }

    public function resetForm(): void
    {
        $token = trim($_GET['token'] ?? '');

        if (!$token) {
            Session::flash('error', 'Invalid password reset link.');
            $this->redirect('/' . AUTH_SLUG . '/login');
        }

        $this->renderBare('reset-password', [
            'token' => $token,
            'flash' => Session::takeFlash(),
            'old'   => Session::takeOld(),
        ]);
    }

    public function resetPassword(): void
    {
        $this->verifyCsrf();

        $errors = $this->validate($_POST, [
            'token'            => 'required',
            'password'         => 'required|min:8',
            'password_confirm' => 'required|match:password',
        ]);

        $token = $this->text('token');

        if ($errors) {
            Session::flash('error', reset($errors));
            $this->redirect('/' . AUTH_SLUG . '/reset-password?token=' . urlencode($token));
        }

        $userModel = new User();
        $reset     = $userModel->findPasswordResetToken($token);

        if (!$reset) {
            Session::flash('error', 'This password reset link is invalid or has expired.');
            $this->redirect('/' . AUTH_SLUG . '/forgot-password');
        }

        $newPasswordHash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $userModel->updatePasswordByEmail($reset['email'], $newPasswordHash);

        Session::flash('success', 'Password updated successfully! You can now log in.');
        $this->redirect('/' . AUTH_SLUG . '/login');
    }

    /* ==================================================================
     * Logout
     * ================================================================== */

    public function logout(): void
    {
        $this->verifyCsrf();

        Session::logout();

        // Fresh, empty session just to carry the "signed out" message.
        session_id(session_create_id());
        session_start();
        Session::flash('success', 'You have been signed out.');

        $this->redirect('/' . AUTH_SLUG . '/login');
    }

    /* ==================================================================
     * Helpers
     * ================================================================== */

    /** Render the login/register page with the right tab open. */
    private function showAuthPage(string $mode): void
    {
        $this->renderBare('login', [
            'mode'  => $mode,
            'flash' => Session::takeFlash(),
            'old'   => Session::takeOld(),
        ]);
    }

    /** Flash an error, keep what they typed (minus passwords) and go back. */
    private function backTo(string $mode, string $message): void
    {
        Session::flash('error', $message);
        Session::keepOld($_POST);
        $this->redirect('/' . AUTH_SLUG . '/' . $mode);
    }

    /** True if $path is inside the signed-in user's own role area. */
    private function isOwnRolePath(string $path): bool
    {
        $slug = Session::roleSlug();
        if (!$slug || !str_starts_with($path, '/') || str_starts_with($path, '//')) {
            return false;
        }

        $prefix = parse_url(url('/' . $slug), PHP_URL_PATH);
        return stripos($path, $prefix . '/') === 0 || strcasecmp(rtrim($path, '/'), $prefix) === 0;
    }
}
