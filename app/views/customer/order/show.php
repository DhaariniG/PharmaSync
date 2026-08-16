<?php $tracking = $order['tracking'] ?? []; ?>
<nav aria-label="breadcrumb">
  <ol class="crumbs small">
    <li class="crumb"><a href="<?= BASE_URL ?>/orders">My Orders</a></li>
    <li class="crumb active">Order #<?= $order['id'] ?></li>
  </ol>
</nav>

<div class="flex between middle mb-4 wrap gap-2">
  <h4 class="bold mb-0">Order #<?= $order['id'] ?></h4>
  <div class="flex middle gap-2">
    <span class="ps-status ps-status-<?= $order['status'] ?> size-6"><?= Order::statusLabel($order['status']) ?></span>
    <form method="POST" action="<?= BASE_URL ?>/orders/reorder/<?= $order['id'] ?>" class="inline">
      <?= csrf_field() ?>
      <button type="submit" class="btn btn-ps-primary btn-sm"><?= icon('rotate-cw', 'me-1') ?>Reorder</button>
    </form>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="ps-card p-4 mb-3">
      <h6 class="bold mb-3">Items</h6>
      <?php foreach ($order['items'] as $item): ?>
        <div class="flex between small mb-2 pb-2 border-bottom">
          <span><?= htmlspecialchars($item['name']) ?> &times; <?= $item['quantity'] ?></span>
          <span>Rs. <?= number_format($item['unit_price'] * $item['quantity'], 2) ?></span>
        </div>
      <?php endforeach; ?>
      <div class="flex between small mt-3"><span class="muted">Subtotal</span><span>Rs. <?= number_format($order['subtotal'], 2) ?></span></div>
      <div class="flex between small"><span class="muted">Delivery fee</span><span>Rs. <?= number_format($order['delivery_fee'], 2) ?></span></div>
      <?php if (!empty($order['tax'])): ?>
        <div class="flex between small mb-2"><span class="muted">Tax</span><span>Rs. <?= number_format($order['tax'], 2) ?></span></div>
      <?php endif; ?>
      <div class="flex between bold"><span>Total</span><span>Rs. <?= number_format($order['total'], 2) ?></span></div>
    </div>

    <div class="ps-banner p-4 flex between middle wrap gap-3">
      <div>
        <div class="bold">Need help with an order?</div>
        <div class="small">Our pharmacists are available 24/7 for dosage guidance or delivery questions.</div>
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
      <div class="small muted mb-1">Placed on</div>
      <div class="mb-2"><?= date('d M Y, h:i A', strtotime($order['placed_at'])) ?></div>
      <div class="small muted mb-1">Payment method</div>
      <?php $payLabels = ['cod' => (($order['delivery_method'] ?? 'delivery') === 'pickup' ? 'Over the Counter' : 'Cash on Delivery'), 'card' => 'Credit / Debit Card', 'bank_transfer' => 'Bank Transfer']; ?>
      <div class="mb-2"><?= htmlspecialchars($payLabels[$order['payment_method']] ?? ucfirst($order['payment_method'])) ?></div>
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
        <div class="small muted mb-1">Fulfilment</div>
        <div class="mb-2"><?= icon('truck', 'me-1') ?>Home Delivery</div>
        <div class="small muted mb-1">Delivered by</div>
        <div><?= htmlspecialchars($order['delivery_person'] ?? 'Not assigned yet') ?></div>
      <?php endif; ?>
    </div>
  </div>
</div>
