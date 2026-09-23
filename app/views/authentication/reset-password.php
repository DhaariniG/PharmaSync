<?php
$flash = $flash ?? [];
$old   = $old ?? [];
$token = $token ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Set New Password - PharmaSync</title>
    <link rel="stylesheet" href="<?= asset('assets/css/Authentication/login.css') ?>">
</head>
<body>
<main class="auth-workspace auth-single">
    <div class="auth-container">

        <div class="mobile-logo show"><?= icon('plus') ?><h2>PharmaSync</h2></div>

        <?php foreach ($flash as $type => $message): ?>
            <div class="alert alert-<?= $type === 'success' ? 'success' : 'danger' ?>" role="alert"><?= e($message) ?></div>
        <?php endforeach; ?>

        <form class="auth-form" method="post" action="<?= url('/' . AUTH_SLUG . '/reset-password') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= e($token) ?>">

            <div class="form-header">
                <h2>Set new password</h2>
                <p>Please enter your new password below.</p>
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <div class="input-icon-wrapper">
                    <?= icon('lock', 'input-icon') ?>
                    <input type="password" id="password" name="password" class="form-control padded-input" placeholder="At least 8 characters" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirm New Password</label>
                <div class="input-icon-wrapper">
                    <?= icon('lock', 'input-icon') ?>
                    <input type="password" id="password_confirm" name="password_confirm" class="form-control padded-input" placeholder="Repeat new password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <span>Update Password</span><?= icon('check') ?>
            </button>

            <a href="<?= url('/' . AUTH_SLUG . '/login') ?>" class="back-link"><?= icon('arrow-left') ?>Back to log in</a>
        </form>

    </div>
</main>
</body>
</html>