<?php
/**
 * Starter page for the Customer module.
 *
 * render('dashboard/index') looked for this file at
 * app/views/customer/dashboard/index.php.
 *
 * When you build your own layout, add
 *   app/views/customer/partials/header.php
 *   app/views/customer/partials/footer.php
 * and render() will wrap every page of yours in them automatically.
 * Until those two files exist, pages render on their own like this one.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer - PharmaSync</title>
    <link rel="stylesheet" href="<?= asset('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= role_css('Customer', 'style.css') ?>">
</head>
<body>

<h1>Customer dashboard</h1>

<p>Signed in as <strong><?= e($user['name'] ?? 'nobody') ?></strong>
   (<?= e($user['role'] ?? '-') ?>).</p>

<p>This file is <code>app/views/customer/dashboard/index.php</code>. Replace it.</p>

<ul>
    <li>Routes: <code>config/routes/customer.php</code></li>
    <li>Controllers: <code>app/controllers/Customer*Controller.php</code></li>
    <li>Models: <code>app/models/</code></li>
    <li>Views: <code>app/views/customer/</code></li>
    <li>Stylesheets: <code>public/assets/css/Customer/</code></li>
</ul>

<p><a href="<?= url('/') ?>">Home</a></p>

</body>
</html>
