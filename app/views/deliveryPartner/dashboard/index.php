<?php
/**
 * Starter page for the Delivery Partner module.
 *
 * render('dashboard/index') looked for this file at
 * app/views/deliveryPartner/dashboard/index.php.
 *
 * When you build your own layout, add
 *   app/views/deliveryPartner/partials/header.php
 *   app/views/deliveryPartner/partials/footer.php
 * and render() will wrap every page of yours in them automatically.
 * Until those two files exist, pages render on their own like this one.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delivery Partner - PharmaSync</title>
    <link rel="stylesheet" href="<?= asset('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= role_css('Delivery_Partner', 'style.css') ?>">
</head>
<body>

<h1>Delivery Partner dashboard</h1>

<p>Signed in as <strong><?= e($user['name'] ?? 'nobody') ?></strong>
   (<?= e($user['role'] ?? '-') ?>).</p>

<p>This file is <code>app/views/deliveryPartner/dashboard/index.php</code>. Replace it.</p>

<ul>
    <li>Routes: <code>config/routes/deliveryPartner.php</code></li>
    <li>Controllers: <code>app/controllers/DeliveryPartner*Controller.php</code></li>
    <li>Models: <code>app/models/</code></li>
    <li>Views: <code>app/views/deliveryPartner/</code></li>
    <li>Stylesheets: <code>public/assets/css/DeliveryPartner/</code></li>
</ul>

<p><a href="<?= url('/') ?>">Home</a></p>

</body>
</html>
