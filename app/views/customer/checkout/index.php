<h4 class="bold mb-4">Checkout</h4>

<?php if (!empty($error)): ?>
  <div class="note note-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($cartProblems)): ?>
  <!-- Stock or limits changed since these went into the cart. -->
  <div class="note note-danger">
    <strong>Your cart needs a change before you can order:</strong>
    <ul class="mb-1 mt-1">
      <?php foreach ($cartProblems as $reason): ?><li><?= e($reason) ?></li><?php endforeach; ?>
    </ul>
    <a href="<?= BASE_URL ?>/customer/cart">Go back to your cart</a>
  </div>
<?php endif; ?>

<?php if (!empty($promoProblem)): ?>
  <div class="note note-warn small">Your promo code isn't applied: <?= e($promoProblem) ?></div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/customer/checkout/place-order" id="checkoutForm" data-validate novalidate>
          <?= csrf_field() ?>
  <!-- The discount on screen. If it changed before "Place Order" (promo used
       in another tab...), the controller stops and shows the new total. -->
  <input type="hidden" name="expected_discount" value="<?= number_format((float) $discount, 2, '.', '') ?>">
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
            <a href="<?= BASE_URL ?>/customer/profile" class="btn btn-ps-outline btn-sm">Add a family profile</a>
          <?php else: ?>
            <select name="patient_id" id="patientSelect" class="field" required>
              <?php foreach ($familyMembers as $i => $member): ?>
                <option value="<?= (int) $member['id'] ?>" <?= $member['relationship'] === 'Self' ? 'selected' : '' ?>>
                  <?= htmlspecialchars($patients->label($user['id'], $member['id'])) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="muted small mt-2">This is shown on your order history so you can tell your family's orders apart. Manage profiles from <a href="<?= BASE_URL ?>/customer/profile">your profile</a>.</div>
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

      <!-- Home delivery: address + notes, then the date and time window.
           The whole wrapper hides when Store Pickup is chosen. -->
      <div id="deliveryBlock">
      <div class="ps-card p-4 mb-3">
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
      </div>

      <!-- Delivery date & time. Windows come from DeliverySlot::schedule();
           full, closed and too-late windows are shown but cannot be ticked. -->
      <div class="ps-card p-4 mb-3" id="slotCard">
        <h6 class="bold mb-1"><?= icon('calendar', 'me-2') ?>Delivery Date &amp; Time</h6>
        <p class="muted small mb-3">Choose when you want your order to arrive. Our rider comes within the window you pick.</p>

        <?php if ($selectedSlot === null): ?>
          <div class="note note-warn small mb-0">Every delivery time for the next <?= (int) DeliverySlot::DAYS_SHOWN ?> days is fully booked. Please choose <strong>Store Pickup</strong>, or try again later.</div>
        <?php else: ?>
          <?php require __DIR__ . '/../partials/delivery-slot-picker.php'; ?>
        <?php endif; ?>

        <div class="note note-danger small mt-2 mb-0 hidden" id="slotError" role="alert">Please choose a delivery date and time.</div>
        <div class="muted small mt-2"><?= icon('clock', 'me-1') ?>Times close <?= (int) $leadHours ?> hours before they start, so we have time to pack and send your order<?= $hasRxItem ? ' and a pharmacist can check the prescription items' : '' ?>.</div>
      </div>
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
          <p class="muted small">Your cart includes prescription medicine. Choose the approved prescription it comes from. Only prescriptions that still cover every prescription item in your cart are listed.</p>

          <?php if (empty($approvedRx)): ?>
            <div class="note note-warn small mb-2">None of your approved prescriptions covers the prescription items in your cart.</div>
            <?php if (!empty($rxShortfall)): ?>
              <table class="ps-cover-table small w-100 mb-2">
                <thead><tr><th>Medicine</th><th>In cart</th><th>Your prescriptions allow</th></tr></thead>
                <tbody>
                  <?php foreach ($rxShortfall as $row): ?>
                    <tr>
                      <td class="semibold"><?= e($row['medicine']['name']) ?></td>
                      <td><?= (int) $row['in_cart'] ?></td>
                      <td class="<?= $row['allowed'] < $row['in_cart'] ? 'text-danger semibold' : '' ?>"><?= (int) $row['allowed'] ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <p class="muted small">One order uses one prescription. If your items come from two prescriptions, order them separately.</p>
            <?php endif; ?>
            <div class="flex gap-2 wrap">
              <a href="<?= BASE_URL ?>/customer/cart" class="btn btn-ps-outline btn-sm">Edit Cart</a>
              <a href="<?= BASE_URL ?>/customer/prescription/upload" class="btn btn-ps-outline btn-sm">Upload Prescription</a>
            </div>
          <?php else: ?>
            <?php if (!empty($preselectedPrescriptionId) && count($approvedRx) > 1): ?>
              <div class="note note-ok small mb-2"><?= icon('circle-check', 'me-1') ?>We've pre-selected the prescription used for this reorder — change it below if needed.</div>
            <?php endif; ?>
            <?php foreach ($approvedRx as $rx): ?>
              <div class="check-row mb-2">
                <input class="check-box" type="radio" name="prescription_id" value="<?= $rx['id'] ?>" id="rx<?= $rx['id'] ?>" data-patient="<?= (int) ($rx['patient_id'] ?? 0) ?>" required <?= (int) $rx['id'] === (int) ($preselectedPrescriptionId ?? 0) ? 'checked' : '' ?>>
                <label class="check-text" for="rx<?= $rx['id'] ?>">
                  <?= htmlspecialchars($rx['file_name']) ?>
                  <span class="muted">— for <?= htmlspecialchars($patients->label($user['id'], $rx['patient_id'] ?? null)) ?>,
                  approved <?= date('d M Y', strtotime($rx['uploaded_at'])) ?></span>
                </label>
              </div>
            <?php endforeach; ?>

            <?php if ($hasOtcItem): ?>
              <!-- One order has one patient: the prescription's. -->
              <div class="note note-warn small mt-2 mb-0">
                <?= icon('info', 'me-1') ?>Everything in this order, including the over-the-counter items, will be recorded as for the person on the prescription. To buy those items for someone else, remove them and place a separate order.
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($allergyWarnings)): ?>
        <!-- Allergy check. One block per family member with a match; the
             script below shows the block for the person this order is for. -->
        <div class="ps-card p-4 mb-3 hidden" id="allergyCard">
          <h6 class="bold mb-2 text-danger"><?= icon('circle-alert', 'me-2') ?>Allergy warning</h6>
          <?php foreach ($allergyWarnings as $memberId => $warnings): ?>
            <div class="hidden" data-allergy-for="<?= (int) $memberId ?>">
              <p class="small mb-2"><strong><?= e($patients->label($user['id'], $memberId)) ?></strong> has an allergy on their profile that may be linked to:</p>
              <ul class="small mb-2">
                <?php foreach ($warnings as $w): ?><li><?= e($w) ?></li><?php endforeach; ?>
              </ul>
            </div>
          <?php endforeach; ?>
          <p class="muted small">This is based on the medicine's ingredient and drug group. Check with your doctor or ask our pharmacist before using it. Your answer is shown to the pharmacist who checks this order.</p>
          <div class="check-row">
            <input class="check-box" type="checkbox" name="allergy_ack" value="1" id="allergyAck">
            <label class="check-text small" for="allergyAck">I have read this warning and still want to place the order.</label>
          </div>
        </div>
      <?php endif; ?>

      <div class="ps-card p-4">
        <h6 class="bold mb-3"><?= icon('credit-card', 'me-2') ?>Payment Method</h6>

        <label class="block ps-card p-3 mb-2" style="cursor:pointer;">
          <input class="check-box me-2" type="radio" name="payment_method" value="card" id="payCard" checked onclick="document.getElementById('cardFields').classList.remove('hidden')">
          <span class="semibold">Credit / Debit Card</span>
          <!-- The card inputs have no name on purpose: the browser checks them
               (see the script below) and they are never sent to our server.
               A real payment gateway takes over this part later. -->
          <div id="cardFields" class="mt-3">
            <input type="text" class="field mb-2" id="cardNumber" inputmode="numeric" autocomplete="cc-number" placeholder="Card Number: XXXX XXXX XXXX XXXX" aria-label="Card number">
            <div class="row g-2">
              <div class="col-6"><input type="text" class="field" id="cardExpiry" inputmode="numeric" autocomplete="cc-exp" placeholder="Expiry Date MM/YY" aria-label="Expiry date"></div>
              <div class="col-6"><input type="password" class="field" id="cardCvv" inputmode="numeric" autocomplete="cc-csc" maxlength="4" placeholder="CVV" aria-label="CVV"></div>
            </div>
            <div class="note note-danger small mt-2 mb-0 hidden" id="cardError" role="alert"></div>
          </div>
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
          <div class="flex between small mb-2 gap-2">
            <span>
              <?= htmlspecialchars($line['medicine']['name']) ?> &times; <?= e($line['quantity'] . ' ' . CustomerMedicine::unitName($line['medicine'], $line['quantity'])) ?>
              <span class="block muted"><?= e(CustomerMedicine::contentsFor($line['medicine'], $line['quantity'])) ?></span>
            </span>
            <span>Rs. <?= number_format($line['subtotal'], 2) ?></span>
          </div>
        <?php endforeach; ?>
        <div class="flex between small mb-2 gap-2">
          <span class="muted" id="psWhenLabel">Delivery</span>
          <span class="text-end semibold" id="psWhen">&mdash;</span>
        </div>
        <hr>
        <div class="flex between small mb-2">
          <span class="muted">Subtotal</span><span>Rs. <?= number_format($subtotal, 2) ?></span>
        </div>
        <?php if (!empty($discount)): ?>
          <div class="flex between small mb-2">
            <span class="muted">Promo discount <span class="block">(over-the-counter items)</span></span>
            <span class="text-success">- Rs. <?= number_format($discount, 2) ?></span>
          </div>
        <?php endif; ?>
        <div class="flex between small mb-2">
          <span class="muted" id="psFeeLabel">Delivery Fee</span><span id="psDeliveryFee">Rs. <?= number_format($delivery, 2) ?></span>
        </div>
        <div class="flex between small mb-2">
          <span class="muted">Estimated Tax (<?= rtrim(rtrim(number_format(TAX_RATE * 100, 2), '0'), '.') ?>%)</span><span>Rs. <?= number_format($tax, 2) ?></span>
        </div>
        <hr>
        <div class="flex between bold mb-3">
          <span>Total</span><span id="psOrderTotal">Rs. <?= number_format($orderTotal, 2) ?></span>
        </div>
        <button type="submit" class="btn btn-ps-primary w-100 py-2" id="psPlaceOrderBtn"><?= icon('lock', 'me-2') ?>Place Order</button>
        <p class="text-center muted small mt-2 mb-0">Card details are checked on your device and never stored by PharmaSync.</p>
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
          <a href="<?= BASE_URL ?>/customer/prescription/status/<?= (int) $pendingRx[0]['id'] ?>" class="btn btn-ps-outline btn-sm">View Prescription</a>
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

  // ---- Delivery date & time -------------------------------------------
  // Switching days is handled by main.js ([data-slot-picker]). Here: keep
  // the order summary showing what is chosen, and stop the form if home
  // delivery has no window ticked.
  (function () {
    var radios = document.querySelectorAll('input[name="delivery_slot"]');
    var pickupSelect = document.getElementById('pickupSlot');
    var slotError = document.getElementById('slotError');

    function chosenSlot() {
      return document.querySelector('input[name="delivery_slot"]:checked');
    }

    function updateWhen() {
      var pickup = document.getElementById('mPickup').checked;
      var label = document.getElementById('psWhenLabel');
      var when  = document.getElementById('psWhen');
      var slot  = chosenSlot();

      if (pickup) {
        label.textContent = 'Pickup';
        when.textContent = pickupSelect && pickupSelect.selectedIndex >= 0
          ? pickupSelect.options[pickupSelect.selectedIndex].text : '—';
      } else {
        label.textContent = 'Delivery';
        when.textContent = slot ? slot.dataset.label : 'Choose a time';
      }
    }

    radios.forEach(function (r) {
      r.addEventListener('change', function () {
        slotError.classList.add('hidden');
        updateWhen();
      });
    });
    if (pickupSelect) pickupSelect.addEventListener('change', updateWhen);
    document.getElementById('mHome').addEventListener('change', updateWhen);
    document.getElementById('mPickup').addEventListener('change', updateWhen);

    document.getElementById('checkoutForm').addEventListener('submit', function (e) {
      if (!document.getElementById('mPickup').checked && !chosenSlot()) {
        e.preventDefault();
        slotError.classList.remove('hidden');
        document.getElementById('slotCard').scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });

    updateWhen();
  })();

  // ---- Allergy warning ---------------------------------------------------
  // Show the warning for whoever this order is for: the chosen family
  // member, or for a prescription order the prescription's patient.
  (function () {
    var card = document.getElementById('allergyCard');
    if (!card) return;
    var select = document.getElementById('patientSelect');

    function patientId() {
      var rx = document.querySelector('input[name="prescription_id"]:checked');
      if (rx) return rx.dataset.patient;
      return select ? select.value : '';
    }
    function refresh() {
      var id = patientId();
      var any = false;
      card.querySelectorAll('[data-allergy-for]').forEach(function (block) {
        var on = block.dataset.allergyFor === id;
        block.classList.toggle('hidden', !on);
        any = any || on;
      });
      card.classList.toggle('hidden', !any);
    }

    if (select) select.addEventListener('change', refresh);
    document.querySelectorAll('input[name="prescription_id"]').forEach(function (r) {
      r.addEventListener('change', refresh);
    });
    document.getElementById('checkoutForm').addEventListener('submit', function (e) {
      if (!card.classList.contains('hidden') && !document.getElementById('allergyAck').checked) {
        e.preventDefault();
        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        document.getElementById('allergyAck').focus();
      }
    });
    refresh();
  })();

  // ---- Card details --------------------------------------------------------
  // Checked here only. The fields have no name, so nothing typed in them is
  // sent to the server; a payment gateway would take this over.
  (function () {
    var error = document.getElementById('cardError');

    function luhn(digits) {
      var sum = 0, dbl = false;
      for (var i = digits.length - 1; i >= 0; i--) {
        var d = parseInt(digits.charAt(i), 10);
        if (dbl) { d *= 2; if (d > 9) d -= 9; }
        sum += d; dbl = !dbl;
      }
      return sum % 10 === 0;
    }
    function problem() {
      var number = document.getElementById('cardNumber').value.replace(/[\s-]/g, '');
      var expiry = document.getElementById('cardExpiry').value.trim();
      var cvv    = document.getElementById('cardCvv').value.trim();

      if (!/^\d{13,19}$/.test(number) || !luhn(number)) return 'Please enter a valid card number.';
      var m = expiry.match(/^(\d{2})\s*\/\s*(\d{2})$/);
      if (!m || +m[1] < 1 || +m[1] > 12) return 'Please enter the expiry date as MM/YY.';
      var endOfMonth = new Date(2000 + +m[2], +m[1], 0, 23, 59, 59);
      if (endOfMonth < new Date()) return 'This card has expired.';
      if (!/^\d{3,4}$/.test(cvv)) return 'Please enter the 3 or 4 digit CVV.';
      return '';
    }

    // Space the card number in groups of four as it is typed.
    document.getElementById('cardNumber').addEventListener('input', function () {
      var digits = this.value.replace(/\D/g, '').slice(0, 19);
      this.value = digits.replace(/(\d{4})(?=\d)/g, '$1 ');
    });

    document.getElementById('checkoutForm').addEventListener('submit', function (e) {
      if (!document.getElementById('payCard').checked) return;
      var msg = problem();
      error.textContent = msg;
      error.classList.toggle('hidden', msg === '');
      if (msg) {
        e.preventDefault();
        document.getElementById('cardFields').scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  })();
</script>
