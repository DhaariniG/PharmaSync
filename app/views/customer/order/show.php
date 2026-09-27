<?php $tracking = $order['tracking'] ?? []; ?>
<nav aria-label="breadcrumb">
  <ol class="crumbs small">
    <li class="crumb"><a href="<?= BASE_URL ?>/customer/orders">My Orders</a></li>
    <li class="crumb active">Order #<?= $order['id'] ?></li>
  </ol>
</nav>

<div class="flex between middle mb-4 wrap gap-2">
  <h4 class="bold mb-0">Order #<?= $order['id'] ?></h4>
  <div class="flex middle gap-2">
    <span class="ps-status ps-status-<?= $order['status'] ?> size-6"><?= Order::statusLabel($order['status']) ?></span>
    <?php if (Order::canChange($order)): ?>
      <!-- Only before the pharmacy starts on it (status 'pending'). -->
      <form method="POST" action="<?= BASE_URL ?>/customer/orders/cancel/<?= $order['id'] ?>" class="inline"
            onsubmit="return confirm('Cancel order #<?= (int) $order['id'] ?>?<?= Order::isPaid($order) ? ' Your card payment will be refunded.' : '' ?>');">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-ps-outline btn-sm"><?= icon('x', 'me-1') ?>Cancel Order</button>
      </form>
    <?php else: ?>
      <form method="POST" action="<?= BASE_URL ?>/customer/orders/reorder/<?= $order['id'] ?>" class="inline">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-ps-primary btn-sm"><?= icon('rotate-cw', 'me-1') ?>Reorder</button>
      </form>
    <?php endif; ?>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="ps-card p-4 mb-3">
      <h6 class="bold mb-3">Items</h6>
      <?php foreach ($order['items'] as $item): ?>
        <div class="flex between small mb-2 pb-2 border-bottom">
          <span>
            <?= htmlspecialchars($item['name']) ?> &times; <?= e($item['quantity'] . ' ' . CustomerMedicine::unitName($item, (int) $item['quantity'])) ?>
            <?php $contents = CustomerMedicine::contentsFor($item, (int) $item['quantity']); ?>
            <?php if ($contents !== ''): ?><span class="muted">(<?= e($contents) ?>)</span><?php endif; ?>
          </span>
          <span>Rs. <?= number_format($item['unit_price'] * $item['quantity'], 2) ?></span>
        </div>
      <?php endforeach; ?>
      <div class="flex between small mt-3"><span class="muted">Subtotal</span><span>Rs. <?= number_format($order['subtotal'], 2) ?></span></div>
      <?php if (!empty($order['discount'])): ?>
        <div class="flex between small"><span class="muted">Promo discount<?= !empty($order['promo_code']) ? ' (' . e($order['promo_code']) . ')' : '' ?></span><span class="text-success">- Rs. <?= number_format($order['discount'], 2) ?></span></div>
      <?php endif; ?>
      <div class="flex between small"><span class="muted">Delivery fee</span><span>Rs. <?= number_format($order['delivery_fee'], 2) ?></span></div>
      <?php if (!empty($order['tax'])): ?>
        <div class="flex between small mb-2"><span class="muted">Tax</span><span>Rs. <?= number_format($order['tax'], 2) ?></span></div>
      <?php endif; ?>
      <div class="flex between bold"><span>Total</span><span>Rs. <?= number_format($order['total'], 2) ?></span></div>
    </div>

    <div class="ps-banner p-4 flex between middle wrap gap-3">
      <div>
        <div class="bold">Need help with an order?</div>
        <div class="small">Our pharmacists can help with dosage or delivery questions during store hours: <?= e(STORE_HOURS) ?>.</div>
      </div>
      <a href="mailto:support@pharmasync.test" class="btn btn-plain">Chat with Pharmacist</a>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="ps-card p-4 mb-3">
      <h6 class="bold mb-3">Real-time Status</h6>
      <?php require __DIR__ . '/../partials/tracking-timeline.php'; ?>
    </div>

    <div class="ps-card p-4">
      <h6 class="bold mb-3">Order Info</h6>
      <div class="small muted mb-1">Ordered for</div>
      <div class="mb-2"><?= icon('user', 'me-1') ?><?= htmlspecialchars($order['patient_label'] ?? 'Not specified') ?></div>
      <?php if (!empty($order['prescription_id'])): ?>
        <!-- The prescription this order was bought with. -->
        <div class="small muted mb-1">Prescription used</div>
        <div class="mb-2"><?= icon('scroll-text', 'me-1') ?><a href="<?= BASE_URL ?>/customer/prescription/status/<?= (int) $order['prescription_id'] ?>" class="semibold">#PR-<?= (int) $order['prescription_id'] ?></a></div>
      <?php endif; ?>
      <div class="small muted mb-1">Placed on</div>
      <div class="mb-2"><?= date('d M Y, h:i A', strtotime($order['placed_at'])) ?></div>
      <div class="small muted mb-1">Payment method</div>
      <?php $payLabels = ['cod' => (($order['delivery_method'] ?? 'delivery') === 'pickup' ? 'Over the Counter' : 'Cash on Delivery'), 'card' => 'Credit / Debit Card']; ?>
      <div class="mb-2"><?= htmlspecialchars($payLabels[$order['payment_method']] ?? ucfirst($order['payment_method'])) ?></div>
      <div class="small muted mb-1">Payment status</div>
      <div class="mb-2"><span class="tag ps-pay ps-pay-<?= e(Order::paymentStatus($order)) ?>"><?= e(Order::paymentLabel($order)) ?></span></div>
      <?php if (!empty($order['allergy_alerts'])): ?>
        <div class="note note-warn small mb-2">
          <strong><?= icon('circle-alert', 'me-1') ?>Allergy warning accepted</strong>
          <ul class="mb-0 mt-1"><?php foreach ($order['allergy_alerts'] as $a): ?><li><?= e($a) ?></li><?php endforeach; ?></ul>
          <div class="mt-1">Our pharmacist checks this before your order is sent.</div>
        </div>
      <?php endif; ?>
      <?php if (($order['delivery_method'] ?? 'delivery') === 'pickup'): ?>
        <?php
          $pk = $order['pickup'] ?? [];
          $pkWhen = '';
          if (!empty($pk['date'])) {
              $pkWhen = date('d M Y', strtotime($pk['date']));
              if (!empty($pk['time'])) {
                  $pkWhen .= ', ' . date('g:i A', strtotime($pk['time']));
              }
          }
        ?>
        <div class="small muted mb-1">Fulfilment</div>
        <div class="mb-2"><?= icon('store', 'me-1') ?>Store Pickup</div>
        <div class="small muted mb-1">Pickup slot</div>
        <div class="mb-2"><?= $pkWhen !== '' ? htmlspecialchars($pkWhen) : 'To be confirmed' ?></div>
        <div class="small muted mb-1">Collect from</div>
        <div><?= htmlspecialchars(STORE_NAME) ?><div class="muted small"><?= htmlspecialchars(STORE_ADDRESS) ?></div></div>
      <?php else: ?>
        <?php $slotText = DeliverySlot::describe($order['delivery_slot'] ?? null); ?>
        <div class="small muted mb-1">Fulfilment</div>
        <div class="mb-2"><?= icon('truck', 'me-1') ?>Home Delivery</div>
        <?php // Old orders placed before slots existed: only say "to be confirmed" while still open. ?>
        <?php if ($slotText !== null || !in_array($order['status'], ['delivered', 'cancelled'], true)): ?>
          <div class="small muted mb-1">Delivery slot</div>
          <div class="mb-2"><?= icon('calendar', 'me-1') ?><?= $slotText !== null ? e($slotText) : 'To be confirmed' ?></div>
        <?php endif; ?>
        <?php if (!empty($deliverySchedule)): ?>
          <!-- Change the window while the order is still pending. -->
          <details class="ps-reschedule mb-2">
            <summary class="small">Change delivery time</summary>
            <form method="POST" action="<?= BASE_URL ?>/customer/orders/reschedule/<?= (int) $order['id'] ?>" class="mt-2">
              <?= csrf_field() ?>
              <?php
                $selectedSlot = $currentSlot;
                require __DIR__ . '/../partials/delivery-slot-picker.php';
              ?>
              <button type="submit" class="btn btn-ps-primary btn-sm w-100">Save New Time</button>
            </form>
          </details>
        <?php endif; ?>
        <?php if (!empty($order['address'])): ?>
          <div class="small muted mb-1">Deliver to</div>
          <div class="mb-2"><?= e($order['address']) ?><?php if (!empty($order['address_notes'])): ?><div class="muted small"><?= e($order['address_notes']) ?></div><?php endif; ?></div>
        <?php endif; ?>
        <div class="small muted mb-1">Delivered by</div>
        <div><?= htmlspecialchars($order['delivery_person'] ?? 'Not assigned yet') ?></div>
      <?php endif; ?>
    </div>
  </div>
</div>
