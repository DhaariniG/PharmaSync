<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 — Page not found</title>
      <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/assets/css/Customer/style.css">
</head>
<body class="flex middle center" style="min-height:100vh;">
  <div class="text-center">
    <?= icon('triangle-alert', 'size-1 text-warning mb-3') ?>
    <h2 class="bold">404 — Page not found</h2>
    <p class="muted">The page you're looking for doesn't exist.</p>
    <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>/" class="btn btn-ps-primary">Go home</a>
  </div>
</body>
</html>
