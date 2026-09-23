<?php
// Dashboard stat card. Expects $statIcon, $statValue, $statLabel.
?>
<div class="col-6 col-md-3">
  <div class="ps-stat-card p-3">
    <div class="ps-stat-icon mb-2"><?= icon($statIcon) ?></div>
    <div class="bold size-4"><?= htmlspecialchars((string) $statValue) ?></div>
    <div class="muted small"><?= htmlspecialchars($statLabel) ?></div>
  </div>
</div>
