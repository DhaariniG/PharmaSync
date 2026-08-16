<h4 class="bold mb-1">Shopping Cart</h4>
<p class="muted mb-4">Manage your prescriptions and healthcare essentials.</p>

<?php if (empty($items)): ?>

  <div class="ps-empty-state ps-card">
    <?= icon('shopping-cart') ?>
    <h5 class="mt-3">Your cart is empty</h5>
    <p class="muted">Browse medicines and health essentials to get started.</p>
    <a href="<?= BASE_URL ?>/catalog" class="btn btn-ps-primary mt-2"><?= icon('briefcase-medical', 'me-2') ?>Browse Medicines</a>
  </div>

<?php else: ?>

  <div class="row g-4">
    <div class="col-lg-8">
      <?php if ($hasRxItem): ?>
        <div class="note note-warn flex middle gap-2">
          <?= icon('scroll-text') ?>
          <div class="small">Your cart contains prescription medicines. You'll be asked to upload a valid prescription at checkout.</div>
        </div>
      <?php endif; ?>

      <div class="ps-card">
        <?php foreach ($items as $line): $m = $line['medicine']; ?>
          <div class="flex middle gap-3 p-3 border-bottom wrap">
            <img src="<?= medicine_image($m) ?>" class="rounded" width="64" height="64" style="object-fit:cover;" alt="<?= htmlspecialchars($m['name']) ?>" onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/medicines/_placeholder.svg';">
            <div class="grow">
              <div class="semibold"><?= htmlspecialchars($m['name']) ?></div>
              <div class="muted small"><?= htmlspecialchars(Medicine::categoryName($m['category_id'])) ?></div>
              <?php if ($m['requires_rx']): ?><span class="tag ps-badge-rx mt-1">Prescription Required</span><?php endif; ?>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/cart/update" class="flex middle border rounded" data-qty-stepper onchange="this.submit()">
          <?= csrf_field() ?>
              <input type="hidden" name="medicine_id" value="<?= $m['id'] ?>">
              <button type="button" class="btn btn-sm" data-qty-minus><?= icon('minus') ?></button>
              <input type="number" name="quantity" value="<?= $line['quantity'] ?>" min="1" max="<?= $m['stock'] ?>" class="field border-0 text-center" style="width:56px;">
              <button type="button" class="btn btn-sm" data-qty-plus><?= icon('plus') ?></button>
            </form>
            <div class="ps-price text-end" style="min-width:100px;">Rs. <?= number_format($line['subtotal'], 2) ?></div>
            <div class="flex flex-col bottom gap-1">
              <form method="POST" action="<?= BASE_URL ?>/cart/save-for-later">
          <?= csrf_field() ?>
                <input type="hidden" name="medicine_id" value="<?= $m['id'] ?>">
                <button type="submit" class="btn btn-text btn-sm p-0 small">Save for later</button>
              </form>
              <form method="POST" action="<?= BASE_URL ?>/cart/remove">
          <?= csrf_field() ?>
                <input type="hidden" name="medicine_id" value="<?= $m['id'] ?>">
                <button type="submit" class="btn btn-sm text-danger p-0"><?= icon('trash-2') ?></button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if (!empty($savedItems)): ?>
        <h6 class="bold mt-4 mb-2">Saved for later</h6>
        <div class="ps-card">
          <?php foreach ($savedItems as $m): ?>
            <div class="flex middle gap-3 p-3 border-bottom">
              <img src="<?= medicine_image($m) ?>" class="rounded" width="48" height="48" style="object-fit:cover;" alt="<?= htmlspecialchars($m['name']) ?>" onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/medicines/_placeholder.svg';">
              <div class="grow">
                <div class="semibold small"><?= htmlspecialchars($m['name']) ?></div>
                <div class="ps-price small">Rs. <?= number_format($m['price'], 2) ?></div>
              </div>
              <form method="POST" action="<?= BASE_URL ?>/cart/move-to-cart">
          <?= csrf_field() ?>
                <input type="hidden" name="medicine_id" value="<?= $m['id'] ?>">
                <button type="submit" class="btn btn-ps-outline btn-sm">Move to Cart</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="col-lg-4">
      <div class="ps-card p-4">
        <h6 class="bold mb-3">Order Summary</h6>
        <div class="flex between mb-2 small">
          <span class="muted">Subtotal</span><span>Rs. <?= number_format($subtotal, 2) ?></span>
        </div>
        <?php if (!empty($discount)): ?>
        <div class="flex between mb-2 small">
          <span class="muted">Discount <?php if (!empty($promoCode)): ?>(<?= htmlspecialchars($promoCode) ?>)<?php endif; ?></span>
          <span class="text-success">- Rs. <?= number_format($discount, 2) ?></span>
        </div>
        <?php endif; ?>
        <div class="flex between mb-2 small">
          <span class="muted">Estimated Tax (2%)</span><span>Rs. <?= number_format($tax, 2) ?></span>
        </div>
        <hr>
        <div class="flex between bold mb-1">
          <span>Total</span>
          <span>Rs. <?= number_format($cartTotal, 2) ?></span>
        </div>
        <p class="muted small mb-3">Delivery charges are not included yet. Choose home delivery or store pickup at checkout.</p>

        <?php if (!empty($promoError)): ?><div class="note note-danger small py-2"><?= htmlspecialchars($promoError) ?></div><?php endif; ?>
        <form method="POST" action="<?= BASE_URL ?>/cart/apply-promo" class="flex gap-2 mb-3">
          <?= csrf_field() ?>
          <input type="text" name="promo_code" value="<?= htmlspecialchars($promoCode ?? '') ?>" class="field field-sm" placeholder="Promo Code (try HEALTH30)">
          <button type="submit" class="btn btn-ps-outline btn-sm">Apply</button>
        </form>

        <div class="note note-plain border small flex middle gap-2 mb-3">
          <?= icon('store', 'text-success') ?>
          <div><strong>Two ways to get your order</strong><br>Home delivery (Rs. <?= number_format(DELIVERY_FEE, 2) ?>) or free store pickup.</div>
        </div>

        <a href="<?= BASE_URL ?>/checkout" class="btn btn-ps-primary w-100 py-2">Proceed to Checkout <?= icon('arrow-right', 'ms-1') ?></a>
        <a href="<?= BASE_URL ?>/catalog" class="btn btn-text w-100 mt-1">Continue shopping</a>
        <p class="text-center muted small mt-2 mb-0"><?= icon('lock', 'me-1') ?>Secure checkout by PharmaSync</p>
      </div>
    </div>
  </div>

<?php endif; ?>

<?php if (!empty($recentlyViewed)): ?>
  <h5 class="ps-section-title mt-5 mb-3">Recently Viewed Medicines</h5>
  <div class="row g-3">
    <?php foreach ($recentlyViewed as $m): ?>
      <div class="col-6 col-md-3">
        <?php require __DIR__ . '/../partials/medicine-card.php'; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
