<?php // expects $m (a medicine array) in scope ?>
<div class="ps-card ps-medicine-card h-100 flex flex-col">
  <img src="<?= medicine_image($m) ?>" class="w-100" style="height:160px;object-fit:cover;" alt="<?= htmlspecialchars($m['name']) ?>" onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/medicines/_placeholder.svg';">
  <div class="p-3 flex flex-col grow">
    <div class="flex between top mb-1">
      <span class="tag <?= $m['requires_rx'] ? 'ps-badge-rx' : 'ps-badge-otc' ?>">
        <?= $m['requires_rx'] ? 'Rx Required' : 'OTC' ?>
      </span>
      <?php if ($m['stock'] <= 0): ?>
        <span class="tag bg-secondary">Out of stock</span>
      <?php endif; ?>
    </div>
    <div class="semibold small mb-1"><?= htmlspecialchars($m['name']) ?></div>
    <div class="muted small mb-2"><?= htmlspecialchars(Medicine::categoryName($m['category_id'])) ?></div>
    <div class="ps-price mb-3"><?= "Rs. " . number_format($m['price'], 2) ?></div>
    <div class="mt-auto flex gap-2">
      <a href="<?= BASE_URL ?>/product/<?= $m['id'] ?>" class="btn btn-ps-outline btn-sm grow">Details</a>
      <?php if ($m['stock'] > 0): ?>
        <?php if (!empty($m['requires_rx'])): ?>
          <a href="<?= BASE_URL ?>/prescription/upload?medicine_id=<?= $m['id'] ?>&quantity=1" class="btn btn-ps-primary btn-sm grow" title="Upload Prescription">
            <?= icon('scroll-text') ?>
          </a>
        <?php else: ?>
          <form method="POST" action="<?= BASE_URL ?>/cart/add" class="grow">
            <?= csrf_field() ?>
            <input type="hidden" name="medicine_id" value="<?= $m['id'] ?>">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn btn-ps-primary btn-sm w-100"><?= icon('shopping-cart') ?></button>
          </form>
        <?php endif; ?>
      <?php else: ?>
        <button type="button" class="btn btn-ps-primary btn-sm grow" data-open="#altModal<?= $m['id'] ?>">
          <?= !empty($m['requires_rx']) ? icon('eye', 'me-1') . 'View Alternatives' : 'Alternates' ?>
        </button>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php
// Out-of-stock medicines open the alternatives pop-up (interactive for OTC,
// view-only for Rx).
if ($m['stock'] <= 0):
  $modalId = 'altModal' . $m['id'];
  $original = $m;
  $alternates = (new Medicine())->alternatesFor($m['id']);
  require __DIR__ . '/alternative-modal.php';
endif; ?>
