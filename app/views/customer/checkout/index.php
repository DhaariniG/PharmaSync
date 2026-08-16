<h4 class="bold mb-4">Checkout</h4>

<?php if (!empty($error)): ?>
  <div class="note note-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/checkout/place-order" data-validate novalidate>
          <?= csrf_field() ?>
  <div class="row g-4">
    <div class="col-lg-8">

      <?php if (!$hasRxItem): ?>
        <!-- OTC order: the customer picks the patient. For a prescription
             order this is skipped, because the prescription already names
             the patient and the controller takes it from there. -->
        <div class="ps-card p-4 mb-3">
          <h6 class="bold mb-3"><?= icon('users', 'me-2') ?>Who is this order for?</h6>
          <?php if (empty($familyMembers)): ?>
            <div class="note note-warn small mb-2">You haven't set up any family profiles yet.</div>
            <a href="<?= BASE_URL ?>/profile" class="btn btn-ps-outline btn-sm">Add a family profile</a>
          <?php else: ?>
            <select name="patient_id" class="field" required>
              <?php foreach ($familyMembers as $i => $member): ?>
                <option value="<?= (int) $member['id'] ?>" <?= $member['relationship'] === 'Self' ? 'selected' : '' ?>>
                  <?= htmlspecialchars($patients->label($user['id'], $member['id'])) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="muted small mt-2">This is shown on your order history so you can tell your family's orders apart. Manage profiles from <a href="<?= BASE_URL ?>/profile">your profile</a>.</div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="ps-card p-4 mb-3">
        <h6 class="bold mb-3">How would you like your medicines?</h6>
        <div class="row g-2">
          <div class="col-6">
            <input type="radio" class="radio-btn" name="delivery_method" id="mHome" value="delivery" checked>
            <label class="btn btn-ps-outline w-100 py-3" for="mHome"><?= icon('truck', 'block size-4 mb-1') ?>Home Delivery</label>
          </div>
          <div class="col-6">
            <input type="radio" class="radio-btn" name="delivery_method" id="mPickup" value="pickup">
            <label class="btn btn-ps-outline w-100 py-3" for="mPickup"><?= icon('store', 'block size-4 mb-1') ?>Store Pickup</label>
          </div>
        </div>
      </div>

      <!-- Home delivery: address + notes -->
      <div class="ps-card p-4 mb-3" id="deliveryBlock">
        <div class="flex between middle mb-3">
          <h6 class="bold mb-0"><?= icon('map-pin', 'me-2') ?>Delivery Address</h6>
        </div>
        <?php foreach ($addresses as $i => $addr): ?>
          <label class="block ps-card p-3 mb-2" style="cursor:pointer;">
            <div class="flex between top">
              <div class="check-row">
                <input class="check-box" type="radio" name="address_id" value="<?= $addr['id'] ?>" <?= $i === 0 ? 'checked' : '' ?>>
                <span class="semibold ms-1"><?= htmlspecialchars($addr['label']) ?></span>
                <?php if ($addr['is_default']): ?><span class="tag ps-badge-otc ms-1">Default</span><?php endif; ?>
                <div class="muted small mt-1"><?= htmlspecialchars($addr['line1']) ?>, <?= htmlspecialchars($addr['city']) ?></div>
                <div class="muted small">Phone: <?= htmlspecialchars($addr['phone']) ?></div>
              </div>
            </div>
          </label>
        <?php endforeach; ?>
        <textarea name="address_notes" class="field mt-2" rows="2" placeholder="Additional delivery notes (optional)"></textarea>
        <div class="muted small mt-2"><?= icon('truck', 'me-1') ?>Estimated arrival: <strong>tomorrow by 6:00 PM</strong>.</div>
      </div>

      <!-- Store pickup: just choose a slot (slots built by pickup_slots()) -->
      <div class="ps-card p-4 mb-3 hidden" id="pickupBlock">
        <h6 class="bold mb-3"><?= icon('store', 'me-2') ?>Collect from Store</h6>

        <div class="ps-card p-3 mb-3" style="background: var(--ps-bg);">
          <div class="semibold"><?= htmlspecialchars(STORE_NAME) ?></div>
          <div class="muted small mt-1"><?= icon('map-pin', 'me-1') ?><?= htmlspecialchars(STORE_ADDRESS) ?></div>
          <div class="muted small"><?= icon('phone', 'me-1') ?><?= htmlspecialchars(STORE_PHONE) ?></div>
          <div class="muted small"><?= icon('clock', 'me-1') ?><?= htmlspecialchars(STORE_HOURS) ?></div>
        </div>

        <label class="field-label small semibold" for="pickupSlot">Pickup date &amp; time</label>
        <select class="field" id="pickupSlot" name="pickup_slot">
          <?php foreach (pickup_slots() as $slot): ?>
            <option value="<?= htmlspecialchars($slot['value']) ?>"><?= htmlspecialchars($slot['label']) ?></option>
          <?php endforeach; ?>
        </select>

        <div class="note note-plain border small mt-3 mb-0">
          <div class="mb-1"><?= icon('clock', 'text-primary me-1') ?>Same-day orders are ready about <strong><?= (int) STORE_PICKUP_PREP_HOURS ?> hours</strong> after ordering.</div>
          <div><?= icon('id-card', 'text-primary me-1') ?>Please <strong>bring a valid photo ID</strong> when collecting<?= $hasRxItem ? ' — it is required to hand over prescription medicines.' : '.' ?></div>
        </div>
      </div>

      <?php if ($hasRxItem): ?>
        <div class="ps-card p-4 mb-3">
          <h6 class="bold mb-3"><?= icon('scroll-text', 'me-2') ?>Prescription</h6>
          <p class="muted small">Your cart includes prescription medicine. Select an approved prescription to continue.</p>

          <?php if (empty($approvedRx)): ?>
            <div class="note note-warn small mb-2">You don't have an approved prescription yet.</div>
            <a href="<?= BASE_URL ?>/prescription/upload" class="btn btn-ps-outline btn-sm">Upload Prescription</a>
          <?php else: ?>
            <?php if (!empty($preselectedPrescriptionId)): ?>
              <div class="note note-ok small mb-2"><?= icon('circle-check', 'me-1') ?>We've pre-selected the approved prescription used for this reorder — change it below if needed.</div>
            <?php endif; ?>
            <?php foreach ($approvedRx as $rx): ?>
              <div class="check-row mb-2">
                <input class="check-box" type="radio" name="prescription_id" value="<?= $rx['id'] ?>" id="rx<?= $rx['id'] ?>" required <?= (int) $rx['id'] === (int) ($preselectedPrescriptionId ?? 0) ? 'checked' : '' ?>>
                <label class="check-text" for="rx<?= $rx['id'] ?>">
                  <?= htmlspecialchars($rx['file_name']) ?>
                  <span class="muted">— for <?= htmlspecialchars($patients->label($user['id'], $rx['patient_id'] ?? null)) ?>,
                  approved <?= date('d M Y', strtotime($rx['uploaded_at'])) ?></span>
                </label>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="ps-card p-4">
        <h6 class="bold mb-3"><?= icon('credit-card', 'me-2') ?>Payment Method</h6>

        <label class="block ps-card p-3 mb-2" style="cursor:pointer;">
          <input class="check-box me-2" type="radio" name="payment_method" value="card" id="payCard" checked onclick="document.getElementById('cardFields').classList.remove('hidden')">
          <span class="semibold">Credit / Debit Card</span>
          <div id="cardFields" class="mt-3">
            <input type="text" class="field mb-2" placeholder="Card Number: XXXX XXXX XXXX XXXX">
            <div class="row g-2">
              <div class="col-6"><input type="text" class="field" placeholder="Expiry Date MM/YY"></div>
              <div class="col-6"><input type="text" class="field" placeholder="CVV"></div>
            </div>
          </div>
        </label>

        <label class="block ps-card p-3 mb-2" style="cursor:pointer;" onclick="document.getElementById('cardFields').classList.add('hidden')">
          <input class="check-box me-2" type="radio" name="payment_method" value="bank_transfer" id="payBank">
          <span class="semibold">Bank Transfer (SLIPS/LANKA QR)</span>
        </label>

        <label class="block ps-card p-3" style="cursor:pointer;" onclick="document.getElementById('cardFields').classList.add('hidden')">
          <input class="check-box me-2" type="radio" name="payment_method" value="cod" id="payCod">
          <!-- Label/hint swapped by JS for delivery vs pickup -->
          <span class="semibold" id="codLabel">Cash on Delivery</span>
          <div class="muted small ms-4" id="codHint">Pay in cash when your order is delivered.</div>
        </label>
      </div>

    </div>

    <div class="col-lg-4">
      <div class="ps-card p-4">
        <h6 class="bold mb-3">Order Summary</h6>
        <?php foreach ($items as $line): ?>
          <div class="flex between small mb-2">
            <span><?= htmlspecialchars($line['medicine']['name']) ?> &times; <?= $line['quantity'] ?></span>
            <span>Rs. <?= number_format($line['subtotal'], 2) ?></span>
          </div>
        <?php endforeach; ?>
        <hr>
        <div class="flex between small mb-2">
          <span class="muted">Subtotal</span><span>Rs. <?= number_format($subtotal, 2) ?></span>
        </div>
        <?php if (!empty($discount)): ?>
          <div class="flex between small mb-2">
            <span class="muted">Promo discount</span>
            <span class="text-success">- Rs. <?= number_format($discount, 2) ?></span>
          </div>
        <?php endif; ?>
        <div class="flex between small mb-2">
          <span class="muted" id="psFeeLabel">Delivery Fee</span><span id="psDeliveryFee">Rs. <?= number_format($delivery, 2) ?></span>
        </div>
        <div class="flex between small mb-2">
          <span class="muted">Estimated Tax (2%)</span><span>Rs. <?= number_format($tax, 2) ?></span>
        </div>
        <hr>
        <div class="flex between bold mb-3">
          <span>Total</span><span id="psOrderTotal">Rs. <?= number_format($orderTotal, 2) ?></span>
        </div>
        <button type="submit" class="btn btn-ps-primary w-100 py-2" id="psPlaceOrderBtn"><?= icon('lock', 'me-2') ?>Place Order</button>
        <p class="text-center muted small mt-2 mb-0">Secure 256-bit SSL Encrypted Payment</p>
      </div>
    </div>
  </div>
</form>

<?php if (!empty($pendingRx)): ?>
  <!-- Warns (doesn't block) when a prescription is still under review -->
  <!-- Opens on load (starts with the "open" class) -->
  <div class="ps-modal open" id="pendingRxModal">
    <div class="ps-modal-box" style="max-width:520px;">
        <div class="flex between top mb-2">
          <h5 class="flex middle gap-2 mb-0">
            <?= icon('clock', 'text-warning') ?>
            Prescription still under review
          </h5>
          <button type="button" class="ps-modal-x" data-close aria-label="Close">&times;</button>
        </div>
        <div>
          <p class="small mb-2">
            You have <strong><?= count($pendingRx) ?></strong> prescription<?= count($pendingRx) > 1 ? 's' : '' ?>
            still being reviewed by a pharmacist:
          </p>
          <ul class="small muted mb-3">
            <?php foreach ($pendingRx as $rx): ?>
              <li>
                #PR-<?= (int) $rx['id'] ?> &mdash; <?= htmlspecialchars($rx['file_name']) ?>
                <span class="tag ps-status ps-status-<?= htmlspecialchars($rx['status']) ?>" style="font-size:.6rem;"><?= strtoupper(str_replace('_', ' ', $rx['status'])) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
          <p class="small mb-0">
            If you place this order now, those prescription items <strong>won't be included</strong> &mdash;
            they'll need a separate order once the pharmacist finishes reviewing. You can also wait and
            check out everything together later.
          </p>
        </div>
        <div class="flex right gap-2 mt-3">
          <a href="<?= BASE_URL ?>/prescription/status/<?= (int) $pendingRx[0]['id'] ?>" class="btn btn-ps-outline btn-sm">View Prescription</a>
          <button type="button" class="btn btn-ps-primary btn-sm" data-close>Continue Anyway</button>
        </div>
    </div>
  </div>
<?php endif; ?>

<script>
  // When the delivery method changes, show the matching panel and update the
  // order summary (pickup is free and relabels the cash option).
  var deliveryFee = <?= json_encode(round((float) $delivery, 2)) ?>;
  var orderBase = <?= json_encode(round((float) $orderTotal - (float) $delivery, 2)) ?>;

  function updateFulfilment() {
    var pickup = document.getElementById('mPickup').checked;

    document.getElementById('deliveryBlock').classList.toggle('hidden', pickup);
    document.getElementById('pickupBlock').classList.toggle('hidden', !pickup);

    document.getElementById('psFeeLabel').textContent = pickup ? 'Store Pickup' : 'Delivery Fee';
    document.getElementById('psDeliveryFee').textContent = pickup ? 'Free' : 'Rs. ' + deliveryFee.toFixed(2);
    document.getElementById('psOrderTotal').textContent = 'Rs. ' + (orderBase + (pickup ? 0 : deliveryFee)).toFixed(2);

    document.getElementById('codLabel').textContent = pickup ? 'Over the Counter' : 'Cash on Delivery';
    document.getElementById('codHint').textContent = pickup
      ? 'Pay in cash or by card at the counter when you collect.'
      : 'Pay in cash when your order is delivered.';
  }

  document.getElementById('mHome').addEventListener('change', updateFulfilment);
  document.getElementById('mPickup').addEventListener('change', updateFulfilment);
  updateFulfilment();
</script>
