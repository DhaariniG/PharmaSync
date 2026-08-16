<div class="flex between top mb-4 wrap gap-2">
  <div>
    <h3 class="bold mb-1">Upload Prescription</h3>
    <p class="muted mb-0">Securely upload your medical documents. Our licensed pharmacists will verify them within 2 hours during business hours.</p>
  </div>
  <a href="tel:+94112345678" class="btn btn-ps-outline"><?= icon('phone', 'me-2') ?>Request Callback</a>
</div>

<?php if (!empty($error)): ?>
  <div class="note note-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="row g-4">
  <div class="col-lg-8">
    <?php if (!empty($requestedMedicine)): ?>
      <div class="ps-card p-3 mb-3 flex middle gap-3">
        <img src="<?= medicine_image($requestedMedicine) ?>" class="rounded" width="56" height="56" style="object-fit:cover;" alt="<?= htmlspecialchars($requestedMedicine['name']) ?>" onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/medicines/_placeholder.svg';">
        <div class="grow">
          <div class="muted small">Requesting prescription for</div>
          <div class="semibold"><?= htmlspecialchars($requestedMedicine['name']) ?> &times; <?= (int) $requestedQuantity ?></div>
        </div>
        <span class="tag ps-badge-rx">Rx Required</span>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/prescription/upload" enctype="multipart/form-data" data-validate novalidate>
          <?= csrf_field() ?>
      <?php if (!empty($requestedMedicine)): ?>
        <input type="hidden" name="requested_medicine_id" value="<?= $requestedMedicine['id'] ?>">
        <input type="hidden" name="requested_quantity" value="<?= (int) $requestedQuantity ?>">
      <?php endif; ?>
      <div class="ps-card p-4 mb-3">
        <h6 class="bold mb-1"><?= icon('user', 'me-2') ?>Who is this prescription for?</h6>
        <p class="muted small mb-3">Choose the person named on the prescription. Add family members from your profile.</p>
        <select name="patient_id" class="field" required>
          <?php foreach ($members as $m): ?>
            <option value="<?= (int) $m['id'] ?>">
              <?= htmlspecialchars($m['relationship'] === 'Self' ? $m['name'] : $m['relationship'] . ' — ' . $m['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <a href="<?= BASE_URL ?>/profile" class="small mt-2 block">Add a family member</a>
      </div>

      <div class="ps-card p-4 mb-3">
        <label for="fileInput" style="cursor:pointer;">
          <div class="border border-2 rounded-3 text-center py-5" style="border-style:dashed !important;">
            <?= icon('cloud-upload', 'size-1 mb-3', "color: var(--ps-primary);") ?>
            <h5 class="bold">Drag and drop file here</h5>
            <p class="muted small">Support for JPG, PNG, and PDF (Max 10MB)</p>
            <div class="flex center gap-2 mt-2">
              <span class="btn btn-ps-primary"><?= icon('folder-open', 'me-2') ?>Browse Files</span>
              <span class="btn btn-ps-outline" onclick="event.preventDefault();event.stopPropagation();alert('Camera capture requires a device camera — use Browse Files in this environment.');"><?= icon('camera', 'me-2') ?>Camera Scan</span>
            </div>
            <div class="muted small mt-2" id="fileName">No file chosen</div>
          </div>
        </label>
        <input type="file" id="fileInput" name="prescription_file" accept=".jpg,.jpeg,.png,.pdf" class="hidden" data-file-preview="#fileName" required>
      </div>

      <div class="ps-card p-4">
        <h6 class="bold mb-1"><?= icon('list', 'me-2') ?>Pharmacy Notes</h6>
        <p class="muted small mb-3">Add specific instructions, allergy alerts, or preferred refill dates for our pharmacists.</p>
        <textarea name="pharmacy_notes" class="field mb-3" rows="4" placeholder="Example: Please provide the generic brand if available. I will be traveling next week and need a 90-day supply..."></textarea>
        <div class="flex between middle wrap gap-2">
          <div class="check-row">
            <input class="check-box" type="checkbox" name="urgent_refill" value="1" id="urgentRefill">
            <label class="check-text" for="urgentRefill">Mark as Urgent Refill</label>
          </div>
          <button type="submit" class="btn btn-ps-primary px-4">Submit Prescription <?= icon('send', 'ms-2') ?></button>
        </div>
      </div>
    </form>
  </div>

  <div class="col-lg-4">
    <div class="flex between middle mb-3">
      <h6 class="bold mb-0">Recently Uploaded</h6>
      <a href="<?= BASE_URL ?>/orders" class="small">View All</a>
    </div>
    <?php if (empty($history)): ?>
      <div class="ps-empty-state ps-card mb-3">
        <?= icon('scroll-text') ?>
        <p class="muted mb-0 mt-2 small">No prescriptions uploaded yet.</p>
      </div>
    <?php else: ?>
      <div class="ps-card mb-3">
        <?php foreach (array_slice($history, 0, 3) as $rx): ?>
          <div class="flex gap-3 p-3 border-bottom">
            <div class="ps-stat-icon noshrink"><?= icon(str_ends_with($rx['file_name'], '.pdf') ? 'file-text' : 'image') ?></div>
            <div class="grow">
              <div class="flex between">
                <span class="semibold small"><?= htmlspecialchars($rx['file_name']) ?></span>
                <span class="ps-status ps-status-<?= $rx['status'] ?>" style="font-size:.65rem;"><?= strtoupper($rx['status']) ?></span>
              </div>
              <div class="muted small">Uploaded: <?= date('M j, g:i A', strtotime($rx['uploaded_at'])) ?></div>
              <a href="<?= BASE_URL ?>/prescription/status/<?= $rx['id'] ?>" class="small me-2"><?= icon('eye', 'me-1') ?>View</a>
              <?php if ($rx['status'] === 'approved'): ?><a href="<?= BASE_URL ?>/catalog" class="small"><?= icon('shopping-cart', 'me-1') ?>Order</a><?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="ps-banner p-4">
      <div class="flex middle gap-2 mb-2">
        <?= icon('shield-plus', 'size-4') ?>
        <h6 class="bold mb-0">Our Quality Promise</h6>
      </div>
      <p class="small mb-3">Each prescription is double-checked by a senior clinical pharmacist for dosage accuracy and potential drug interactions. Your health is our priority.</p>
      <div class="flex middle gap-2">
        <div class="ps-avatar sm" style="background:#fff; color: var(--ps-primary);">SJ</div>
        <div>
          <div class="semibold small">Dr. Sarah Jenkins</div>
          <div class="small" style="opacity:.85;">Chief Pharmacist</div>
        </div>
      </div>
    </div>
  </div>
</div>
