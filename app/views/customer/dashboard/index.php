<?php
  $__hour = (int) date('G');
  $greeting = $__hour < 12 ? 'Good Morning' : ($__hour < 18 ? 'Good Afternoon' : 'Good Evening');
?>
<div class="flex between top mb-4 wrap gap-2">
  <div>
    <h3 class="bold mb-1"><?= $greeting ?>, <?= htmlspecialchars(explode(' ', $user['name'])[0]) ?></h3>
    <p class="muted mb-0">Your health is our priority. Here's a look at your wellness dashboard today.</p>
  </div>
  <span class="tag bg-white border text-dark px-3 py-2"><?= icon('calendar', 'me-2') ?><?= date('l, M j') ?></span>
</div>

<?php
  // Show a real reminder only if one exists; otherwise be honest with a dash
  // rather than inventing a future date the demo can't back up.
  $nextRefill = $user['next_refill'] ?? null;
  $stats = [
    ['package',              (string) count($recentOrders),                  'Active Orders'],
    ['scroll-text',(string) count($prescriptions),                 'Prescriptions'],
    ['star',             number_format($user['health_points'] ?? 0),     'Health Points'],
    ['calendar-check', $nextRefill ? date('M j', strtotime($nextRefill)) : '—', 'Next Refill'],
  ];
?>
<div class="row g-3 mb-4">
  <?php foreach ($stats as [$statIcon, $statValue, $statLabel]):
    require __DIR__ . '/../partials/stat-card.php';
  endforeach; ?>
</div>

<?php if ($therapyAlert): ?>
  <div class="note note-danger flex between middle mb-4">
    <div>
      <?= icon('triangle-alert', 'me-2') ?>
      <strong>Active Therapy Alert:</strong> <?= htmlspecialchars($therapyAlert['medicine']['name']) ?> is currently out of stock.
    </div>
    <button type="button" class="btn btn-sm btn-danger" data-open="#dashboardAltModal">View Alternatives</button>
  </div>
<?php endif; ?>

<div class="row g-3 mb-4">
  <div class="col-lg-7">
    <div class="ps-card p-4 h-100">
      <div class="flex between middle mb-2">
        <h6 class="bold mb-0"><?= icon('cloud-upload', 'me-2') ?>Upload Prescription</h6>
        <span class="tag bg-light muted border">Supports JPG, PDF</span>
      </div>
      <a href="<?= BASE_URL ?>/prescription/upload" class="nounderline">
        <div class="border border-2 border-dashed rounded-3 text-center py-4 mt-2" style="border-style:dashed !important;">
          <?= icon('image', 'size-2 muted mb-2') ?>
          <div class="semibold">Drag and drop your prescription here</div>
          <div class="muted small mb-3">Our pharmacists will verify it within 15 minutes and add the items to your cart.</div>
          <span class="btn btn-ps-primary btn-sm"><?= icon('upload', 'me-1') ?>Select Prescription</span>
        </div>
      </a>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="ps-card p-4 h-100">
      <div class="flex between middle mb-3">
        <h6 class="bold mb-0">Shop Categories</h6>
        <a href="<?= BASE_URL ?>/catalog" class="small">View All</a>
      </div>
      <div class="row g-2">
        <?php
        $icons = [1=>'pill',2=>'thermometer',3=>'pill',4=>'pill-bottle',5=>'hand',6=>'droplet',7=>'baby'];
        $i = 0;
        foreach ($categories as $id => $name):
          if ($i++ >= 4) break; ?>
          <div class="col-6">
            <a href="<?= BASE_URL ?>/catalog?category=<?= $id ?>" class="nounderline">
              <div class="ps-card p-3 text-center">
                <?= icon($icons[$id] ?? 'pill', 'size-5 mb-1', 'color: var(--ps-primary)') ?>
                <div class="text-dark small semibold"><?= htmlspecialchars($name) ?></div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-7">
    <?php
      // Drive the status widget from the customer's most recent order's real
      // tracking data (falls back to an empty card only if there are none).
      $trackingOrder = $recentOrders[0] ?? null;
      if ($trackingOrder):
        require __DIR__ . '/../partials/tracking-steps.php';
      else: ?>
        <div class="ps-card p-4 h-100 flex flex-col center text-center">
          <?= icon('package-open', 'size-2 muted mb-2') ?>
          <div class="semibold">No active orders</div>
          <p class="muted small mb-2">Your order status will appear here once you place an order.</p>
          <a href="<?= BASE_URL ?>/catalog" class="btn btn-ps-primary btn-sm self-middle">Browse Medicines</a>
        </div>
      <?php endif; ?>
  </div>
  <div class="col-lg-5">
    <div class="ps-card p-4 h-100 flex flex-col center text-center" style="background: linear-gradient(135deg, var(--ps-primary-light), #fff);">
      <div class="tag bg-danger self-top mb-2">Limited Offer</div>
      <h6 class="bold">30% OFF Immunity Boosters</h6>
      <p class="muted small mb-2">Protect your family this season. Use code <strong>HEALTH30</strong> at checkout.</p>
      <a href="<?= BASE_URL ?>/catalog?category=3" class="btn btn-ps-primary btn-sm self-top">Shop Now</a>
    </div>
  </div>
</div>

<div class="flex between middle mb-3">
  <h5 class="ps-section-title mb-0">Featured medicines</h5>
  <a href="<?= BASE_URL ?>/catalog" class="small">View all <?= icon('arrow-right', 'ms-1') ?></a>
</div>
<div class="row g-3 mb-4">
  <?php foreach ($featured as $m): ?>
    <div class="col-6 col-md-3">
      <?php require __DIR__ . '/../partials/medicine-card.php'; ?>
    </div>
  <?php endforeach; ?>
</div>

<div class="flex between middle mb-3">
  <h5 class="ps-section-title mb-0">Recent Orders</h5>
  <a href="<?= BASE_URL ?>/orders" class="small">See All <?= icon('arrow-right', 'ms-1') ?></a>
</div>
<div class="ps-card">
  <div class="table-wrap">
    <table class="table mb-0 valign">
      <thead class="table-head">
        <tr><th>Order ID</th><th>Items</th><th>Date</th><th>Amount</th><th>Status</th></tr>
      </thead>
      <tbody>
        <?php if (empty($recentOrders)): ?>
          <tr><td colspan="5" class="text-center muted py-4">No orders yet.</td></tr>
        <?php endif; ?>
        <?php foreach ($recentOrders as $o): ?>
          <tr>
            <td class="semibold"><a href="<?= BASE_URL ?>/orders/<?= $o['id'] ?>">#<?= $o['id'] ?></a></td>
            <td><?= htmlspecialchars(implode(', ', array_column($o['items'], 'name'))) ?></td>
            <td><?= date('M j, Y', strtotime($o['placed_at'])) ?></td>
            <td>Rs. <?= number_format($o['total'], 2) ?></td>
            <td><span class="ps-status ps-status-<?= $o['status'] ?>"><?= Order::statusLabel($o['status']) ?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if ($therapyAlert):
  $modalId = 'dashboardAltModal';
  $original = $therapyAlert['medicine'];
  $alternates = $therapyAlert['alternates'];
  $autoShow = false;
  require __DIR__ . '/../partials/alternative-modal.php';
endif; ?>
