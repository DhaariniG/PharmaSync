<?php
/**
 * 404 - shown by Router::notFound() and by Controller::notFound().
 * $requestedPath is set when the router raised it, not when a controller did.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page not found - PharmaSync</title>
    <link rel="stylesheet" href="<?= asset('assets/css/main.css') ?>">
</head>
<body>

<h1>404 - page not found</h1>

<?php if (APP_ENV === 'dev' && !empty($requestedPath)): ?>
    <p>No route matches <code><?= e($requestedPath) ?></code>.
       Check your file in <code>config/routes/</code>.</p>
<?php endif; ?>

<p><a href="<?= url('/') ?>">Go back</a></p>

</body>
</html>
