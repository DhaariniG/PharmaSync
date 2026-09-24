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
            $this->render('accounts/create', [
                'user' => $this->currentUser(),
                'error' => 'Please fill in all required fields.',
            ]);

            return;
        }

        /*
        * Validate email.
        */
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render('accounts/create', [
                'user' => $this->currentUser(),
                'error' => 'Please enter a valid email address.',
            ]);

            return;
        }

        /*
        * Validate role against the roles defined by the project.
        */
        if (!isset(ROLE_SLUGS[$role])) {
            $this->render('accounts/create', [
                'user' => $this->currentUser(),
                'error' => 'Invalid user role.',
            ]);

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
            $this->render('accounts/create', [
                'user' => $this->currentUser(),
                'error' => 'Invalid account status.',
            ]);

            return;
        }

        /*
        * Password requirements.
        */
        if (strlen($password) < 8) {
            $this->render('accounts/create', [
                'user' => $this->currentUser(),
                'error' => 'Password must contain at least 8 characters.',
            ]);

            return;
        }

        if ($password !== $confirmPassword) {
            $this->render('accounts/create', [
                'user' => $this->currentUser(),
                'error' => 'Passwords do not match.',
            ]);

            return;
        }

        $userModel = new User();

        /*
        * Email must be unique.
        */
        if ($userModel->findByEmail($email)) {
            $this->render('accounts/create', [
                'user' => $this->currentUser(),
                'error' => 'An account with this email address already exists.',
            ]);

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
            $this->redirect(
                '/admin/accounts/detail?id=' . $id . '&mode=edit&error=required'
            );
            return;
        }

        /*
        * Validate email
        */
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirect(
                '/admin/accounts/detail?id=' . $id . '&mode=edit&error=email'
            );
            return;
        }

        /*
        * Validate role
        */
        if (!isset(ROLE_SLUGS[$role])) {
            $this->redirect(
                '/admin/accounts/detail?id=' . $id . '&mode=edit&error=role'
            );
            return;
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
            $this->redirect(
                '/admin/accounts/detail?id=' . $id . '&mode=edit&error=status'
            );
            return;
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
        $this->redirect(
            '/admin/accounts/detail?id=' . $id . '&updated=1'
        );
    }

    public function delete(): void
    {
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
        $userModel->delete($id);

        /*
        * Return to the accounts list.
        */
        $this->redirect('/admin/accounts?deleted=1');
    }

}
