<?php
$stages = [
    'pending'            => 1, // Under Pharmacist Review
    'needs_alternative'  => 3, // Alternative Suggested
    'prepared'           => 3, // Items prepared — awaiting your confirmation
    'approved'           => 4, // Ready to buy / already actioned
    'rejected'           => 1,
];
$stageLabels = ['Prescription Uploaded', 'Under Pharmacist Review', 'Approved', 'Alternative Suggested', 'Ready to Buy'];
$current = $stages[$prescription['status']] ?? 1;
?>
<div class="flex between middle mb-4 wrap gap-2">
  <div>
    <h4 class="bold mb-1">Prescription Status</h4>
    <p class="muted mb-0">Prescription ID: <span class="semibold">#PR-<?= $prescription['id'] ?></span></p>
  </div>
  <div class="flex gap-2">
    <a href="<?= BASE_URL ?>/customer/prescription/upload" class="btn btn-ps-primary btn-sm">Re-upload Document</a>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="ps-card p-4 mb-3">
      <div class="upper small bold muted mb-3">Tracking Journey</div>
      <ul class="ps-timeline">
        <?php foreach ($stageLabels as $i => $label): ?>
          <li class="<?= $i < $current ? 'done' : ($i === $current ? 'active' : '') ?>">
            <span class="dot-icon"><?php if ($i < $current): ?><?= icon('check') ?><?php elseif ($i === $current): ?><?= icon('ellipsis') ?><?php endif; ?></span>
            <div class="semibold small"><?= $label ?></div>
            <?php if ($i === 0): ?>
              <div class="muted small"><?= date('M j, g:i A', strtotime($prescription['uploaded_at'])) ?></div>
              <span class="tag ps-badge-otc mt-1">Completed</span>
            <?php elseif ($i === $current): ?>
              <div class="muted small">In progress</div>
              <span class="tag ps-status-needs_alternative ps-status mt-1">Active Review</span>
            <?php else: ?>
              <div class="muted small">Pending</div>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="ps-card p-3" style="background: var(--ps-primary-light);">
      <div class="flex middle gap-2 mb-2">
        <div class="ps-avatar sm"><?= isset($prescription['pharmacist_name']) ? implode('', array_map(fn($p) => $p[0], array_slice(explode(' ', $prescription['pharmacist_name']), -2))) : 'PH' ?></div>
        <div>
          <div class="semibold small">Pharmacist In-Charge</div>
          <div class="muted small"><?= htmlspecialchars($prescription['pharmacist_name'] ?? 'On-call Pharmacist') ?></div>
        </div>
      </div>
      <p class="small muted">Need help with your prescription? Chat with our pharmacist for dosage advice or alternative queries.</p>
      <a href="mailto:support@pharmasync.test" class="btn btn-ps-primary btn-sm w-100">Chat with Pharmacist</a>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="note note-danger flex gap-2 mb-3">
      <?= icon('info', 'mt-1') ?>
      <div>
        <strong>Pharmacist Note</strong>
        <div class="small"><?= htmlspecialchars($prescription['notes']) ?></div>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <div class="ps-card p-3 h-100">
          <div class="flex between middle mb-2">
            <span class="semibold small">Original Document</span>
            <a href="<?= BASE_URL ?>/customer/prescription/file/<?= (int) $prescription['id'] ?>" target="_blank" rel="noopener" class="small">View Fullscreen</a>
          </div>
          <div class="flex middle center rounded" style="height:160px;background: var(--ps-bg);">
            <?= icon(str_ends_with($prescription['file_name'], '.pdf') ? 'file-text' : 'image', 'size-1 muted') ?>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="ps-card p-3 h-100">
          <div class="semibold small mb-2">Prescription Details</div>
          <div class="flex between small mb-1"><span class="muted">Patient</span><span><?= htmlspecialchars($patientLabel) ?></span></div>
          <div class="flex between small mb-1"><span class="muted">Clinic</span><span><?= htmlspecialchars($prescription['clinic'] ?? '—') ?></span></div>
          <div class="flex between small mb-1"><span class="muted">Prescribed Date</span><span><?= isset($prescription['prescribed_date']) ? date('M j, Y', strtotime($prescription['prescribed_date'])) : date('M j, Y', strtotime($prescription['uploaded_at'])) ?></span></div>
          <?php if (!empty($requestedMedicine)): ?>
            <div class="flex between small mb-1"><span class="muted">Requested Medicine</span><span><?= htmlspecialchars($requestedMedicine['name']) ?> &times; <?= e(CustomerMedicine::quantityLabel($requestedMedicine, (int) ($prescription['requested_quantity'] ?? 1))) ?></span></div>
          <?php endif; ?>
          <hr>
          <div class="text-center">
            <div class="muted small upper">Status</div>
            <?php
              // Green when approved, red when rejected, amber while waiting.
              $statusTone = ['approved' => 'text-success', 'rejected' => 'text-danger'][$prescription['status']] ?? 'text-warning';
            ?>
            <div class="bold <?= $statusTone ?>">
              <?php if ($prescription['status'] === 'prepared'): ?>Awaiting Your Confirmation
              <?php elseif ($prescription['status'] === 'needs_alternative' && ($prescription['alternative_decision'] ?? 'pending') === 'pending'): ?>Awaiting Your Approval
              <?php elseif (($prescription['alternative_decision'] ?? '') === 'waiting'): ?>Waiting for Restock
              <?php elseif ($prescription['status'] === 'approved'): ?>Approved
              <?php elseif ($prescription['status'] === 'rejected'): ?>Rejected
              <?php else: ?>Under Review
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php if ($prescription['status'] === 'prepared' && !empty($preparedItems)): ?>
      <div class="flex between middle mb-2">
        <h6 class="bold mb-0">Items Prepared by the Pharmacist</h6>
        <span class="muted small">Prepared by <?= htmlspecialchars($prescription['pharmacist_name'] ?? 'our pharmacist') ?></span>
      </div>
      <div class="ps-card p-3 mb-3">
        <?php foreach ($preparedItems as $line): $m = $line['medicine']; ?>
          <div class="flex middle gap-3 p-2 border-bottom">
            <img src="<?= medicine_image($m) ?>" class="rounded" width="48" height="48" style="object-fit:cover;" alt="<?= htmlspecialchars($m['name']) ?>" onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/medicines/_placeholder.svg';">
            <div class="grow">
              <div class="semibold small"><?= htmlspecialchars($m['name']) ?></div>
              <div class="muted small">Qty: <?= e(CustomerMedicine::quantityLabel($m, (int) $line['quantity'])) ?></div>
            </div>
            <div class="ps-price small">Rs. <?= number_format($m['price'] * $line['quantity'], 2) ?></div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="text-center mb-3">
        <p class="muted small">Review the items above. Confirming will add them to your cart, where they can be combined with any OTC items already there and checked out together.</p>
        <form method="POST" action="<?= BASE_URL ?>/customer/prescription/confirm/<?= (int) $prescription['id'] ?>" class="inline">
          <?= csrf_field() ?>
          <button type="submit" class="btn btn-ps-primary px-4"><?= icon('check', 'me-2') ?>Confirm & Add to Cart</button>
        </form>
      </div>

    <?php elseif (!empty($alternativeMedicine)): ?>
      <?php $decision = $prescription['alternative_decision'] ?? 'pending'; ?>
      <div class="flex between middle mb-2">
        <h6 class="bold mb-0">Pharmacist-Suggested Alternative</h6>
        <span class="muted small">Recommended by <?= htmlspecialchars($prescription['pharmacist_name'] ?? 'our pharmacist') ?></span>
      </div>
      <div class="ps-card p-3 flex gap-3 middle mb-2">
        <img src="<?= medicine_image($alternativeMedicine) ?>" class="rounded" width="60" height="60" style="object-fit:cover;" alt="<?= htmlspecialchars($alternativeMedicine['name']) ?>" onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/medicines/_placeholder.svg';">
        <div class="grow">
          <div class="flex between">
            <span class="semibold small"><?= htmlspecialchars($alternativeMedicine['name']) ?></span>
            <span class="tag ps-badge-otc">In Stock</span>
          </div>
          <div class="muted small">Same medicine: <?= e(($alternativeMedicine['generic'] ?? '') . ' ' . ($alternativeMedicine['strength'] ?? '')) ?>, from <?= e($alternativeMedicine['manufacturer']) ?></div>
          <div class="ps-price small">Rs. <?= number_format($alternativeMedicine['price'], 2) ?></div>
        </div>
      </div>

      <?php if ($decision === 'approved'): ?>
        <div class="note note-ok small mb-3"><?= icon('circle-check', 'me-1') ?>You approved this alternative. See below for how much is left to order.</div>
      <?php elseif ($decision === 'waiting'): ?>
        <div class="note note-warn small mb-3"><?= icon('clock', 'me-1') ?>You chose to keep waiting for the original medicine to be restocked. We'll notify you as soon as it's available.</div>
      <?php else: ?>
        <p class="muted small mb-2">This substitute was chosen by your pharmacist — you can approve it or keep waiting for the original medicine to be restocked.</p>
        <div class="flex gap-2 mb-3">
          <form method="POST" action="<?= BASE_URL ?>/customer/prescription/approve-alternative/<?= (int) $prescription['id'] ?>">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-ps-primary btn-sm"><?= icon('check', 'me-1') ?>Approve Alternative</button>
          </form>
          <form method="POST" action="<?= BASE_URL ?>/customer/prescription/continue-waiting/<?= (int) $prescription['id'] ?>">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-ps-outline btn-sm">Continue Waiting for Restock</button>
          </form>
        </div>
      <?php endif; ?>

    <?php elseif ($prescription['status'] === 'approved'): ?>
      <?php if (empty($coverage)): ?>
        <div class="note note-warn small">This prescription has no items approved for ordering. Please contact the pharmacy or upload a new prescription.</div>
      <?php endif; ?>
    <?php elseif ($prescription['status'] === 'rejected'): ?>
      <div class="text-center">
        <a href="<?= BASE_URL ?>/customer/prescription/upload" class="btn btn-ps-primary">Upload New Prescription</a>
      </div>
    <?php else: ?>
      <p class="muted small text-center">A pharmacist will review this shortly. This page updates once reviewed.</p>
    <?php endif; ?>

    <?php if (!empty($coverage)):
      $leftTotal = array_sum(array_column($coverage, 'left'));
    ?>
      <!-- What this prescription allows. Quantities already ordered are
           taken off, so a prescription can't be used again and again. -->
      <div class="flex between middle mb-2 mt-4">
        <h6 class="bold mb-0">What This Prescription Covers</h6>
        <?php if ($leftTotal === 0): ?><span class="tag ps-status ps-status-delivered">Used in full</span><?php endif; ?>
      </div>
      <div class="ps-card p-3 mb-3">
        <div class="ps-table-wrap">
          <table class="ps-cover-table small w-100">
            <thead>
              <tr><th>Medicine</th><th>Prescribed</th><th>Ordered</th><th>Left</th></tr>
            </thead>
            <tbody>
              <?php foreach ($coverage as $line): $m = $line['medicine']; ?>
                <tr>
                  <td class="semibold"><?= e($m['name']) ?></td>
                  <td><?= e(CustomerMedicine::quantityLabel($m, (int) $line['prescribed'])) ?></td>
                  <td><?= (int) $line['ordered'] ?></td>
                  <td class="<?= $line['left'] > 0 ? 'text-success semibold' : 'muted' ?>"><?= (int) $line['left'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <p class="muted small mt-2 mb-0">Quantities are in packs, as set by the pharmacist. Cancelling an order gives its quantity back.</p>
      </div>
      <div class="text-center mb-3">
        <?php if ($leftTotal > 0): ?>
          <form method="POST" action="<?= BASE_URL ?>/customer/prescription/add-to-cart/<?= (int) $prescription['id'] ?>" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-ps-primary"><?= icon('shopping-cart', 'me-2') ?>Add What's Left to Cart</button>
          </form>
        <?php else: ?>
          <a href="<?= BASE_URL ?>/customer/prescription/upload" class="btn btn-ps-outline">Upload a New Prescription</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($usedInOrders)): ?>
      <!-- The orders are shown on the Orders page; here we only link to them. -->
      <h6 class="bold mb-2 mt-4">Orders Placed With This Prescription</h6>
      <div class="ps-card p-3 mb-3">
        <?php foreach ($usedInOrders as $o): ?>
          <a href="<?= BASE_URL ?>/customer/orders/<?= (int) $o['id'] ?>" class="flex between middle gap-2 p-2 border-bottom nounderline text-dark">
            <span>
              <span class="semibold">#PS-<?= (int) $o['id'] ?></span>
              <span class="muted small ms-2"><?= date('M j, Y', strtotime($o['placed_at'])) ?> &middot; <?= e(implode(', ', array_column($o['items'], 'name'))) ?></span>
            </span>
            <span class="ps-status ps-status-<?= e($o['status']) ?>"><?= e(Order::statusLabel($o['status'])) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
