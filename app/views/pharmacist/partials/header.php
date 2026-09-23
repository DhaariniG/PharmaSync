<?php
/**
 * Header for every Pharmacist page (not the printable ones).
 * Controller::render() puts this BEFORE the page view, so the <html>, <head>,
 * sidebar and top bar are written once here.
 *
 * Variables the controller passes in:
 *   $page_title      - text in the tab and in the top bar
 *   $active_page     - which sidebar link is highlighted
 *   $page_css        - this page's own stylesheet, e.g. 'dashboard.css'
 *   $container_class - outer wrapper class ('container' or 'dashboard-container')
 *   $show_topbar     - false for the one page that draws its own top bar
 */
$page_title      = $page_title ?? 'Pharmacist Portal';
$page_css        = $page_css ?? '';
$container_class = $container_class ?? 'container';
$show_topbar     = $show_topbar ?? true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaSync - <?= e($page_title) ?></title>
<?php if ($page_css !== ''): ?>
    <link rel="stylesheet" href="<?= role_css('Pharmacist', $page_css) ?>">
<?php endif; ?>
<?php if (!empty($page_css)): ?>
    <link rel="stylesheet" href="<?= url('/css/' . e($page_css)) ?>">
<?php endif; ?>
    <!-- Lucide icons (external CDN - see HANDOFF.md) -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="<?= e($container_class) ?>">
        <?php require __DIR__ . '/sidebar.php'; ?>

        <main class="main-content">
            <?php if ($show_topbar) { require __DIR__ . '/topbar.php'; } ?>
            <?php require __DIR__ . '/flash.php'; ?>
