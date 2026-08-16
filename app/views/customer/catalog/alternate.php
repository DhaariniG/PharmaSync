<nav aria-label="breadcrumb">
  <ol class="crumbs small">
    <li class="crumb"><a href="<?= BASE_URL ?>/catalog">Catalog</a></li>
    <li class="crumb"><a href="<?= BASE_URL ?>/product/<?= $original['id'] ?>"><?= htmlspecialchars($original['name']) ?></a></li>
    <li class="crumb active">Alternatives</li>
  </ol>
</nav>

<div class="note note-warn flex middle gap-2">
  <?= icon('triangle-alert') ?>
  <div><strong><?= htmlspecialchars($original['name']) ?></strong> is currently out of stock. Here are some alternatives in the same category.</div>
</div>

<h5 class="ps-section-title mb-3">Suggested alternatives</h5>

<?php if (empty($alternates)): ?>
  <div class="ps-empty-state ps-card">
    <?= icon('package-open') ?>
    <h5 class="mt-3">No alternatives available right now</h5>
    <p class="muted">Check back later, or upload a prescription for pharmacist assistance.</p>
    <a href="<?= BASE_URL ?>/prescription/upload" class="btn btn-ps-primary mt-2">Upload Prescription</a>
  </div>
<?php else: ?>
  <div class="row g-3">
    <?php foreach ($alternates as $m): ?>
      <div class="col-6 col-md-4 col-lg-3">
        <?php require __DIR__ . '/../partials/medicine-card.php'; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
