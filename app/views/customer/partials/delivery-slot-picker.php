<?php
/*
 * Delivery date + time window picker. Used by checkout and by "Change
 * delivery time" on the order page. Day switching is in assets/js/main.js
 * ([data-slot-picker]); without JavaScript every day's windows are listed.
 *
 * Expects: $deliverySchedule  from DeliverySlot::schedule()
 *          $selectedSlot      "Y-m-d|H:i" to tick, or null
 * Optional: $slotFieldName    radio name, default 'delivery_slot'
 *           $currentSlot      "Y-m-d|H:i" the order already has (shown, not required)
 */
$slotFieldName = $slotFieldName ?? 'delivery_slot';
$currentSlot   = $currentSlot ?? null;
$openDay = $selectedSlot !== null ? explode('|', $selectedSlot)[0] : ($deliverySchedule[0]['date'] ?? null);
?>
<div data-slot-picker data-field="<?= e($slotFieldName) ?>">
  <div class="ps-day-picker mb-3" role="group" aria-label="Delivery date">
    <?php foreach ($deliverySchedule as $day):
      $ts = strtotime($day['date']);
      $closedAllDay = count(array_filter($day['windows'], fn($w) => $w['status'] === 'closed')) === count($day['windows']);
    ?>
      <button type="button" class="ps-day<?= $day['date'] === $openDay ? ' active' : '' ?><?= $day['free'] === 0 ? ' is-full' : '' ?>" data-day="<?= e($day['date']) ?>" aria-pressed="<?= $day['date'] === $openDay ? 'true' : 'false' ?>">
        <span class="ps-day-name"><?= $day['date'] === date('Y-m-d') ? 'Today' : date('D', $ts) ?></span>
        <span class="ps-day-num"><?= date('j', $ts) ?></span>
        <span class="ps-day-month"><?= date('M', $ts) ?></span>
        <span class="ps-day-free"><?= $closedAllDay ? 'Closed' : ($day['free'] === 0 ? 'Full' : $day['free'] . ' free') ?></span>
      </button>
    <?php endforeach; ?>
  </div>

  <?php foreach ($deliverySchedule as $day): ?>
    <div class="ps-slot-panel" data-day-panel="<?= e($day['date']) ?>">
      <div class="semibold mb-1">Delivery Date: <span class="text-primary"><?= date('l, j F', strtotime($day['date'])) ?></span></div>
      <div class="muted small mb-2">Available slots:</div>

      <?php foreach ($day['windows'] as $w):
        $isCurrent = $w['value'] === $currentSlot;
        $isOpen = $w['status'] === 'available' || $isCurrent;
        $statusText = $isCurrent ? 'Your current time' : [
            'available' => 'Available',
            'full'      => 'Fully Booked',
            'closed'    => $w['closed_reason'] !== null ? 'Closed – ' . $w['closed_reason'] : 'Store closed',
            'past'      => 'Booking closed',
        ][$w['status']];
      ?>
        <label class="ps-slot is-<?= $isCurrent ? 'available' : e($w['status']) ?>">
          <input type="radio" class="check-box" name="<?= e($slotFieldName) ?>"
                 value="<?= e($w['value']) ?>"
                 data-label="<?= e(DeliverySlot::describe($w, 'D, j M')) ?>"
                 <?= $w['value'] === $selectedSlot ? 'checked' : '' ?>
                 <?= $isOpen ? '' : 'disabled' ?>>
          <span class="ps-slot-time"><?= e($w['start']) ?> &ndash; <?= e($w['end']) ?></span>
          <span class="ps-slot-status">
            <span class="ps-slot-state"><?= icon($isOpen ? 'check' : 'x', 'me-1') ?><?= e($statusText) ?></span>
            <?php if ($w['status'] === 'available' && !$isCurrent && $w['left'] <= 2): ?>
              <span class="ps-slot-left">Only <?= (int) $w['left'] ?> left</span>
            <?php endif; ?>
          </span>
        </label>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
</div>
