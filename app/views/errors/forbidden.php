<?php
/**
 * 403 - shown by Controller::requireRole() when a signed-in user opens a
 * page that belongs to a different role.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Not allowed - PharmaSync</title>
    <link rel="stylesheet" href="<?= asset('assets/css/main.css') ?>">
</head>
<body>

<h1>403 - not allowed</h1>

<p>Your account does not have access to this page.</p>

<p><a href="<?= url('/') ?>">Go to my dashboard</a></p>

</body>
</html>
