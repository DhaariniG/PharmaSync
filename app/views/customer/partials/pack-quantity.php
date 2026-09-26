<?php
/*
 * Quantity stepper that says what it counts: "Quantity (strips)" and a
 * live "= 20 tablets" underneath. Expects $medicine. The live total is
 * updated by the [data-pack-total] handler in assets/js/main.js.
 */
$packSize = (int) ($medicine['pack_size'] ?? 0);
?>
<div>
  <div class="small muted mb-1">Quantity (<?= e(CustomerMedicine::unitName($medicine, 2)) ?>)</div>
  <div class="flex middle border rounded" data-qty-stepper>
    <button type="button" class="btn btn-sm" data-qty-minus aria-label="One less"><?= icon('minus') ?></button>
    <input type="number" name="quantity" value="1" min="1" max="<?= (int) max(1, min($medicine['stock'], CustomerMedicine::maxPerOrder($medicine))) ?>" class="field border-0 text-center" style="width:60px;" aria-label="Quantity in <?= e(CustomerMedicine::unitName($medicine, 2)) ?>">
    <button type="button" class="btn btn-sm" data-qty-plus aria-label="One more"><?= icon('plus') ?></button>
  </div>
  <?php if (empty($medicine['requires_rx'])): ?>
    <div class="ps-qty-caption">Max <?= CustomerMedicine::maxPerOrder($medicine) ?> per order</div>
  <?php endif; ?>
  <?php if ($packSize > 0): ?>
    <div class="ps-qty-caption" data-pack-total data-pack-size="<?= $packSize ?>" data-pack-item="<?= e($medicine['pack_item']) ?>">
      = <?= e(CustomerMedicine::contentsFor($medicine, 1)) ?>
    </div>
  <?php endif; ?>
</div>
