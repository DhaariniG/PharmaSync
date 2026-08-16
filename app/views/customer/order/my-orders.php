<h4 class="bold mb-1">Order History</h4>
<p class="muted mb-4">Track your current deliveries and manage past prescriptions.</p>

<?php if (empty($active) && empty($past)): ?>
  <div class="ps-empty-state ps-card">
    <?= icon('package') ?>
    <h5 class="mt-3">No orders yet</h5>
    <p class="muted">Your order history will show up here once you place an order.</p>
    <a href="<?= BASE_URL ?>/catalog" class="btn btn-ps-primary mt-2">Start Shopping</a>
  </div>
<?php else: ?>

  <?php if (!empty($active)): ?>
    <div class="mb-2"><span class="ps-status ps-status-processing">ACTIVE ORDERS (<?= count($active) ?>)</span></div>
    <?php foreach ($active as $o): ?>
      <div class="ps-card p-3 mb-3">
        <div class="flex between top wrap gap-2">
          <div>
            <span class="muted small">#ORD-<?= $o['id'] ?></span>
            <div class="bold"><?= htmlspecialchars(implode(' + ', array_column($o['items'], 'name'))) ?></div>
            <div class="muted small mt-1">
              <?= icon('user', 'me-1') ?>For <?= htmlspecialchars($o['patient_label'] ?? 'Not specified') ?>
            </div>
          </div>
          <span class="ps-status ps-status-<?= $o['status'] ?>"><?= Order::statusLabel($o['status']) ?></span>
        </div>
        <hr>
        <div class="row g-2 small muted mb-2">
          <div class="col-6 col-md-3"><div>Items</div><div class="text-dark semibold"><?= count($o['items']) ?> item(s)</div></div>
          <div class="col-6 col-md-3"><div>Total Paid</div><div class="text-dark semibold">Rs. <?= number_format($o['total'], 2) ?></div></div>
          <div class="col-6 col-md-3"><div>Delivered by</div><div class="text-dark semibold"><?= htmlspecialchars($o['delivery_person'] ?? 'Not assigned yet') ?></div></div>
          <div class="col-6 col-md-3"><div>Ordered</div><div class="text-dark semibold"><?= date('M j, Y', strtotime($o['placed_at'])) ?></div></div>
        </div>
        <div class="flex between middle">
          <span class="small"><?= icon('truck', 'text-success me-1') ?>In transit — track for latest status</span>
          <div class="flex middle gap-2">
            <form method="POST" action="<?= BASE_URL ?>/orders/reorder/<?= $o['id'] ?>" class="inline">
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-ps-outline btn-sm"><?= icon('rotate-cw', 'me-1') ?>Reorder</button>
            </form>
            <a href="<?= BASE_URL ?>/orders/<?= $o['id'] ?>" class="semibold small">Track Package <?= icon('arrow-right', 'ms-1') ?></a>
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
          <span class="muted small">#ORD-<?= $o['id'] ?></span>
          <div class="semibold"><?= htmlspecialchars(implode(' + ', array_column($o['items'], 'name'))) ?></div>
          <div class="muted small"><?= icon('user', 'me-1') ?>For <?= htmlspecialchars($o['patient_label'] ?? 'Not specified') ?></div>
          <div class="muted small">Ordered: <?= date('M j, Y', strtotime($o['placed_at'])) ?> &middot; Amount: Rs. <?= number_format($o['total'], 2) ?></div>
        </div>
        <div class="flex middle gap-2">
          <span class="ps-status ps-status-<?= $o['status'] ?>"><?= Order::statusLabel($o['status']) ?></span>
          <form method="POST" action="<?= BASE_URL ?>/orders/reorder/<?= $o['id'] ?>" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-ps-outline btn-sm"><?= icon('rotate-cw', 'me-1') ?>Reorder</button>
          </form>
          <a href="<?= BASE_URL ?>/orders/<?= $o['id'] ?>" class="btn btn-ps-outline btn-sm">View</a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

<?php endif; ?>
