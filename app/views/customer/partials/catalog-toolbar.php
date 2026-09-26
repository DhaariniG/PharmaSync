<?php
/*
 * Above the medicine grid: how many results, one chip per active filter
 * (each with an x to remove just that one), and the sort dropdown.
 * Include partials/catalog-url.php first.
 *
 * Expects: $items, $filters, $filterUrl, $hasFilters, $formAction.
 * Optional: $baseQuery.
 */
$baseQuery = $baseQuery ?? [];

$priceChip = null;
if ($filters['min_price'] !== null && $filters['max_price'] !== null) {
    $priceChip = money($filters['min_price']) . ' – ' . money($filters['max_price']);
} elseif ($filters['min_price'] !== null) {
    $priceChip = 'From ' . money($filters['min_price']);
} elseif ($filters['max_price'] !== null) {
    $priceChip = 'Up to ' . money($filters['max_price']);
}
?>
<div class="flex between middle mb-3 wrap gap-2">
  <div class="flex wrap middle gap-2">
    <span class="muted small"><?= count($items) ?> <?= count($items) === 1 ? 'medicine' : 'medicines' ?></span>

    <?php if ($filters['category'] !== null): ?>
      <span class="ps-chip"><?= e(CustomerMedicine::categoryName($filters['category'])) ?>
        <a href="<?= e($filterUrl(['category' => null])) ?>" aria-label="Remove category filter"><?= icon('x') ?></a>
      </span>
    <?php endif; ?>

    <?php foreach ($filters['brand'] as $b): ?>
      <span class="ps-chip"><?= e($b) ?>
        <a href="<?= e($filterUrl(['brand' => array_values(array_diff($filters['brand'], [$b]))])) ?>" aria-label="Remove <?= e($b) ?> filter"><?= icon('x') ?></a>
      </span>
    <?php endforeach; ?>

    <?php if ($priceChip !== null): ?>
      <span class="ps-chip"><?= e($priceChip) ?>
        <a href="<?= e($filterUrl(['min_price' => null, 'max_price' => null])) ?>" aria-label="Remove price filter"><?= icon('x') ?></a>
      </span>
    <?php endif; ?>

    <?php if ($hasFilters): ?>
      <a href="<?= e($filterUrl(['category' => null, 'brand' => null, 'min_price' => null, 'max_price' => null])) ?>" class="small">Clear all</a>
    <?php endif; ?>
  </div>

  <form method="GET" action="<?= e($formAction) ?>" class="flex middle gap-2">
    <?php // Every other filter rides along, so changing the sort keeps them. ?>
    <?php foreach ($baseQuery as $k => $v): ?>
      <input type="hidden" name="<?= e($k) ?>" value="<?= e($v) ?>">
    <?php endforeach; ?>
    <?php if ($filters['category'] !== null): ?>
      <input type="hidden" name="category" value="<?= (int) $filters['category'] ?>">
    <?php endif; ?>
    <?php foreach ($filters['brand'] as $b): ?>
      <input type="hidden" name="brand[]" value="<?= e($b) ?>">
    <?php endforeach; ?>
    <?php if ($filters['min_price'] !== null): ?>
      <input type="hidden" name="min_price" value="<?= e($filters['min_price']) ?>">
    <?php endif; ?>
    <?php if ($filters['max_price'] !== null): ?>
      <input type="hidden" name="max_price" value="<?= e($filters['max_price']) ?>">
    <?php endif; ?>

    <label class="small muted mb-0" for="psSort">Sort by:</label>
    <select name="sort" id="psSort" class="field field-sm" style="width:auto;" onchange="this.form.submit()">
      <option value="relevance" <?= $filters['sort'] === 'relevance' ? 'selected' : '' ?>>Default</option>
      <option value="price_asc" <?= $filters['sort'] === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
      <option value="price_desc" <?= $filters['sort'] === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
      <option value="name" <?= $filters['sort'] === 'name' ? 'selected' : '' ?>>Name (A-Z)</option>
    </select>
    <noscript><button type="submit" class="btn btn-ps-outline btn-sm">Sort</button></noscript>
  </form>
</div>
