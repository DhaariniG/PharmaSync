<?php
/**
 * Header for every Inventory Manager page.
 * Controller::render() puts this BEFORE the page view, so the <html>, <head>,
 * sidebar and topbar are written once here instead of in every screen.
 *
 * Variables the controller passes in:
 *   $page_title  - text in the tab and in the topbar
 *   $active_page - which sidebar link is highlighted
 *   $page_css    - this page's own stylesheet file name, e.g. 'dashboard.css'
 */
$page_title = $page_title ?? 'Inventory Manager';
$page_css   = $page_css ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PharmaSync - Inventory Manager <?= e($page_title) ?></title>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">

<!-- Shared across all Inventory Manager screens -->
<link rel="stylesheet" href="<?= role_css('Inventory_Manager', 'component/variables.css') ?>">
<link rel="stylesheet" href="<?= role_css('Inventory_Manager', 'component/sidebar.css') ?>">
<link rel="stylesheet" href="<?= role_css('Inventory_Manager', 'component/topbar.css') ?>">
<?php if ($page_css !== ''): ?>
<!-- This screen only -->
<link rel="stylesheet" href="<?= role_css('Inventory_Manager', $page_css) ?>">
<?php endif; ?>

</head>
<body>

<?php require __DIR__ . '/sidebar.php'; ?>
<?php require __DIR__ . '/topbar.php'; ?>
