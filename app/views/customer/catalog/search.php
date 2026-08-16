<nav aria-label="breadcrumb">
  <ol class="crumbs small">
    <li class="crumb"><a href="<?= BASE_URL ?>/">Home</a></li>
    <li class="crumb active">Search Results</li>
  </ol>
</nav>

<div class="flex between middle mb-4 wrap gap-2">
  <h4 class="bold mb-0">Search Results for '<?= htmlspecialchars($query) ?>' <span class="muted normal size-6"><?= count($items) ?> results found</span></h4>
</div>

<div class="row g-4">
  <div class="col-lg-3">
    <div class="ps-card p-3">
      <?php
        $formAction = BASE_URL . '/search';
        $hiddenFields = ['q' => $query];
        require __DIR__ . '/../partials/catalog-filters.php';
      ?>
    </div>
  </div>

  <div class="col-lg-9">
    <?php if (!empty($items)): ?>
      <div class="row g-3 mb-4">
        <?php foreach ($items as $m): ?>
          <div class="col-6 col-lg-4">
            <?php require __DIR__ . '/../partials/medicine-card.php'; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="ps-empty-state ps-card mb-4">
        <?= icon('search') ?>
        <h5 class="mt-3">No results found for '<?= htmlspecialchars($query) ?>'</h5>
        <p class="muted">Not finding what you need? Our pharmacists can help you find alternatives or check other stock.</p>
        <div class="flex gap-2 center mt-2">
          <a href="mailto:support@pharmasync.test" class="btn btn-ps-primary">Chat with Pharmacist</a>
          <a href="<?= BASE_URL ?>/prescription/upload" class="btn btn-ps-outline">Upload Prescription</a>
        </div>
      </div>
    <?php endif; ?>

    <h6 class="bold mb-3">Suggested Alternatives</h6>
    <div class="row g-3">
      <?php foreach ($suggested as $m): ?>
        <div class="col-6 col-lg-3">
          <?php require __DIR__ . '/../partials/medicine-card.php'; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
