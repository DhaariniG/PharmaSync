<div class="ps-empty-state ps-card">
  <?= icon('circle-alert') ?>
  <h5 class="mt-3"><?= htmlspecialchars($message ?? 'Not found') ?></h5>
  <a href="<?= BASE_URL ?>/catalog" class="btn btn-ps-primary mt-2">Back to Catalog</a>
</div>
