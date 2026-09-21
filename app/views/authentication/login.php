<?php
/**
 * Login + Create Account - one page, two tabs.
 *
 * UI from the group's authentication design (feature/Pharmacist branch).
 * $mode is 'login' or 'register' and decides which tab is open, so the page
 * still works with JavaScript off: the tabs are real links to
 * /authentication/login and /authentication/register, and the script below
 * only makes switching instant.
 *
 * Variables: $mode, $flash (type => message), $old (previous input).
 */
$mode  = ($mode ?? 'login') === 'register' ? 'register' : 'login';
$flash = $flash ?? [];
$old   = $old ?? [];
$auth  = '/' . AUTH_SLUG;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $mode === 'register' ? 'Create Account' : 'Log In' ?> - PharmaSync</title>
    <link rel="stylesheet" href="<?= asset('assets/css/Authentication/login.css') ?>">
</head>
<body>
<div class="split-viewport">

    <!-- LEFT: brand panel -->
    <aside class="hero-sidebar">
        <div class="hero-content">
            <div class="brand-header">
                <div class="logo-box"><?= icon('plus') ?></div>
                <span class="brand-name">PharmaSync</span>
            </div>

            <h1 class="hero-title">Digital Pharmacy<br>Management System</h1>
            <p class="hero-subtitle">Manage inventory, prescriptions, orders, and deliveries — all in one platform built for modern pharmacies.</p>

            <ul class="feature-list">
                <li><span class="check-icon"><?= icon('check') ?></span><span>FEFO-based inventory tracking</span></li>
                <li><span class="check-icon"><?= icon('check') ?></span><span>Automated drug interaction &amp; dosage checks</span></li>
                <li><span class="check-icon"><?= icon('check') ?></span><span>Order and delivery tracking</span></li>
            </ul>
        </div>

        <div class="hero-footer">
            <p>&copy; <?= date('Y') ?> PharmaSync. All rights reserved.</p>
        </div>
    </aside>

    <!-- RIGHT: forms -->
    <main class="auth-workspace">
        <div class="auth-container">

            <div class="mobile-logo"><?= icon('plus') ?><h2>PharmaSync</h2></div>

            <?php foreach ($flash as $type => $message): ?>
                <div class="alert alert-<?= $type === 'success' ? 'success' : 'danger' ?>" role="alert"><?= e($message) ?></div>
            <?php endforeach; ?>

            <nav class="auth-tabs" aria-label="Log in or create an account">
                <a href="<?= url($auth . '/login') ?>" class="auth-tab-btn <?= $mode === 'login' ? 'active' : '' ?>" data-auth-tab="login">Log In</a>
                <a href="<?= url($auth . '/register') ?>" class="auth-tab-btn <?= $mode === 'register' ? 'active' : '' ?>" data-auth-tab="register">Create Account</a>
            </nav>

            <!-- LOG IN -->
            <form id="form-login" class="auth-form" method="post" action="<?= url($auth . '/login') ?>" <?= $mode === 'login' ? '' : 'hidden' ?>>
                <?= csrf_field() ?>
                <div class="form-header">
                    <h2>Welcome Back!</h2>
                    <p>Log in with the email you registered with.</p>
                </div>

                <div class="form-group">
                    <label for="login-email">Email</label>
                    <div class="input-icon-wrapper">
                        <?= icon('user', 'input-icon') ?>
                        <input type="email" id="login-email" name="email" value="<?= old('email', $old) ?>" placeholder="e.g. name@pharmasync.com" class="form-control padded-input" autocomplete="email" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="label-row">
                        <label for="login-password">Password</label>
                        <a href="<?= url($auth . '/forgot-password') ?>" class="forgot-link">Forgot?</a>
                    </div>
                    <div class="input-icon-wrapper">
                        <?= icon('lock', 'input-icon') ?>
                        <input type="password" id="login-password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" class="form-control padded-input" autocomplete="current-password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <span>Log In to Portal</span><?= icon('arrow-right') ?>
                </button>
            </form>

            <!-- CREATE ACCOUNT -->
            <form id="form-register" class="auth-form" method="post" action="<?= url($auth . '/register') ?>" <?= $mode === 'register' ? '' : 'hidden' ?>>
                <?= csrf_field() ?>
                <div class="form-header">
                    <h2>Create New Account</h2>
                    <p>Customer accounts only. Staff accounts are created by the admin.</p>
                </div>

                <div class="form-group">
                    <label for="reg-name">Full Name</label>
                    <input type="text" id="reg-name" name="full_name" value="<?= old('full_name', $old) ?>" placeholder="e.g. Nadeesha Perera" class="form-control" autocomplete="name" minlength="3" maxlength="150" required>
                </div>

                <div class="form-group">
                    <label for="reg-phone">Contact Number</label>
                    <input type="tel" id="reg-phone" name="phone" value="<?= old('phone', $old) ?>" placeholder="e.g. 077 1234567" class="form-control" autocomplete="tel" minlength="10" maxlength="20" required>
                </div>

                <div class="form-group">
                    <label for="reg-email">Email Address</label>
                    <input type="email" id="reg-email" name="email" value="<?= old('email', $old) ?>" placeholder="name@example.com" class="form-control" autocomplete="email" maxlength="150" required>
                </div>

                <div class="form-row-2col">
                    <div class="form-group">
                        <label for="reg-password">Password</label>
                        <input type="password" id="reg-password" name="password" placeholder="At least 8 characters" class="form-control" autocomplete="new-password" minlength="8" required>
                    </div>
                    <div class="form-group">
                        <label for="reg-password-confirm">Confirm Password</label>
                        <input type="password" id="reg-password-confirm" name="password_confirm" placeholder="Repeat password" class="form-control" autocomplete="new-password" minlength="8" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <span>Submit Registration</span><?= icon('user-plus') ?>
                </button>
            </form>

            <div class="security-footer">
                <?= icon('shield-check') ?><span>Secure &amp; Encrypted Connection</span>
            </div>

        </div>
    </main>
</div>

<script>
/* Switch tabs without a page load. The links still work without JS. */
document.querySelectorAll('[data-auth-tab]').forEach(function (tab) {
    tab.addEventListener('click', function (event) {
        event.preventDefault();
        var mode = tab.getAttribute('data-auth-tab');

        document.getElementById('form-login').hidden    = mode !== 'login';
        document.getElementById('form-register').hidden = mode !== 'register';

        document.querySelectorAll('[data-auth-tab]').forEach(function (t) {
            t.classList.toggle('active', t === tab);
        });

        history.replaceState(null, '', tab.href);
        document.title = (mode === 'register' ? 'Create Account' : 'Log In') + ' - PharmaSync';
    });
});
</script>
</body>
</html>
