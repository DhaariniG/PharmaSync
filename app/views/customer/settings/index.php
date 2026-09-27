<?php
/*
 * Settings. Every button here does something real - see
 * CustomerSettingsController. Controls that would need the shared login
 * (two-factor sign-in, signing out other devices) are not shown until the
 * login supports them.
 */
$memberSince  = !empty($account['created_at']) ? date('F Y', strtotime($account['created_at'])) : null;
$signedInAt   = !empty($account['last_login']) ? date('d M Y, g:i A', strtotime($account['last_login'])) : null;
$pwChangedAt  = !empty($settings['password_changed_at']) ? date('d M Y', strtotime($settings['password_changed_at'])) : null;
$pharmacyTel  = '+94778764530';        // same number as "Call the Pharmacy" on the upload page
$pharmacyTelShown = '+94 77 876 4530';
?>
<h3 class="bold mb-1">Settings</h3>
<p class="muted mb-4">Manage your password, notifications, data and account.</p>

<div class="row g-4">
  <div class="col-lg-3">
    <nav class="ps-card p-2 mb-3 ps-settings-nav" aria-label="Settings sections">
      <a href="#security" class="flex between middle p-2 rounded nounderline text-dark">
        <span><?= icon('shield-half', 'me-2') ?>Account Security</span><?= icon('chevron-right', 'small') ?>
      </a>
      <a href="#notifications" class="flex between middle p-2 rounded nounderline text-dark">
        <span><?= icon('bell', 'me-2') ?>Notifications</span><?= icon('chevron-right', 'small') ?>
      </a>
      <a href="#privacy" class="flex between middle p-2 rounded nounderline text-dark">
        <span><?= icon('lock', 'me-2') ?>Your Data &amp; Account</span><?= icon('chevron-right', 'small') ?>
      </a>
      <a href="#help" class="flex between middle p-2 rounded nounderline text-dark">
        <span><?= icon('circle-question-mark', 'me-2') ?>Help &amp; Support</span><?= icon('chevron-right', 'small') ?>
      </a>
    </nav>

    <div class="ps-card p-3 small">
      <div class="muted upper mb-2">Your account</div>
      <div class="semibold"><?= e($account['full_name'] ?? ($user['name'] ?? '')) ?></div>
      <div class="muted mb-2"><?= e($account['email'] ?? ($user['email'] ?? '')) ?></div>
      <?php if ($memberSince): ?><div class="muted">Member since <?= e($memberSince) ?></div><?php endif; ?>
      <a href="<?= BASE_URL ?>/customer/profile" class="block mt-2 semibold">Edit profile</a>
    </div>
  </div>

  <div class="col-lg-9">

    <!-- ================= Account security ================= -->
    <div class="ps-card p-4 mb-3" id="security">
      <h6 class="bold mb-2">Account Security</h6>

      <details class="ps-setting">
        <summary class="flex between middle py-3 border-bottom">
          <div>
            <div class="semibold">Change Password</div>
            <div class="muted small"><?= $pwChangedAt ? 'Last changed ' . e($pwChangedAt) : 'Use at least 8 characters' ?></div>
          </div>
          <span class="btn btn-ps-outline btn-sm">Update</span>
        </summary>
        <form method="POST" action="<?= BASE_URL ?>/customer/settings/password" class="py-3 border-bottom ps-setting-form" data-validate novalidate>
          <?= csrf_field() ?>
          <label class="small semibold" for="curPw">Current password</label>
          <input type="password" name="current_password" id="curPw" class="field mb-2" autocomplete="current-password" required>
          <label class="small semibold" for="newPw">New password</label>
          <input type="password" name="new_password" id="newPw" class="field mb-2" autocomplete="new-password" minlength="8" required>
          <label class="small semibold" for="confPw">Confirm new password</label>
          <input type="password" name="confirm_password" id="confPw" class="field mb-3" autocomplete="new-password" minlength="8" required>
          <button type="submit" class="btn btn-ps-primary btn-sm">Change Password</button>
        </form>
      </details>

      <div class="flex between middle py-3 wrap gap-2">
        <div>
          <div class="semibold">This device</div>
          <div class="muted small"><?= e($device) ?><?= $signedInAt ? ' &middot; signed in ' . e($signedInAt) : '' ?></div>
        </div>
        <!-- The shared logout route. -->
        <form method="POST" action="<?= url('/' . AUTH_SLUG . '/logout') ?>" class="inline">
          <?= csrf_field() ?>
          <button type="submit" class="btn btn-ps-outline btn-sm"><?= icon('log-out', 'me-1') ?>Sign out</button>
        </form>
      </div>
    </div>

    <!-- ================= Notifications ================= -->
    <form method="POST" action="<?= BASE_URL ?>/customer/settings/notifications" class="ps-card p-4 mb-3" id="notifications">
      <?= csrf_field() ?>
      <h6 class="bold mb-1">Notifications</h6>
      <p class="muted small mb-2">Choose which alerts appear on your Notifications page and bell. Switched-off alerts are hidden, not deleted.</p>

      <?php $last = array_key_last(CustomerSettings::NOTIFICATION_TYPES); ?>
      <?php foreach (CustomerSettings::NOTIFICATION_TYPES as $key => [$label, $hint]): ?>
        <div class="flex between middle py-3 <?= $key === $last ? '' : 'border-bottom' ?>">
          <label for="<?= e($key) ?>" class="grow" style="cursor:pointer;">
            <div class="semibold"><?= e($label) ?></div>
            <div class="muted small"><?= e($hint) ?></div>
          </label>
          <span class="switch"><input class="check-box" type="checkbox" name="<?= e($key) ?>" id="<?= e($key) ?>" value="1" <?= !empty($settings[$key]) ? 'checked' : '' ?>></span>
        </div>
      <?php endforeach; ?>

      <button type="submit" class="btn btn-ps-primary btn-sm mt-2">Save Preferences</button>
    </form>

    <!-- ================= Your data & account ================= -->
    <div class="ps-card p-4 mb-3" id="privacy">
      <h6 class="bold mb-1">Your Data &amp; Account</h6>
      <p class="muted small mb-2">Allergies on your family profiles are always checked at checkout, and order and prescription records are kept as the law requires.</p>

      <div class="flex between middle py-3 border-bottom wrap gap-2">
        <div>
          <div class="semibold">Download my data</div>
          <div class="muted small">A file with your profile, family members, addresses, orders and prescriptions</div>
        </div>
        <a href="<?= BASE_URL ?>/customer/settings/download-data" class="btn btn-ps-outline btn-sm"><?= icon('download', 'me-1') ?>Download</a>
      </div>

      <details class="ps-setting">
        <summary class="flex between middle py-3">
          <div>
            <div class="semibold text-danger">Close my account</div>
            <div class="muted small">You won't be able to sign in. Records are kept, and the pharmacy can reopen it.</div>
          </div>
          <span class="btn btn-danger btn-sm">Close Account</span>
        </summary>
        <form method="POST" action="<?= BASE_URL ?>/customer/settings/close-account" class="py-3 ps-setting-form"
              onsubmit="return confirm('Close your PharmaSync account? You will be signed out.');">
          <?= csrf_field() ?>
          <?php if ($openOrders > 0): ?>
            <div class="note note-warn small mb-2">You have <?= (int) $openOrders ?> order<?= $openOrders === 1 ? '' : 's' ?> in progress. Your account can be closed once <?= $openOrders === 1 ? 'it is' : 'they are' ?> delivered or cancelled.</div>
          <?php endif; ?>
          <label class="small semibold" for="closePw">Your password</label>
          <input type="password" name="password" id="closePw" class="field mb-2" autocomplete="current-password" required>
          <label class="small semibold" for="closeText">Type CLOSE to confirm</label>
          <input type="text" name="confirm_text" id="closeText" class="field mb-3" autocomplete="off" required>
          <button type="submit" class="btn btn-danger btn-sm">Close My Account</button>
        </form>
      </details>
    </div>

    <!-- ================= Help ================= -->
    <div class="ps-card p-4 mb-3" id="help">
      <h6 class="bold mb-3">Help &amp; Support</h6>
      <div class="row g-3">
        <div class="col-md-4">
          <a href="tel:<?= e($pharmacyTel) ?>" class="ps-card p-3 block h-100 nounderline text-dark">
            <div class="semibold small"><?= icon('phone', 'me-2 text-primary') ?>Call the pharmacy</div>
            <div class="muted small"><?= e($pharmacyTelShown) ?>, during opening hours</div>
          </a>
        </div>
        <div class="col-md-4">
          <div class="ps-card p-3 h-100">
            <div class="semibold small"><?= icon('map-pin', 'me-2 text-primary') ?>Visit the pharmacy</div>
            <div class="muted small"><?= e(STORE_ADDRESS) ?></div>
            <div class="muted small"><?= e(STORE_HOURS) ?></div>
          </div>
        </div>
        <div class="col-md-4">
          <a href="<?= BASE_URL ?>/customer/orders" class="ps-card p-3 block h-100 nounderline text-dark">
            <div class="semibold small"><?= icon('package', 'me-2 text-primary') ?>Problem with an order</div>
            <div class="muted small">Track, cancel, change the delivery time or reorder</div>
          </a>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  // Highlight the section you jumped to in the left menu.
  (function () {
    var links = document.querySelectorAll('.ps-settings-nav a');
    function mark() {
      var hash = location.hash || '#security';
      links.forEach(function (a) { a.classList.toggle('active', a.getAttribute('href') === hash); });
    }
    window.addEventListener('hashchange', mark);
    mark();
  })();
</script>
