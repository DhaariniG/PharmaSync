<?php
/**
 * Starter page for the Admin module.
 *
 * render('dashboard/index') looked for this file at
 * app/views/admin/dashboard/index.php.
 *
 * When you build your own layout, add
 *   app/views/admin/partials/header.php
 *   app/views/admin/partials/footer.php
 * and render() will wrap every page of yours in them automatically.
 * Until those two files exist, pages render on their own like this one.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - PharmaSync</title>
    <link rel="stylesheet" href="<?= asset('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= role_css('Admin', 'style.css') ?>">
</head>
<body>

<h1>Admin dashboard</h1>

<p>Signed in as <strong><?= e($user['name'] ?? 'nobody') ?></strong>
   (<?= e($user['role'] ?? '-') ?>).</p>

<p>This file is <code>app/views/admin/dashboard/index.php</code>. Replace it.</p>

<ul>
    <li>Routes: <code>config/routes/admin.php</code></li>
    <li>Controllers: <code>app/controllers/Admin*Controller.php</code></li>
    <li>Models: <code>app/models/</code></li>
    <li>Views: <code>app/views/admin/</code></li>
    <li>Stylesheets: <code>public/assets/css/Admin/</code></li>
</ul>

<p><a href="<?= url('/') ?>">Home</a></p>

</body>
</html>
