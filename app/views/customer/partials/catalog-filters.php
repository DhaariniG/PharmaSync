<?php
// Catalog filter sidebar. Expects $brands, $priceRange, $activeBrands,
// $formAction, $hiddenFields; optional $categories/$categoryCounts/$activeCategory.
$hiddenFields   = $hiddenFields ?? [];
$categoryCounts = $categoryCounts ?? [];
?>
<form method="GET" action="<?= $formAction ?>">
  <?php foreach ($hiddenFields as $k => $v): ?>
    <input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>">
  <?php endforeach; ?>

  <?php if (isset($categories)): ?>
    <div class="ps-filter-group">
      <div class="title">Categories</div>
      <a href="<?= $formAction ?>" class="block nounderline mb-1 <?= !($activeCategory ?? null) ? 'bold text-dark' : 'muted' ?>">
        All Medicines <span class="muted">(<?= array_sum($categoryCounts) ?>)</span>
      </a>
      <?php foreach ($categories as $id => $name): ?>
        <a href="<?= $formAction ?>?category=<?= $id ?>" class="block nounderline mb-1 <?= ($activeCategory ?? null) === $id ? 'bold text-dark' : 'muted' ?>">
          <?= htmlspecialchars($name) ?> <span class="muted">(<?= $categoryCounts[$id] ?? 0 ?>)</span>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="ps-filter-group">
    <div class="title">Price Range (Rs.)</div>
    <div class="flex gap-2">
      <input type="number" name="min_price" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>" class="field field-sm" placeholder="<?= (int) $priceRange['min'] ?>">
      <input type="number" name="max_price" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>" class="field field-sm" placeholder="<?= (int) $priceRange['max'] ?>">
    </div>
  </div>

  <div class="ps-filter-group">
    <div class="title">Brands</div>
    <?php foreach ($brands as $brand): ?>
      <div class="check-row">
        <input class="check-box" type="checkbox" name="brand[]" value="<?= htmlspecialchars($brand) ?>" id="brand<?= md5($brand) ?>" <?= in_array($brand, $activeBrands, true) ? 'checked' : '' ?>>
        <label class="check-text small" for="brand<?= md5($brand) ?>"><?= htmlspecialchars($brand) ?></label>
      </div>
    <?php endforeach; ?>
  </div>

  <button type="submit" class="btn btn-ps-primary btn-sm w-100 mt-3">Apply Filters</button>
</form>
