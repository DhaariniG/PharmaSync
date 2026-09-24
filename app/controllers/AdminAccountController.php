<?php

class AdminAccountController extends Controller
{
    protected string $viewBase = 'admin';

    public function index(): void
  {
     $this->requireRole('Admin');

     $userModel = new User();
     $accounts = $userModel->all();

     $this->render('accounts/index', [
         'user'     => $this->currentUser(),
         'accounts' => $accounts,
    ]);
  }

    public function create(): void
    {
        $this->requireRole('Admin');
        $this->render('accounts/create', ['user' => $this->currentUser()]);
    }

        public function store(): void
        {
        $this->verifyCsrf();
        $this->requireRole('Admin');

        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $status = trim($_POST['status'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        /*
        * Required fields
        */
        if (
            $fullName === '' ||
            $email === '' ||
            $phone === '' ||
            $role === '' ||
            $status === '' ||
            $password === ''
        ) {
            $this->showCreateForm('Please fill in all required fields.');

            return;
        }

        /*
        * Validate email.
        */
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->showCreateForm('Please enter a valid email address.');

            return;
        }

        /*
        * Validate role against the roles defined by the project.
        */
        if (!isset(ROLE_SLUGS[$role])) {
            $this->showCreateForm('Invalid user role.');

            return;
        }

        /*
        * Validate status.
        */
        $allowedStatuses = [
            'Active',
            'Inactive',
            'Suspended',
        ];

        if (!in_array($status, $allowedStatuses, true)) {
            $this->showCreateForm('Invalid account status.');

            return;
        }

        /*
        * Password requirements.
        */
        if (strlen($password) < 8) {
            $this->showCreateForm('Password must contain at least 8 characters.');

            return;
        }

        if ($password !== $confirmPassword) {
            $this->showCreateForm('Passwords do not match.');

            return;
        }

        $userModel = new User();

        /*
        * Email must be unique.
        */
        if ($userModel->findByEmail($email)) {
            $this->showCreateForm('An account with this email address already exists.');

            return;
        }

        /*
        * Create user.
        *
        * User::create() hashes the password for us.
        */
        $userModel->create([
            'full_name' => $fullName,
            'email'     => $email,
            'phone'     => $phone,
            'address'   => $address !== '' ? $address : null,
            'role'      => $role,
            'status'    => $status,
        ], $password);

        $this->flash('success', 'Account for "' . $fullName . '" was created.');
        $this->redirect('/admin/accounts');
    }

    public function detail(): void
    {
        $this->requireRole('Admin');

        // Get the user ID from the URL:
        // /admin/accounts/detail?id=1
        $id = (int) ($_GET['id'] ?? 0);

        // No valid ID supplied
        if ($id <= 0) {
            $this->redirect('/admin/accounts');
            return;
        }

        // Find the user in the database
        $userModel = new User();
        $account = $userModel->find($id);

        // User does not exist
        if (!$account) {
            $this->redirect('/admin/accounts');
            return;
        }

        // Send the database user to detail.php
        $this->render('accounts/detail', [
            'user'    => $this->currentUser(),
            'account' => $account,
        ]);
    }

    public function update(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Admin');

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->redirect('/admin/accounts');
            return;
        }

        $fullName = trim($_POST['full_name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $address  = trim($_POST['address'] ?? '');
        $role     = trim($_POST['role'] ?? '');
        $status   = trim($_POST['status'] ?? '');

        /*
        * Required fields
        */
        if (
            $fullName === '' ||
            $email === '' ||
            $phone === '' ||
            $role === '' ||
            $status === ''
        ) {
            $this->backToEdit($id, 'Please fill in all required fields.');
        }

        /*
        * Validate email
        */
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->backToEdit($id, 'Please enter a valid email address.');
        }

        /*
        * Validate role
        */
        if (!isset(ROLE_SLUGS[$role])) {
            $this->backToEdit($id, 'Please select a valid role.');
        }

        /*
        * Validate status
        */
        $allowedStatuses = [
            'Active',
            'Inactive',
            'Suspended'
        ];

        if (!in_array($status, $allowedStatuses, true)) {
            $this->backToEdit($id, 'Please select a valid status.');
        }

        $userModel = new User();

        /*
        * Make sure the account actually exists.
        */
        $account = $userModel->find($id);

        if (!$account) {
            $this->redirect('/admin/accounts');
            return;
        }

        /*
        * Email must stay unique: another account may already use it.
        * (Finding THIS account's own email is fine.)
        */
        $owner = $userModel->findByEmail($email);

        if ($owner && (int) $owner['user_id'] !== $id) {
            $this->backToEdit($id, 'Another account already uses this email address.');
        }

        /*
        * Update the database.
        */
        $userModel->update($id, [
            'full_name' => $fullName,
            'email'     => $email,
            'phone'     => $phone,
            'address'   => $address !== '' ? $address : null,
            'role'      => $role,
            'status'    => $status,
        ]);

        /*
        * Return to the details page.
        */
        $this->flash('success', 'Account updated successfully.');
        $this->redirect('/admin/accounts/detail?id=' . $id);
    }

    public function delete(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Admin');

        /*
        * Get the ID sent by the delete form.
        */
        $id = (int) ($_POST['id'] ?? 0);

        /*
        * Make sure we received a valid ID.
        */
        if ($id <= 0) {
            $this->redirect('/admin/accounts');
            return;
        }

        /*
        * An admin cannot delete the account they are signed in with.
        */
        if ($id === (int) $this->currentUser()['id']) {
            $this->flash('error', 'You cannot delete your own account while you are signed in with it.');
            $this->redirect('/admin/accounts/detail?id=' . $id);
        }

        $userModel = new User();

        /*
        * Make sure the account exists before deleting it.
        */
        $account = $userModel->find($id);

        if (!$account) {
            $this->redirect('/admin/accounts');
            return;
        }

        /*
        * Delete the account.
        */
        try {
            $userModel->delete($id);
            $this->flash('success', 'Account for "' . $account['name'] . '" was deleted.');
        } catch (PDOException $e) {
            // MySQL refuses to delete a user that orders, sales, purchase
            // orders or the audit log still point at (ON DELETE RESTRICT).
            // Tell the admin what to do instead.
            $this->flash('error', 'This account has related records and cannot be deleted. '
                . 'Set its status to Inactive instead.');
            $this->redirect('/admin/accounts/detail?id=' . $id);
        }

        /*
        * Return to the accounts list.
        */
        $this->redirect('/admin/accounts');
    }

    /**
     * Show the create form again with an error, refilled with what was
     * typed. Only these fields are sent back - never the passwords.
     */
    private function showCreateForm(string $error): void
    {
        $old = [];
        foreach (['full_name', 'email', 'phone', 'address', 'role', 'status'] as $field) {
            $old[$field] = trim($_POST[$field] ?? '');
        }

        $this->render('accounts/create', [
            'user'  => $this->currentUser(),
            'error' => $error,
            'old'   => $old,
        ]);
    }

    /** Show an error on the edit form of account $id. */
    private function backToEdit(int $id, string $message): void
    {
        $this->flash('error', $message);
        $this->redirect('/admin/accounts/detail?id=' . $id . '&mode=edit');
    }

}
