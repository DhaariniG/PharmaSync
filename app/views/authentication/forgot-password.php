<?php
/**
 * Forgot password - asks for an email. Sending the reset link is not built
 * yet (see AuthenticationController::forgot).
 *
 * Variables: $flash (type => message), $old (previous input).
 */
$flash = $flash ?? [];
$old   = $old ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - PharmaSync</title>
    <link rel="stylesheet" href="<?= asset('assets/css/Authentication/login.css') ?>">
</head>
<body>
<main class="auth-workspace auth-single">
    <div class="auth-container">

        <div class="mobile-logo show"><?= icon('plus') ?><h2>PharmaSync</h2></div>

        <?php foreach ($flash as $type => $message): ?>
            <div class="alert alert-<?= $type === 'success' ? 'success' : 'danger' ?>" role="alert"><?= e($message) ?></div>
        <?php endforeach; ?>

        <form class="auth-form" method="post" action="<?= url('/' . AUTH_SLUG . '/forgot-password') ?>">
            <?= csrf_field() ?>
            <div class="form-header">
                <h2>Reset your password</h2>
                <p>Enter your account email and we will send you a reset link.</p>
            </div>

            <div class="form-group">
                <label for="forgot-email">Email</label>
                <div class="input-icon-wrapper">
                    <?= icon('mail', 'input-icon') ?>
                    <input type="email" id="forgot-email" name="email" value="<?= old('email', $old) ?>" placeholder="e.g. name@pharmasync.com" class="form-control padded-input" autocomplete="email" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <span>Send Reset Link</span><?= icon('send') ?>
            </button>

            <a href="<?= url('/' . AUTH_SLUG . '/login') ?>" class="back-link"><?= icon('arrow-left') ?>Back to log in</a>
        </form>

    </div>
</main>
</body>
</html>
