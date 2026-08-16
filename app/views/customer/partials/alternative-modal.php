<?php
// "Medicine unavailable — here are alternatives" pop-up.
// Needs: $modalId, $original, $alternates. Optional: $autoShow, $readOnly.
// $readOnly makes it view-only (Rx medicines, where the pharmacist chooses).
$autoShow = $autoShow ?? false;
$readOnly = $readOnly ?? !empty($original['requires_rx']);
// These are unset at the end of the file so a looped include (one per card)
// doesn't carry one card's mode into the next.
?>
<div class="ps-modal<?= $autoShow ? ' open' : '' ?>" id="<?= $modalId ?>">
  <div class="ps-modal-box" style="max-width:720px;">
      <div class="flex between top mb-2">
        <h5 class="flex middle gap-2 mb-0">
          <?= icon('circle-alert', 'text-danger') ?>
          Medicine Currently Unavailable
        </h5>
        <button type="button" class="ps-modal-x" data-close aria-label="Close">&times;</button>
      </div>
      <div class="pt-1">
        <p class="muted mb-4">
          <strong><?= htmlspecialchars($original['name']) ?></strong> is out of stock.
          <?php if ($readOnly): ?>
            This is a prescription medicine, so a pharmacist will choose a suitable alternative for you if needed — you'll be notified to approve it. For your reference, here are the in-stock alternatives our pharmacists may consider.
          <?php else: ?>
            Our pharmacist recommends these safe, in-stock alternatives.
          <?php endif; ?>
        </p>

        <?php if (empty($alternates)): ?>
          <div class="ps-empty-state">
            <?= icon('package-open') ?>
            <p class="muted mb-0 mt-2">No alternatives available right now — please contact a pharmacist.</p>
          </div>
        <?php else: ?>
          <div class="row g-3">
            <?php foreach ($alternates as $alt): ?>
              <div class="col-md-4">
                <div class="ps-card h-100 p-3 flex flex-col">
                  <img src="<?= medicine_image($alt) ?>" class="w-100 rounded mb-2" style="height:110px;object-fit:cover;" alt="<?= htmlspecialchars($alt['name']) ?>" onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/medicines/_placeholder.svg';">
                  <span class="tag ps-badge-otc mb-2 self-top">In Stock</span>
                  <div class="semibold small"><?= htmlspecialchars($alt['name']) ?></div>
                  <div class="muted small mb-2"><?= htmlspecialchars($alt['manufacturer']) ?></div>
                  <div class="ps-price mb-3">Rs. <?= number_format($alt['price'], 2) ?></div>
                  <?php if ($readOnly): ?>
                    <!-- Rx: view-only. The pharmacist picks the substitute during
                         prescription review, so no add-to-cart here. -->
                    <a href="<?= BASE_URL ?>/product/<?= $alt['id'] ?>" class="btn btn-ps-outline btn-sm w-100 mt-auto"><?= icon('eye', 'me-1') ?>View Details</a>
                  <?php else: ?>
                    <!-- OTC: self-service. Customer can inspect the alternative or
                         add it straight to the cart, no pharmacist involved. -->
                    <div class="mt-auto flex gap-2">
                      <a href="<?= BASE_URL ?>/product/<?= $alt['id'] ?>" class="btn btn-ps-outline btn-sm grow px-1"><?= icon('eye', 'me-1') ?>View</a>
                      <form method="POST" action="<?= BASE_URL ?>/cart/add" class="grow">
          <?= csrf_field() ?>
                        <input type="hidden" name="medicine_id" value="<?= $alt['id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-ps-primary btn-sm w-100 px-1"><?= icon('shopping-cart', 'me-1') ?>Add to Cart</button>
                      </form>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="flex between middle wrap gap-2 mt-3 pt-3" style="border-top:1px solid var(--ps-border);">
        <span class="muted small"><?= icon('shield-plus', 'me-1') ?>All recommendations are validated by clinical standards.</span>
        <div class="flex gap-2">
          <?php if ($readOnly): ?>
            <a href="<?= BASE_URL ?>/prescription/upload?medicine_id=<?= $original['id'] ?>" class="btn btn-ps-primary btn-sm">Request with Prescription</a>
          <?php else: ?>
            <button type="button" class="btn btn-ps-outline btn-sm" data-close>Continue Waiting</button>
          <?php endif; ?>
          <a href="mailto:support@pharmasync.test" class="btn btn-text btn-sm">Contact Pharmacist</a>
        </div>
      </div>
  </div>
</div>
<?php
// Clear the per-include mode flags so the NEXT card in a catalog loop derives
// its own mode from its own $original, instead of inheriting this card's.
unset($readOnly, $autoShow);
?>
