<?php
/*
 * Catalog filter sidebar - used by the catalog page and the search page.
 * Include partials/catalog-url.php first (it gives $filterUrl).
 *
 * Expects: $formAction, $filters, $filterUrl, $categories, $categoryCounts,
 *          $brands, $priceRange. Optional: $baseQuery, $allLabel.
 */
$baseQuery = $baseQuery ?? [];
$allLabel  = $allLabel ?? 'All Medicines';

// 150.0 -> "150", 99.5 -> "99.5"
$priceText = fn($v) => $v === null ? '' : rtrim(rtrim(number_format((float) $v, 2, '.', ''), '0'), '.');
?>
<div class="ps-filter-group">
  <div class="title">Categories</div>
  <a href="<?= e($filterUrl(['category' => null])) ?>" class="block nounderline mb-1 <?= $filters['category'] === null ? 'bold text-dark' : 'muted' ?>">
    <?= e($allLabel) ?> <span class="muted">(<?= array_sum($categoryCounts) ?>)</span>
  </a>
  <?php foreach ($categories as $id => $name): ?>
    <a href="<?= e($filterUrl(['category' => $id])) ?>" class="block nounderline mb-1 <?= $filters['category'] === $id ? 'bold text-dark' : 'muted' ?>">
      <?= e($name) ?> <span class="muted">(<?= (int) ($categoryCounts[$id] ?? 0) ?>)</span>
    </a>
  <?php endforeach; ?>
</div>

<form method="GET" action="<?= e($formAction) ?>">
  <?php // Keep what this form does not show: the search text, category and sort. ?>
  <?php foreach ($baseQuery as $k => $v): ?>
    <input type="hidden" name="<?= e($k) ?>" value="<?= e($v) ?>">
  <?php endforeach; ?>
  <?php if ($filters['category'] !== null): ?>
    <input type="hidden" name="category" value="<?= (int) $filters['category'] ?>">
  <?php endif; ?>
  <?php if ($filters['sort'] !== 'relevance'): ?>
    <input type="hidden" name="sort" value="<?= e($filters['sort']) ?>">
  <?php endif; ?>

  <div class="ps-filter-group">
    <div class="title">Price Range (Rs.)</div>
    <div class="flex gap-2">
      <input type="number" name="min_price" min="0" step="any" value="<?= e($priceText($filters['min_price'])) ?>" class="field field-sm" placeholder="Min <?= (int) $priceRange['min'] ?>" aria-label="Minimum price">
      <input type="number" name="max_price" min="0" step="any" value="<?= e($priceText($filters['max_price'])) ?>" class="field field-sm" placeholder="Max <?= (int) $priceRange['max'] ?>" aria-label="Maximum price">
    </div>
  </div>

  <div class="ps-filter-group">
    <div class="title">Brands</div>
    <?php foreach ($brands as $brand): $brandId = 'brand' . md5($brand); ?>
      <div class="check-row">
        <input class="check-box" type="checkbox" name="brand[]" value="<?= e($brand) ?>" id="<?= $brandId ?>" <?= in_array($brand, $filters['brand'], true) ? 'checked' : '' ?>>
        <label class="check-text small" for="<?= $brandId ?>"><?= e($brand) ?></label>
      </div>
    <?php endforeach; ?>
  </div>

  <button type="submit" class="btn btn-ps-primary btn-sm w-100 mt-3">Apply Filters</button>
  <?php if ($hasFilters): ?>
    <a href="<?= e($filterUrl(['category' => null, 'brand' => null, 'min_price' => null, 'max_price' => null])) ?>" class="btn btn-ps-outline btn-sm w-100 mt-2">Clear Filters</a>
  <?php endif; ?>
</form>
