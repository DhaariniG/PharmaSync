<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaSync — <?= $__title ?? 'Account' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="d-flex align-items-center" style="min-height:100vh; background: linear-gradient(135deg, var(--ps-primary-light), #ffffff);">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-11 col-sm-8 col-md-6 col-lg-4">
      <div class="text-center mb-4">
        <a href="<?= BASE_URL ?>/login" class="text-decoration-none">
          <span class="brand fs-3"><i class="fa-solid fa-capsules me-1"></i>PharmaSync</span>
        </a>
      </div>
      <div class="ps-card shadow-sm p-4 p-md-5">
