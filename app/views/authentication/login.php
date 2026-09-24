<?php
/**
 * Login - STUB.
 *
 * There is no password check yet: pick a role and the stub signs you in as
 * that role's demo user. Whoever owns authentication replaces this page with
 * a real email + password form. Keep csrf_field() when you do.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in - PharmaSync</title>
    <link rel="stylesheet" href="<?= asset('assets/css/main.css') ?>">
</head>
<body>

<h1>PharmaSync</h1>

<?php foreach ($flash ?? [] as $type => $message): ?>
    <p class="flash flash-<?= e($type) ?>"><?= e($message) ?></p>
<?php endforeach; ?>

<form method="post" action="<?= url('/' . AUTH_SLUG . '/login') ?>">
    <?= csrf_field() ?>

    <label for="role">Sign in as</label>
    <select name="role" id="role">
        <?php foreach (ROLE_SLUGS as $dbRole => $slug): ?>
            <option value="<?= e($dbRole) ?>"><?= e(str_replace('_', ' ', $dbRole)) ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Sign in</button>
</form>

<p><small>Stub login. Replace with a real users lookup.</small></p>

</body>
</html>
