<div class="flex between top mb-4 wrap gap-2">
  <div>
    <h3 class="bold mb-1"><?= htmlspecialchars($pageTitle) ?></h3>
    <p class="muted mb-0">Browse our pharmaceutical-grade inventory. Secure, verified, and delivered with precision.</p>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-3">
    <div class="ps-card p-3">
      <?php
        $formAction = BASE_URL . '/catalog';
        require __DIR__ . '/../partials/catalog-filters.php';
      ?>
    </div>
  </div>

  <div class="col-lg-9">
    <div class="flex between middle mb-3 wrap gap-2">
      <div class="flex wrap gap-2">
        <?php if ($activeCategory): ?>
          <span class="ps-chip"><?= htmlspecialchars(Medicine::categoryName($activeCategory)) ?> <a href="<?= BASE_URL ?>/catalog"><?= icon('x') ?></a></span>
        <?php endif; ?>
        <?php foreach ($activeBrands as $b): ?>
          <span class="ps-chip"><?= htmlspecialchars($b) ?></span>
        <?php endforeach; ?>
        <?php if (!$activeCategory && empty($activeBrands)): ?>
          <span class="muted small"><?= count($items) ?> medicines available</span>
        <?php endif; ?>
      </div>
      <form method="GET" class="flex middle gap-2">
        <?php if ($activeCategory): ?><input type="hidden" name="category" value="<?= $activeCategory ?>"><?php endif; ?>
        <label class="small muted mb-0">Sort by:</label>
        <select name="sort" class="field field-sm" style="width:auto;" onchange="this.form.submit()">
          <option value="relevance" <?= $activeSort === 'relevance' ? 'selected' : '' ?>>Relevance</option>
          <option value="price_asc" <?= $activeSort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
          <option value="price_desc" <?= $activeSort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
          <option value="name" <?= $activeSort === 'name' ? 'selected' : '' ?>>Name (A-Z)</option>
        </select>
      </form>
    </div>

    <?php if (empty($items)): ?>
      <div class="ps-empty-state ps-card">
        <?= icon('search') ?>
        <h5 class="mt-3">No medicines found</h5>
        <p class="muted">Try adjusting your filters.</p>
        <a href="<?= BASE_URL ?>/catalog" class="btn btn-ps-primary mt-2">Reset Filters</a>
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

<a href="<?= BASE_URL ?>/orders" class="btn btn-ps-primary rounded-pill fixed shadow" style="bottom:24px; right:24px; z-index:1000;">
  <?= icon('rotate-cw', 'me-2') ?>Refill Last Order
</a>
