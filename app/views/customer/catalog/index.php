<?php
  // One URL for this page. Filter links and forms all start from it.
  $formAction = url('/customer/catalog');
  require __DIR__ . '/../partials/catalog-url.php';
?>
<div class="flex between top mb-4 wrap gap-2">
  <div>
    <h3 class="bold mb-1"><?= htmlspecialchars($pageTitle) ?></h3>
    <p class="muted mb-0">Browse our pharmaceutical-grade inventory. Secure, verified, and delivered with precision.</p>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-3">
    <div class="ps-card p-3">
      <?php require __DIR__ . '/../partials/catalog-filters.php'; ?>
    </div>
  </div>

  <div class="col-lg-9">
    <?php require __DIR__ . '/../partials/catalog-toolbar.php'; ?>

    <?php if (empty($items)): ?>
      <div class="ps-empty-state ps-card">
        <?= icon('search') ?>
        <h5 class="mt-3">No medicines found</h5>
        <p class="muted">Try adjusting your filters.</p>
        <a href="<?= e($filterUrl(['category' => null, 'brand' => null, 'min_price' => null, 'max_price' => null])) ?>" class="btn btn-ps-primary mt-2">Reset Filters</a>
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($items as $m): ?>
          <div class="col-6 col-lg-4">
            <?php require __DIR__ . '/../partials/medicine-card.php'; ?>
          </div>
        <?php endforeach; ?>
      </div>

      <nav class="mt-4">
        <ul class="pager pager-sm center">
          <li class="pager-item disabled"><a class="pager-link" href="#">&laquo;</a></li>
          <li class="pager-item active"><a class="pager-link" href="#">1</a></li>
          <li class="pager-item disabled"><a class="pager-link" href="#">&raquo;</a></li>
        </ul>
      </nav>
    <?php endif; ?>
  </div>
</div>

<?php if (Session::isLoggedIn()): // guests have no past orders ?>
<a href="<?= BASE_URL ?>/customer/orders" class="btn btn-ps-primary rounded-pill fixed shadow" style="bottom:24px; right:24px; z-index:1000;">
  <?= icon('rotate-cw', 'me-2') ?>Refill Last Order
</a>
<?php endif; ?>
