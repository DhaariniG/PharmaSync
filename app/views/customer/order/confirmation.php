<div class="row center">
  <div class="col-lg-8">
    <div class="ps-card p-4 p-md-5 text-center mb-4">
      <?= icon('circle-check', '', "font-size:3.5rem; color: var(--ps-primary);") ?>
      <h3 class="bold mt-3">Order Successfully Placed!</h3>
      <p class="muted">Thank you for choosing PharmaSync. Your health is our priority, and we're preparing your order for dispatch.</p>
      <hr class="my-4">

      <?php $isPickup = ($order['delivery_method'] ?? 'delivery') === 'pickup'; ?>
      <div class="row text-start g-3 mb-3">
        <div class="col-md-6">
          <div class="muted small upper">Order Number</div>
          <div class="bold">#PS-<?= $order['id'] ?></div>
        </div>
        <div class="col-md-6">
          <div class="muted small upper">Ordered For</div>
          <div class="bold"><?= htmlspecialchars($order['patient_label'] ?? 'Not specified') ?></div>
        </div>
        <?php if ($isPickup): ?>
          <?php
            $pk = $order['pickup'] ?? [];
            $pkWhen = '';
            if (!empty($pk['date'])) {
                $pkWhen = date('D, d M Y', strtotime($pk['date']));
                if (!empty($pk['time'])) {
                    $pkWhen .= ' at ' . date('g:i A', strtotime($pk['time']));
                }
            }
          ?>
          <div class="col-md-6">
            <div class="muted small upper">Pickup Slot</div>
            <div class="bold"><?= $pkWhen !== '' ? htmlspecialchars($pkWhen) : 'To be confirmed' ?></div>
          </div>
          <div class="col-md-6">
            <div class="muted small upper">Collect From</div>
            <div class="bold"><?= htmlspecialchars(STORE_NAME) ?></div>
            <div class="muted small"><?= htmlspecialchars(STORE_ADDRESS) ?></div>
          </div>
        <?php else: ?>
          <div class="col-md-6">
            <div class="muted small upper">Estimated Delivery</div>
            <div class="bold">Tomorrow, by 6:00 PM</div>
          </div>
          <div class="col-md-6">
            <div class="muted small upper">Delivery Address</div>
            <div class="bold"><?= htmlspecialchars($order['address'] ?? 'Not recorded') ?></div>
            <?php if (!empty($order['address_notes'])): ?>
              <div class="muted small"><?= htmlspecialchars($order['address_notes']) ?></div>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <div class="col-md-6">
          <div class="muted small upper">Payment Method</div>
          <div class="bold">
            <?php
              $labels = ['cod' => $isPickup ? 'Over the Counter' : 'Cash on Delivery', 'card' => 'Credit / Debit Card', 'bank_transfer' => 'Bank Transfer'];
              echo $labels[$order['payment_method']] ?? ucfirst($order['payment_method']);
            ?>
          </div>
        </div>
      </div>
      <?php if ($isPickup): ?>
        <div class="note note-plain border small text-start"><?= icon('id-card', 'text-primary me-1') ?>Remember to bring a valid photo ID when collecting your order.</div>
      <?php endif; ?>

      <div class="ps-card p-3 flex between middle mb-4" style="background: var(--ps-bg);">
        <div class="text-start">
          <div class="muted small">Total Paid Amount</div>
          <div class="bold size-4">Rs. <?= number_format($order['total'], 2) ?></div>
        </div>
        <span class="tag ps-badge-otc"><?= icon('check', 'me-1') ?>Verified Order</span>
      </div>

      <div class="flex center gap-2 wrap">
        <a href="<?= BASE_URL ?>/orders/<?= $order['id'] ?>" class="btn btn-ps-primary"><?= icon('truck', 'me-2') ?>Track Order</a>
        <a href="#" class="btn btn-ps-outline"><?= icon('download', 'me-2') ?>Download Invoice</a>
      </div>
      <a href="<?= BASE_URL ?>/catalog" class="block mt-3">Continue Shopping <?= icon('arrow-right', 'ms-1') ?></a>
    </div>

    <h5 class="ps-section-title mb-3">Popular Healthcare Products</h5>
    <div class="row g-3 mb-4">
      <?php foreach ($popular as $m): ?>
        <div class="col-md-4">
          <?php require __DIR__ . '/../partials/medicine-card.php'; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="ps-banner p-4 flex between middle wrap gap-3">
      <div>
        <div class="bold">Need help with your order?</div>
        <div class="small">Our pharmacists are available 24/7 for consultations.</div>
      </div>
      <a href="mailto:support@pharmasync.test" class="btn btn-plain">Chat with Pharmacist</a>
    </div>
  </div>
</div>
