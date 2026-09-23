<?php
/**
 * Shared layout for the standalone error pages (no sidebar or topbar, so it
 * works for any role and for signed-out visitors).
 *
 * Variables it expects: $code, $heading, $message.
 * "Back" goes to the signed-in user's dashboard, or the site root.
 */
$slug    = Session::roleSlug();
$backUrl = $slug ? url('/' . $slug . '/dashboard') : url('/');
$backTxt = $slug ? 'Back to dashboard' : 'Back to home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PharmaSync - <?= e($heading) ?></title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="<?= role_css('Inventory_Manager', 'component/variables.css') ?>">
<style>
    .error-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: var(--space-lg); }
    .error-card { background: var(--color-white); border: 1px solid var(--color-border); border-radius: var(--radius-xl); padding: var(--space-xl); max-width: 440px; width: 100%; text-align: center; }
    .error-brand { color: var(--color-secondary); font-weight: 700; font-size: var(--text-h2); margin-bottom: var(--space-lg); }
    .error-code { font-size: 56px; font-weight: 700; color: var(--color-secondary); line-height: 1; }
    .error-heading { font-size: var(--text-h1); margin: var(--space-md) 0 var(--space-sm); }
    .error-message { color: var(--color-text-muted); font-size: var(--text-body); margin-bottom: var(--space-lg); }
    .error-back { display: inline-block; background: var(--color-secondary); color: var(--color-text-on-secondary); padding: 10px var(--space-lg); border-radius: var(--radius-lg); font-weight: 600; font-size: var(--text-body); }
</style>
</head>
<body>
<main class="error-page">
    <div class="error-card">
        <p class="error-brand">PharmaSync</p>
        <p class="error-code"><?= e($code) ?></p>
        <h1 class="error-heading"><?= e($heading) ?></h1>
        <p class="error-message"><?= e($message) ?></p>
        <a class="error-back" href="<?= e($backUrl) ?>"><?= e($backTxt) ?></a>
    </div>
</main>
</body>
</html>
