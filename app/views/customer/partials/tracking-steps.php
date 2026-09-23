<?php
/*
 * Horizontal order-progress bar.
 * Expects $trackingOrder (an order with a 'tracking' array). Shows nothing
 * if there's no order.
 */
$__order = $trackingOrder ?? null;
$__steps = $__order['tracking'] ?? [];

if ($__order && !empty($__steps)):
    // The active step is the first not-yet-done step (if any).
    $__activeIndex = null;
    foreach ($__steps as $__i => $__s) {
        if (empty($__s['done'])) { $__activeIndex = $__i; break; }
    }
?>
<div class="ps-banner p-4 h-100">
  <div class="flex between middle mb-3 wrap gap-2">
    <span class="upper small semibold" style="letter-spacing:.04em;">
      Order Status #PS-<?= (int) $__order['id'] ?>
    </span>
    <span class="tag bg-white text-dark">
      <?= htmlspecialchars(Order::statusLabel($__order['status'])) ?>
    </span>
  </div>
  <div class="flex between top text-center">
    <?php foreach ($__steps as $__i => $__step):
      $__done   = !empty($__step['done']);
      $__active = ($__i === $__activeIndex);
    ?>
      <div class="fill">
        <div class="mx-auto mb-2 flex middle center rounded-circle"
             style="width:30px;height:30px;background:<?= $__done || $__active ? '#fff' : 'rgba(255,255,255,.25)' ?>; color: var(--ps-primary-dark);">
          <?php if ($__done): ?>
            <?= icon('check', 'small') ?>
          <?php elseif ($__active): ?>
            <?= icon('truck', 'small') ?>
          <?php else: ?>
            <?= icon('circle', 'small') ?>
          <?php endif; ?>
        </div>
        <div class="small"><?= htmlspecialchars($__step['label']) ?></div>
        <div class="small opacity-75" style="font-size:.7rem;"><?= htmlspecialchars($__step['time']) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>
