<?php
/**
 * Registration - STUB. Not built yet.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - PharmaSync</title>
    <link rel="stylesheet" href="<?= asset('assets/css/main.css') ?>">
</head>
<body>

<h1>Create an account</h1>

<?php foreach ($flash ?? [] as $type => $message): ?>
    <p class="flash flash-<?= e($type) ?>"><?= e($message) ?></p>
<?php endforeach; ?>

<p>Registration is not built yet.</p>

<p><a href="<?= url('/' . AUTH_SLUG . '/login') ?>">Back to sign in</a></p>

</body>
</html>
