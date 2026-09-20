<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaSync - Portal Access</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/login.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="split-viewport">
        
        <!-- LEFT SIDE: Hero Section -->
        <div class="hero-sidebar">
            <div class="hero-content">
                <div class="brand-header">
                    <div class="logo-box">
                        <i data-lucide="plus"></i>
                    </div>
                    <span class="brand-name">PharmaSync</span>
                </div>

                <h1 class="hero-title">Digital Pharmacy<br>Management System</h1>
                <p class="hero-subtitle">Manage inventory, prescriptions, orders, and deliveries — all in one platform built for modern pharmacies.</p>

                <ul class="feature-list">
                    <li>
                        <div class="check-icon"><i data-lucide="check"></i></div>
                        <span>FEFO-based inventory tracking</span>
                    </li>
                    <li>
                        <div class="check-icon"><i data-lucide="check"></i></div>
                        <span>Automated drug interaction & dosage checks</span>
                    </li>
                    <li>
                        <div class="check-icon"><i data-lucide="check"></i></div>
                        <span>Order and delivery tracking</span>
                    </li>
                </ul>
            </div>

            <div class="hero-footer">
                <p>© 2026 PharmaSync. All rights reserved.</p>
            </div>
        </div>

        <!-- RIGHT SIDE: Auth Workspace -->
        <div class="auth-workspace">
            <div class="auth-container">
                
                <!-- Display Session Flash Messages Safely -->
                <?php 
                if (isset($_SESSION['flash_error'])) {
                    echo '<div class="alert alert-danger" style="color: #dc2626; background: #fef2f2; padding: 10px; border-radius: 6px; margin-bottom: 15px;">' . htmlspecialchars($_SESSION['flash_error']) . '</div>';
                    unset($_SESSION['flash_error']);
                }
                if (isset($_SESSION['flash_success'])) {
                    echo '<div class="alert alert-success" style="color: #16a34a; background: #f0fdf4; padding: 10px; border-radius: 6px; margin-bottom: 15px;">' . htmlspecialchars($_SESSION['flash_success']) . '</div>';
                    unset($_SESSION['flash_success']);
                }
                ?>

                <div class="mobile-logo">
                    <i data-lucide="plus"></i>
                    <h2>PharmaSync</h2>
                </div>

                <div class="auth-tabs">
                    <button class="auth-tab-btn active" id="tab-login" onclick="switchAuthMode('login')">Log In</button>
                    <button class="auth-tab-btn" id="tab-register" onclick="switchAuthMode('register')">Create Account</button>
                </div>

                <!-- LOG IN FORM -->
                <form id="form-login" class="auth-form active-form" action="<?= BASE_URL ?>/index.php?url=auth/login" method="POST">
                    <div class="form-header">
                        <h2>Welcome Back !</h2>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-icon-wrapper">
                            <i data-lucide="user" class="input-icon"></i>
                            <input type="email" name="email" placeholder="e.g. name@pharmasync.com" class="form-control padded-input" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="label-row">
                            <label>Password</label>
                            <a href="<?= BASE_URL ?>/index.php?url=auth/forgot_password" class="forgot-link">Forgot?</a>
                        </div>
                        <div class="input-icon-wrapper">
                            <i data-lucide="lock" class="input-icon"></i>
                            <input type="password" name="password" placeholder="••••••••••••" class="form-control padded-input" required>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember_me">
                            <span class="label-text">Remember this device</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <span>Log In to Portal</span>
                        <i data-lucide="arrow-right"></i>
                    </button>
                </form>

                <!-- CREATE ACCOUNT FORM -->
                <form id="form-register" class="auth-form" action="<?= BASE_URL ?>/index.php?url=auth/signup" method="POST" style="display: none;">
                    <div class="form-header">
                        <h2>Create New Account</h2>
                        <p>Fill in your details to create your portal account.</p>
                    </div>

                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" placeholder="John Doe" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" name="phone" placeholder="e.g. 077 1234567" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="name@pharmasync.com" class="form-control" required>
                    </div>

                    <div class="form-row-2col">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="••••••••" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" placeholder="••••••••" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <span>Submit Registration</span>
                        <i data-lucide="user-plus"></i>
                    </button>
                </form>

                <div class="security-footer">
                    <i data-lucide="shield-check"></i>
                    <span>Secure & Encrypted Connection</span>
                </div>

            </div>
        </div>

    </div>

    <script>
        lucide.createIcons();

        function switchAuthMode(mode) {
            const loginForm = document.getElementById('form-login');
            const registerForm = document.getElementById('form-register');
            const loginTab = document.getElementById('tab-login');
            const registerTab = document.getElementById('tab-register');

            if (mode === 'register') {
                loginForm.style.display = 'none';
                registerForm.style.display = 'flex';
                loginTab.classList.remove('active');
                registerTab.classList.add('active');
            } else {
                registerForm.style.display = 'none';
                loginForm.style.display = 'flex';
                registerTab.classList.remove('active');
                loginTab.classList.add('active');
            }
        }
    </script>
</body>
</html>