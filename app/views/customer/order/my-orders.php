<h4 class="bold mb-1">Order History</h4>
<p class="muted mb-4">Track your current orders and see everything you have bought before.</p>

<?php if (!empty($rxInProgress)): ?>
  <!-- Prescriptions are not orders (no items, price or delivery yet), so
       they get their own section with PR numbers and never mix with orders. -->
  <div class="ps-card p-3 mb-4 ps-rx-progress">
    <div class="flex between middle mb-1 wrap gap-2">
      <h6 class="bold mb-0"><?= icon('scroll-text', 'me-2') ?>Prescriptions in progress (<?= count($rxInProgress) ?>)</h6>
      <a href="<?= BASE_URL ?>/customer/prescription/upload" class="small semibold">Upload a prescription</a>
    </div>
    <p class="muted small mb-2">These aren't orders yet. Once a pharmacist has prepared a prescription, you add it to your cart and check out, and it becomes an order.</p>
    <?php foreach ($rxInProgress as $rx): ?>
      <a href="<?= BASE_URL ?>/customer/prescription/status/<?= (int) $rx['id'] ?>" class="flex between middle gap-2 p-2 border-top nounderline text-dark">
        <span>
          <span class="semibold">#PR-<?= (int) $rx['id'] ?></span>
          <span class="muted small ms-2"><?= e($rx['file_name']) ?> &middot; for <?= e($patients->label($userId, $rx['patient_id'] ?? null)) ?> &middot; uploaded <?= date('M j', strtotime($rx['uploaded_at'])) ?></span>
        </span>
        <span class="ps-status ps-status-<?= e($rx['progress']['tone']) ?>"><?= e($rx['progress']['label']) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php if (empty($active) && empty($past)): ?>
  <div class="ps-empty-state ps-card">
    <?= icon('package') ?>
    <h5 class="mt-3">No orders yet</h5>
    <p class="muted">Your order history will show up here once you place an order.</p>
    <a href="<?= BASE_URL ?>/customer/catalog" class="btn btn-ps-primary mt-2">Start Shopping</a>
  </div>
<?php else: ?>

  <?php if (!empty($active)): ?>
    <div class="mb-2"><span class="ps-status ps-status-processing">ACTIVE ORDERS (<?= count($active) ?>)</span></div>
    <?php foreach ($active as $o): ?>
      <div class="ps-card p-3 mb-3">
        <div class="flex between top wrap gap-2">
          <div>
            <span class="muted small">#PS-<?= $o['id'] ?></span>
            <div class="bold"><?= htmlspecialchars(implode(' + ', array_column($o['items'], 'name'))) ?></div>
            <div class="muted small mt-1">
              <?= icon('user', 'me-1') ?>For <?= htmlspecialchars($o['patient_label'] ?? 'Not specified') ?><?php if (!empty($o['prescription_id'])): ?> &middot; <?= icon('scroll-text', 'me-1') ?>Prescription #PR-<?= (int) $o['prescription_id'] ?><?php endif; ?>
            </div>
          </div>
          <span class="ps-status ps-status-<?= $o['status'] ?>"><?= Order::statusLabel($o['status']) ?></span>
        </div>
        <hr>
        <div class="row g-2 small muted mb-2">
          <div class="col-6 col-md-3"><div>Items</div><div class="text-dark semibold"><?= count($o['items']) ?> item(s)</div></div>
          <div class="col-6 col-md-3"><div>Total</div><div class="text-dark semibold">Rs. <?= number_format($o['total'], 2) ?></div></div>
          <div class="col-6 col-md-3"><div>Delivered by</div><div class="text-dark semibold"><?= htmlspecialchars($o['delivery_person'] ?? 'Not assigned yet') ?></div></div>
          <div class="col-6 col-md-3"><div>Ordered</div><div class="text-dark semibold"><?= date('M j, Y', strtotime($o['placed_at'])) ?></div></div>
        </div>
        <div class="flex between middle">
          <?php $slotText = DeliverySlot::describe($o['delivery_slot'] ?? null, 'D, j M'); ?>
          <?php if ($slotText !== null): ?>
            <span class="small"><?= icon('calendar', 'text-success me-1') ?>Delivery slot: <strong><?= e($slotText) ?></strong></span>
          <?php else: ?>
            <span class="small"><?= icon('truck', 'text-success me-1') ?>In transit — track for latest status</span>
          <?php endif; ?>
          <div class="flex middle gap-2">
            <form method="POST" action="<?= BASE_URL ?>/customer/orders/reorder/<?= $o['id'] ?>" class="inline">
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-ps-outline btn-sm"><?= icon('rotate-cw', 'me-1') ?>Reorder</button>
            </form>
            <a href="<?= BASE_URL ?>/customer/orders/<?= $o['id'] ?>" class="semibold small">Track Package <?= icon('arrow-right', 'ms-1') ?></a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if (!empty($past)): ?>
    <h6 class="bold mt-4 mb-2">Past Orders</h6>
    <?php foreach ($past as $o): ?>
      <div class="ps-card p-3 mb-2 flex between middle wrap gap-2">
        <div>
          <span class="muted small">#PS-<?= $o['id'] ?></span>
          <div class="semibold"><?= htmlspecialchars(implode(' + ', array_column($o['items'], 'name'))) ?></div>
          <div class="muted small"><?= icon('user', 'me-1') ?>For <?= htmlspecialchars($o['patient_label'] ?? 'Not specified') ?><?php if (!empty($o['prescription_id'])): ?> &middot; <?= icon('scroll-text', 'me-1') ?>Prescription #PR-<?= (int) $o['prescription_id'] ?><?php endif; ?></div>
          <div class="muted small">Ordered: <?= date('M j, Y', strtotime($o['placed_at'])) ?> &middot; Amount: Rs. <?= number_format($o['total'], 2) ?></div>
        </div>
        <div class="flex middle gap-2">
          <span class="ps-status ps-status-<?= $o['status'] ?>"><?= Order::statusLabel($o['status']) ?></span>
          <form method="POST" action="<?= BASE_URL ?>/customer/orders/reorder/<?= $o['id'] ?>" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-ps-outline btn-sm"><?= icon('rotate-cw', 'me-1') ?>Reorder</button>
          </form>
          <a href="<?= BASE_URL ?>/customer/orders/<?= $o['id'] ?>" class="btn btn-ps-outline btn-sm">View</a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

<?php endif; ?>
