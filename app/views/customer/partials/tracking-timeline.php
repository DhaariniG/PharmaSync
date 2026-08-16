<?php
// Vertical order-tracking timeline. Expects $tracking (list of steps).
$__tracking = $tracking ?? [];
?>
<ul class="ps-timeline">
  <?php foreach ($__tracking as $step): ?>
    <li class="<?= !empty($step['done']) ? 'done' : 'active' ?>">
      <span class="dot-icon">
        <?php if (!empty($step['done'])): ?>
          <?= icon('check') ?>
        <?php else: ?>
          <?= icon('truck') ?>
        <?php endif; ?>
      </span>
      <div class="semibold small"><?= htmlspecialchars($step['label']) ?></div>
      <div class="muted small"><?= htmlspecialchars($step['time']) ?></div>
      <?php if (!empty($step['note'])): ?>
        <div class="ps-card p-2 mt-1 small muted"><?= htmlspecialchars($step['note']) ?></div>
      <?php endif; ?>
    </li>
  <?php endforeach; ?>
</ul>
