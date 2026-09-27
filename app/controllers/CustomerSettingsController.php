<?php

/**
 * Settings page. Every control on it does something real:
 *   - change password          (checks the current one first)
 *   - notification switches    (hide / show those notification types)
 *   - sign out of this device
 *   - download my data         (a JSON copy of everything we hold)
 *   - close my account         (sets it Inactive; records are kept)
 *
 * Accounts belong to the shared login, so this controller never edits
 * app/models/User.php - it only calls the public methods the login and
 * forgot-password pages already use (findById, updatePasswordByEmail,
 * update).
 */
class CustomerSettingsController extends Controller
{
    protected string $viewBase = 'customer';

    /** Same rule as sign-up (AuthenticationController: min:8). */
    private const PASSWORD_MIN = 8;

    public function index(): void
    {
        $this->requireRole('Customer');

        $user = $this->currentUser();
        $userId = (int) $user['id'];
        $account = (new User())->findById($userId) ?? [];

        $this->render('settings.index', [
            'user'       => $user,
            'account'    => $account,
            'settings'   => (new CustomerSettings())->get($userId),
            'device'     => $this->describeDevice((string) ($_SERVER['HTTP_USER_AGENT'] ?? '')),
            'openOrders' => count($this->openOrders($userId)),
        ]);
    }

    /* ------------------------------------------------------------------
     * Notifications
     * ------------------------------------------------------------------ */

    public function saveNotifications(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        // A switch that is off is simply not sent, so "not posted" = off.
        $changes = [];
        foreach (array_keys(CustomerSettings::NOTIFICATION_TYPES) as $key) {
            $changes[$key] = $this->input($key) ? 1 : 0;
        }
        (new CustomerSettings())->save((int) $this->currentUser()['id'], $changes);

        $this->flash('success', 'Notification preferences saved.');
        $this->redirect('/customer/settings#notifications');
    }

    /* ------------------------------------------------------------------
     * Password
     * ------------------------------------------------------------------ */

    public function changePassword(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $userId  = (int) $this->currentUser()['id'];
        $current = (string) $this->input('current_password', '');
        $new     = (string) $this->input('new_password', '');
        $confirm = (string) $this->input('confirm_password', '');

        $users = new User();
        $account = $users->findById($userId);

        $error = null;
        if (!$account) {
            $error = 'We could not find your account. Please sign in again.';
        } elseif ($current === '' || !password_verify($current, $account['password_hash'] ?? '')) {
            $error = 'Your current password is not correct.';
        } elseif (strlen($new) < self::PASSWORD_MIN) {
            $error = 'Your new password must be at least ' . self::PASSWORD_MIN . ' characters.';
        } elseif ($new !== $confirm) {
            $error = 'The new password and its confirmation do not match.';
        } elseif (password_verify($new, $account['password_hash'])) {
            $error = 'Your new password must be different from the current one.';
        }

        if ($error !== null) {
            $this->flash('error', $error);
            $this->redirect('/customer/settings#security');
            return;
        }

        try {
            // The same method the forgot-password page uses.
            $users->updatePasswordByEmail($account['email'], password_hash($new, PASSWORD_BCRYPT));
        } catch (Throwable $e) {
            $this->flash('error', 'Your password could not be changed right now. Please try again later.');
            $this->redirect('/customer/settings#security');
            return;
        }

        (new CustomerSettings())->save($userId, ['password_changed_at' => date('Y-m-d H:i:s')]);
        session_regenerate_id(true);   // a new session id after a credential change

        $this->flash('success', 'Your password has been changed.');
        $this->redirect('/customer/settings#security');
    }

    /* ------------------------------------------------------------------
     * Download my data
     * ------------------------------------------------------------------ */

    /** Everything the customer module holds about this account, as JSON. */
    public function downloadData(): void
    {
        $this->requireRole('Customer');

        $userId  = (int) $this->currentUser()['id'];
        $account = (new User())->findById($userId) ?? [];

        // Never include the password hash or where files sit on the server.
        $prescriptions = array_map(function ($p) {
            return array_filter($p, fn($key) => !str_contains($key, 'path') && $key !== 'user_id', ARRAY_FILTER_USE_KEY);
        }, (new Prescription())->forUser($userId));

        $data = [
            'exported_at' => date('c'),
            'account' => [
                'name'         => $account['full_name'] ?? null,
                'email'        => $account['email'] ?? null,
                'phone'        => $account['phone'] ?? null,
                'address'      => $account['address'] ?? null,
                'member_since' => $account['created_at'] ?? null,
                'last_sign_in' => $account['last_login'] ?? null,
            ],
            'family_members' => (new FamilyMember())->forUser($userId),
            'addresses'      => (new Address())->forUser($userId),
            'orders'         => (new Order())->forUser($userId),
            'prescriptions'  => $prescriptions,
            'settings'       => (new CustomerSettings())->get($userId),
        ];

        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="pharmasync-my-data-' . date('Y-m-d') . '.json"');
        header('Cache-Control: no-store');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /* ------------------------------------------------------------------
     * Close account
     * ------------------------------------------------------------------ */

    /**
     * Closing sets the account to Inactive, which the shared login already
     * refuses. Nothing is deleted: order and prescription records must be
     * kept, and the pharmacy can reopen the account if asked.
     */
    public function closeAccount(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $userId = (int) $this->currentUser()['id'];
        $users = new User();
        $account = $users->findById($userId);

        $error = null;
        if (!$account) {
            $error = 'We could not find your account. Please sign in again.';
        } elseif (strtoupper(trim((string) $this->input('confirm_text', ''))) !== 'CLOSE') {
            $error = 'Please type CLOSE to confirm.';
        } elseif (!password_verify((string) $this->input('password', ''), $account['password_hash'] ?? '')) {
            $error = 'Your password is not correct, so the account was not closed.';
        } elseif ($open = $this->openOrders($userId)) {
            $error = 'You have ' . count($open) . ' order(s) still in progress. Wait until they are delivered, or cancel them, before closing your account.';
        }

        if ($error !== null) {
            $this->flash('error', $error);
            $this->redirect('/customer/settings#privacy');
            return;
        }

        try {
            $users->update($userId, [
                'full_name' => $account['full_name'],
                'email'     => $account['email'],
                'phone'     => $account['phone'],
                'address'   => $account['address'] ?? null,
                'role'      => $account['role'],
                'status'    => 'Inactive',
            ]);
        } catch (Throwable $e) {
            $this->flash('error', 'Your account could not be closed right now. Please try again later.');
            $this->redirect('/customer/settings#privacy');
            return;
        }

        // Sign out, the same way the shared logout does.
        Session::logout();
        session_id(session_create_id());
        session_start();
        Session::flash('success', 'Your account has been closed. Your order and prescription records are kept as the law requires. Contact the pharmacy if you want to reopen it.');
        $this->redirect('/' . AUTH_SLUG . '/login');
    }

    /* ------------------------------------------------------------------ */

    /** Orders the pharmacy is still working on. */
    private function openOrders(int $userId): array
    {
        return array_values(array_filter(
            (new Order())->forUser($userId),
            fn($o) => in_array($o['status'] ?? '', ['pending', 'processing'], true)
        ));
    }

    /** "Chrome on Windows" from the browser's user agent. */
    private function describeDevice(string $ua): string
    {
        $browsers = ['Edg/' => 'Edge', 'OPR/' => 'Opera', 'Firefox/' => 'Firefox', 'Chrome/' => 'Chrome', 'Safari/' => 'Safari'];
        $systems  = ['Windows' => 'Windows', 'Android' => 'Android', 'iPhone' => 'iPhone', 'iPad' => 'iPad', 'Mac OS X' => 'macOS', 'Linux' => 'Linux'];

        $browser = 'A browser';
        foreach ($browsers as $needle => $name) {
            if (str_contains($ua, $needle)) {
                $browser = $name;
                break;
            }
        }
        $system = null;
        foreach ($systems as $needle => $name) {
            if (str_contains($ua, $needle)) {
                $system = $name;
                break;
            }
        }
        return $system ? $browser . ' on ' . $system : $browser;
    }
}
