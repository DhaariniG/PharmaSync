<?php
/**
 * Starter page for the Pharmacist module.
 *
 * render('dashboard/index') looked for this file at
 * app/views/pharmacist/dashboard/index.php.
 *
 * When you build your own layout, add
 *   app/views/pharmacist/partials/header.php
 *   app/views/pharmacist/partials/footer.php
 * and render() will wrap every page of yours in them automatically.
 * Until those two files exist, pages render on their own like this one.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pharmacist - PharmaSync</title>
    <link rel="stylesheet" href="<?= asset('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= role_css('Pharmacist', 'style.css') ?>">
</head>
<body>

<h1>Pharmacist dashboard</h1>

<p>Signed in as <strong><?= e($user['name'] ?? 'nobody') ?></strong>
   (<?= e($user['role'] ?? '-') ?>).</p>

<p>This file is <code>app/views/pharmacist/dashboard/index.php</code>. Replace it.</p>

<ul>
    <li>Routes: <code>config/routes/pharmacist.php</code></li>
    <li>Controllers: <code>app/controllers/Pharmacist*Controller.php</code></li>
    <li>Models: <code>app/models/</code></li>
    <li>Views: <code>app/views/pharmacist/</code></li>
    <li>Stylesheets: <code>public/assets/css/Pharmacist/</code></li>
</ul>

<p><a href="<?= url('/') ?>">Home</a></p>

</body>
</html>
