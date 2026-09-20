<?php

class Auth extends Controller
{
    /**
     * Handles user login via POST and renders the auth view on GET.
     */
    public function login(): void
    {
        if ($this->isPost()) {
            // 1. Validate form fields
            $errors = $this->validate($_POST, [
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if (!empty($errors)) {
                Session::flash('error', reset($errors));
                Session::keepOld($_POST);
                $this->redirect('auth/login');
            }

            $email    = $this->input('email');
            $password = $this->input('password');

            // 2. Fetch user from the User model
            $userModel = $this->model('User');
            $user      = $userModel->findByEmail($email);

            // 3. Verify user existence and hashed password
            if ($user && password_verify($password, $user->password_hash)) {
                
                // Check if account is active
                if (isset($user->status) && $user->status !== 'Active') {
                    Session::flash('error', 'Your account is suspended or inactive.');
                    $this->redirect('auth/login');
                }

                // Store user session securely via Code 2 Session::login()
                // Expects: user_id (int), role (string ENUM), full_name (string)
                Session::login((int) $user->user_id, $user->role, $user->full_name);

                // Record the last login timestamp
                $userModel->recordLogin($user->user_id);

                // 4. Automatically redirect based on the user's role slug
                $this->redirectToDashboard();

            } else {
                Session::flash('error', 'Invalid email or password.');
                Session::keepOld($_POST);
                $this->redirect('auth/login');
            }
        } else {
            /*
            // Uncomment if you ever want logged-in users to auto-skip the login form:
            if (Session::isLoggedIn()) {
                $this->redirectToDashboard();
            }
            */

            $this->view('authentication/login');
        }
    }

    /**
     * Handles new customer registration via POST.
     */
    public function signup(): void
    {
        if ($this->isPost()) {
            // 1. Validate inputs
            $errors = $this->validate($_POST, [
                'full_name'        => 'required|min:3',
                'email'            => 'required|email',
                'phone'            => 'required|min:10',
                'password'         => 'required|min:6',
                'confirm_password' => 'required|match:password',
            ]);

            if (!empty($errors)) {
                Session::flash('error', reset($errors));
                Session::keepOld($_POST);
                $this->redirect('auth/login');
            }

            $userModel = $this->model('User');

            // 2. Prevent duplicate email registration
            if ($userModel->findByEmail($this->input('email'))) {
                Session::flash('error', 'An account with that email already exists.');
                Session::keepOld($_POST);
                $this->redirect('auth/login');
            }

            // 3. Prepare data array matching User::registerCustomer() expected keys
            $registrationData = [
                'full_name'          => $this->input('full_name'),
                'email'             => $this->input('email'),
                'phone'             => $this->input('phone'),
                'address'           => $this->input('address', ''),
                'password'          => $this->input('password'),
                'date_of_birth'     => $this->input('date_of_birth', ''),
                'allergies'         => $this->input('allergies', ''),
                'medical_conditions' => $this->input('medical_conditions', ''),
            ];

            // 4. Save through model transaction
            $newUserId = $userModel->registerCustomer($registrationData);

            if ($newUserId) {
                Session::flash('success', 'Registration successful! You can now log in.');
                $this->redirect('auth/login');
            } else {
                Session::flash('error', 'An error occurred during registration. Please try again.');
                Session::keepOld($_POST);
                $this->redirect('auth/login');
            }
        } else {
            $this->view('authentication/login');
        }
    }

    /**
     * Handles forgot password requests.
     */
    public function forgotPassword(): void
    {
        if ($this->isPost()) {
            $email = $this->input('email');
            
            // Password reset logic / email trigger goes here...
            
            Session::flash('success', 'If an account exists for that email, a password reset link has been sent.');
            $this->redirect('auth/login');
        } else {
            $this->view('authentication/forgot_password');
        }
    }

    /**
     * Destroys session and logs out the user cleanly.
     */
    public function logout(): void
    {
        Session::logout();
        $this->redirect('auth/login');
    }
}