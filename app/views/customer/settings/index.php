<h3 class="bold mb-1">Settings</h3>
<p class="muted mb-4">Manage your account security, notification preferences, and privacy controls.</p>

<div class="row g-4">
  <div class="col-lg-3">
    <div class="ps-card p-2 mb-3">
      <a href="#" class="flex between middle p-2 rounded nounderline text-dark" style="background: var(--ps-primary-light); color: var(--ps-primary-dark) !important;">
        <span><?= icon('shield-half', 'me-2') ?>Account Security</span><?= icon('chevron-right', 'small') ?>
      </a>
      <a href="#notifications" class="flex between middle p-2 rounded nounderline text-dark">
        <span><?= icon('bell', 'me-2') ?>Notifications</span><?= icon('chevron-right', 'small') ?>
      </a>
      <a href="#privacy" class="flex between middle p-2 rounded nounderline text-dark">
        <span><?= icon('lock', 'me-2') ?>Privacy Controls</span><?= icon('chevron-right', 'small') ?>
      </a>
      <a href="#help" class="flex between middle p-2 rounded nounderline text-dark">
        <span><?= icon('circle-question-mark', 'me-2') ?>Help & Support</span><?= icon('chevron-right', 'small') ?>
      </a>
    </div>
    <div class="ps-security-score p-4">
      <div class="small">Security Score</div>
      <div class="size-4 bold mb-2">Strong</div>
      <div class="meter mb-2" style="height:6px; background: rgba(255,255,255,.3);">
        <div class="meter-fill bg-white" style="width: 80%;"></div>
      </div>
      <div class="small">Your account is well protected. Add 2FA to reach 100%.</div>
    </div>
  </div>

  <div class="col-lg-9">
    <div class="ps-card p-4 mb-3">
      <h6 class="bold mb-3">Account Security</h6>

      <div class="flex between middle py-3 border-bottom">
        <div>
          <div class="semibold">Change Password</div>
          <div class="muted small">Last updated 3 months ago</div>
        </div>
        <button type="button" class="btn btn-ps-outline btn-sm">Update</button>
      </div>

      <div class="flex between middle py-3 border-bottom">
        <div>
          <div class="semibold">Two-Factor Authentication (2FA)</div>
          <div class="muted small">Recommended for high-security prescriptions</div>
        </div>
        <label class="switch"><input class="check-box" type="checkbox"></label>
      </div>

      <div class="flex between middle py-3">
        <div>
          <div class="semibold">Active Sessions</div>
          <div class="muted small">Logged in on 2 devices</div>
        </div>
        <a href="#" class="small semibold">Manage</a>
      </div>
    </div>

    <div class="ps-card p-4 mb-3">
      <h6 class="bold mb-3" id="notifications">Notifications</h6>

      <div class="flex between middle py-3 border-bottom">
        <div>
          <div class="semibold">Order updates</div>
          <div class="muted small">Packing, dispatch and delivery alerts</div>
        </div>
        <label class="switch"><input class="check-box" type="checkbox" checked></label>
      </div>

      <div class="flex between middle py-3 border-bottom">
        <div>
          <div class="semibold">Prescription updates</div>
          <div class="muted small">Approvals, prepared orders and suggested alternatives</div>
        </div>
        <label class="switch"><input class="check-box" type="checkbox" checked></label>
      </div>

      <div class="flex between middle py-3 border-bottom">
        <div>
          <div class="semibold">Refill reminders</div>
          <div class="muted small">Tells you when a repeat medicine is running low</div>
        </div>
        <label class="switch"><input class="check-box" type="checkbox" checked></label>
      </div>

      <div class="flex between middle py-3">
        <div>
          <div class="semibold">Offers and health tips</div>
          <div class="muted small">Occasional promotions and seasonal advice</div>
        </div>
        <label class="switch"><input class="check-box" type="checkbox"></label>
      </div>
    </div>

    <div class="ps-card p-4 mb-3">
      <h6 class="bold mb-3" id="privacy">Privacy Controls</h6>

      <div class="flex between middle py-3 border-bottom">
        <div>
          <div class="semibold">Share allergies with the pharmacist</div>
          <div class="muted small">Lets the pharmacist check your prescription against your allergies</div>
        </div>
        <label class="switch"><input class="check-box" type="checkbox" checked></label>
      </div>

      <div class="flex between middle py-3 border-bottom">
        <div>
          <div class="semibold">Keep my order history</div>
          <div class="muted small">Needed for the reorder button and past receipts</div>
        </div>
        <label class="switch"><input class="check-box" type="checkbox" checked></label>
      </div>

      <div class="flex between middle py-3 border-bottom">
        <div>
          <div class="semibold">Download my data</div>
          <div class="muted small">Get a copy of your profile, orders and prescriptions</div>
        </div>
        <button type="button" class="btn btn-ps-outline btn-sm">Download</button>
      </div>

      <div class="flex between middle py-3">
        <div>
          <div class="semibold text-danger">Delete my account</div>
          <div class="muted small">Removes your profile. Prescription records are kept as the law requires.</div>
        </div>
        <button type="button" class="btn btn-danger btn-sm">Delete</button>
      </div>
    </div>

    <div class="ps-card p-4 mb-3">
      <h6 class="bold mb-3" id="help">Help &amp; Support</h6>
      <div class="row g-3">
        <div class="col-md-6">
          <a href="mailto:support@pharmasync.test" class="ps-card p-3 block h-100 nounderline text-dark">
            <div class="semibold small"><?= icon('messages-square', 'me-2 text-primary') ?>Chat with a pharmacist</div>
            <div class="muted small">Dosage advice and alternative queries</div>
          </a>
        </div>
        <div class="col-md-6">
          <a href="tel:+94112345678" class="ps-card p-3 block h-100 nounderline text-dark">
            <div class="semibold small"><?= icon('phone', 'me-2 text-primary') ?>Call the pharmacy</div>
            <div class="muted small">+94 11 234 5678, during opening hours</div>
          </a>
        </div>
        <div class="col-md-6">
          <a href="<?= BASE_URL ?>/orders" class="ps-card p-3 block h-100 nounderline text-dark">
            <div class="semibold small"><?= icon('package', 'me-2 text-primary') ?>Problem with an order</div>
            <div class="muted small">Track, reorder or report an issue</div>
          </a>
        </div>
        <div class="col-md-6">
          <a href="#" class="ps-card p-3 block h-100 nounderline text-dark">
            <div class="semibold small"><?= icon('circle-question-mark', 'me-2 text-primary') ?>Common questions</div>
            <div class="muted small">Delivery, pickup, prescriptions and refunds</div>
          </a>
        </div>
      </div>
    </div>

    <div class="note note-danger flex between middle wrap gap-2">
      <div>
        <strong><?= icon('triangle-alert', 'me-1') ?>Critical Alert</strong>
        <div class="small">You haven't set a backup recovery email yet. In case of lockout, you may lose access to your medical history.</div>
      </div>
      <a href="<?= BASE_URL ?>/profile" class="bold small text-danger upper">Set Email Now</a>
    </div>
  </div>
</div>
