<?php
/**
 * Starter page for the Inventory Manager module.
 *
 * render('dashboard/index') looked for this file at
 * app/views/InventoryManager/dashboard/index.php.
 *
 * When you build your own layout, add
 *   app/views/InventoryManager/partials/header.php
 *   app/views/InventoryManager/partials/footer.php
 * and render() will wrap every page of yours in them automatically.
 * Until those two files exist, pages render on their own like this one.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory Manager - PharmaSync</title>
    <link rel="stylesheet" href="<?= asset('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= role_css('Inventory_Manager', 'style.css') ?>">
</head>
<body>

<h1>Inventory Manager dashboard</h1>

<p>Signed in as <strong><?= e($user['name'] ?? 'nobody') ?></strong>
   (<?= e($user['role'] ?? '-') ?>).</p>

<p>This file is <code>app/views/InventoryManager/dashboard/index.php</code>. Replace it.</p>

<ul>
    <li>Routes: <code>config/routes/inventoryManager.php</code></li>
    <li>Controllers: <code>app/controllers/InventoryManager*Controller.php</code></li>
    <li>Models: <code>app/models/</code></li>
    <li>Views: <code>app/views/InventoryManager/</code></li>
    <li>Stylesheets: <code>public/assets/css/InventoryManager/</code></li>
</ul>

<p><a href="<?= url('/') ?>">Home</a></p>

</body>
</html>
