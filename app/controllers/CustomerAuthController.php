<?php

class CustomerAuthController extends Controller
{
    public function loginForm(): void
    {
        if ($this->currentUser()) {
            $this->redirect('/');
        }
        $this->renderBare('login', ['error' => $this->flash('error')], 'authentication');
    }

    public function login(): void
    {
        $this->verifyCsrf();
        $email = trim((string) $this->input('email', ''));
        $password = (string) $this->input('password', '');

        $userModel = new Customer();
        $user = $userModel->findByEmail($email);

        if (!$user || !$userModel->verifyPassword($user, $password)) {
            $this->flash('error', 'Invalid email or password.');
            $this->redirect('/login');
            return;
        }

        unset($user['password_hash']);
        $_SESSION['user'] = $user;
        $this->flash('success', 'Welcome back, ' . $user['name'] . '!');
        $this->redirect('/');
    }

    public function registerForm(): void
    {
        if ($this->currentUser()) {
            $this->redirect('/');
        }
        $this->renderBare('register', ['error' => $this->flash('error')], 'authentication');
    }

    public function register(): void
    {
        $this->verifyCsrf();
        $name     = trim((string) $this->input('name', ''));
        $email    = trim((string) $this->input('email', ''));
        $phone    = trim((string) $this->input('phone', ''));
        $address  = trim((string) $this->input('address', ''));
        $password = (string) $this->input('password', '');
        $confirm  = (string) $this->input('confirm_password', '');

        if ($name === '' || $email === '' || $password === '') {
            $this->flash('error', 'Please fill in all required fields.');
            $this->redirect('/register');
            return;
        }

        if ($password !== $confirm) {
            $this->flash('error', 'Passwords do not match.');
            $this->redirect('/register');
            return;
        }

        $userModel = new Customer();

        if ($userModel->findByEmail($email)) {
            $this->flash('error', 'An account with that email already exists.');
            $this->redirect('/register');
            return;
        }

        $user = $userModel->create([
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'address'  => $address,
            'password' => $password,
        ]);

        unset($user['password_hash']);
        $_SESSION['user'] = $user;
        $this->flash('success', 'Account created — welcome to PharmaSync!');
        $this->redirect('/');
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        $this->redirect('/login');
    }
}
