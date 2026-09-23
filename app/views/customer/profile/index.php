<div class="flex between top mb-4 wrap gap-2">
  <div>
    <h3 class="bold mb-1">Profile Settings</h3>
    <p class="muted mb-0">Manage your personal health data and account security.</p>
  </div>
</div>

<div class="row g-4 mb-1">
  <div class="col-lg-8">
    <div class="ps-card p-4">
      <div class="flex between middle mb-3">
        <h6 class="bold mb-0"><?= icon('id-card', 'me-2') ?>Personal Information</h6>
      </div>
      <form method="POST" action="<?= BASE_URL ?>/customer/profile/update">
          <?= csrf_field() ?>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="field-label small muted">Full Name</label>
            <input type="text" name="name" class="field" value="<?= htmlspecialchars($user['name']) ?>">
          </div>
          <div class="col-md-6">
            <label class="field-label small muted">Email Address</label>
            <input type="email" class="field" value="<?= htmlspecialchars($user['email']) ?>" disabled>
          </div>
          <div class="col-md-6">
            <label class="field-label small muted">Phone Number</label>
            <input type="tel" name="phone" class="field" value="<?= htmlspecialchars($user['phone']) ?>">
          </div>
          <div class="col-md-6">
            <label class="field-label small muted">Date of Birth</label>
            <input type="text" class="field" value="<?= !empty($user['dob']) ? date('M j, Y', strtotime($user['dob'])) : 'Not set' ?>" disabled>
          </div>
        </div>
        <button type="submit" class="btn btn-ps-primary mt-3"><?= icon('save', 'me-2') ?>Save Changes</button>
      </form>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="ps-banner p-4 h-100">
      <div class="flex middle gap-2 mb-2">
        <?= icon('heart-pulse', 'size-4') ?>
        <h6 class="bold mb-0">Health Insights</h6>
      </div>
      <p class="small mb-3">Your health profile is <?= (int) ($user['profile_complete'] ?? 0) ?>% complete. Update your conditions for better personalized care.</p>
      <div class="meter mb-3" style="height:6px; background: rgba(255,255,255,.3);">
        <div class="meter-fill bg-white" style="width: <?= (int) ($user['profile_complete'] ?? 0) ?>%;"></div>
      </div>
      <a href="#healthProfile" class="btn btn-plain btn-sm">Complete Profile</a>
    </div>
  </div>
</div>

<?php
/*
 * The account holder's allergies and conditions are the health flags of the
 * family member marked 'Self', so there is only one place this data lives.
 * Everyone else's are managed on their own page.
 */
$selfAllergies  = $selfMember ? FamilyMember::allergiesOf($selfMember) : [];
$selfConditions = $selfMember ? FamilyMember::conditionsOf($selfMember) : [];
?>

<div class="ps-card p-4 mb-4" id="healthProfile">
  <div class="flex between middle mb-1 wrap gap-2">
    <h6 class="bold mb-0"><?= icon('clipboard-plus', 'me-2') ?>Health Profile</h6>
    <?php if ($selfMember): ?>
      <span class="muted small"><?= htmlspecialchars($selfMember['name']) ?> &middot; account holder</span>
    <?php endif; ?>
  </div>
  <p class="muted small mb-3">What the pharmacist checks a prescription against. Each family member keeps their own list &mdash; open their card below.</p>

  <?php if (!$selfMember): ?>
    <div class="note note-warn mb-0">
      Add your own profile under Family Profiles (relationship &ldquo;Self&rdquo;) to record your allergies and conditions here.
    </div>
  <?php else: ?>
    <div class="row g-3">

      <div class="col-md-6">
        <div class="ps-card p-3 h-100">
          <div class="small bold muted mb-2"><?= icon('triangle-alert', 'text-danger me-1') ?>Known Allergies</div>
          <div class="flex wrap gap-2 mb-2">
            <?php foreach ($selfAllergies as $a): ?>
              <span class="tag rounded-pill flex middle gap-1" style="background:#fde8e6; color: var(--ps-danger);">
                <?= htmlspecialchars($a['value']) ?>
                <form method="POST" action="<?= BASE_URL ?>/customer/profile/delete-flag" class="inline"
                      onsubmit="return confirm('Remove <?= htmlspecialchars($a['value'], ENT_QUOTES) ?> from your allergies?');">
                  <?= csrf_field() ?>
                  <input type="hidden" name="member_id" value="<?= (int) $selfMember['id'] ?>">
                  <input type="hidden" name="flag_id" value="<?= (int) $a['id'] ?>">
                  <button type="submit" class="btn-text small" aria-label="Remove <?= htmlspecialchars($a['value'], ENT_QUOTES) ?>">&times;</button>
                </form>
              </span>
            <?php endforeach; ?>
            <?php if (!$selfAllergies): ?>
              <span class="muted small">None recorded.</span>
            <?php endif; ?>
          </div>
          <form method="POST" action="<?= BASE_URL ?>/customer/profile/add-allergy" class="flex gap-2">
            <?= csrf_field() ?>
            <input type="text" name="value" class="field field-sm" maxlength="120" placeholder="Add allergy" required>
            <button type="submit" class="btn btn-ps-outline btn-sm">Add</button>
          </form>
        </div>
      </div>

      <div class="col-md-6">
        <div class="ps-card p-3 h-100">
          <div class="small bold muted mb-2"><?= icon('activity', 'me-1') ?>Chronic Conditions</div>
          <div class="flex wrap gap-2 mb-2">
            <?php foreach ($selfConditions as $c): ?>
              <span class="tag rounded-pill ps-badge-otc flex middle gap-1">
                <?= htmlspecialchars($c['value']) ?>
                <form method="POST" action="<?= BASE_URL ?>/customer/profile/delete-flag" class="inline"
                      onsubmit="return confirm('Remove <?= htmlspecialchars($c['value'], ENT_QUOTES) ?> from your conditions?');">
                  <?= csrf_field() ?>
                  <input type="hidden" name="member_id" value="<?= (int) $selfMember['id'] ?>">
                  <input type="hidden" name="flag_id" value="<?= (int) $c['id'] ?>">
                  <button type="submit" class="btn-text small" aria-label="Remove <?= htmlspecialchars($c['value'], ENT_QUOTES) ?>">&times;</button>
                </form>
              </span>
            <?php endforeach; ?>
            <?php if (!$selfConditions): ?>
              <span class="muted small">None recorded.</span>
            <?php endif; ?>
          </div>
          <form method="POST" action="<?= BASE_URL ?>/customer/profile/add-condition" class="flex gap-2">
            <?= csrf_field() ?>
            <input type="text" name="value" class="field field-sm" maxlength="120" placeholder="Add condition" required>
            <button type="submit" class="btn btn-ps-outline btn-sm">Add</button>
          </form>
        </div>
      </div>

    </div>
  <?php endif; ?>
</div>

<div class="ps-card p-4 mb-4">
  <div class="flex between middle mb-1 wrap gap-2">
    <h6 class="bold mb-0"><?= icon('map-pin', 'me-2') ?>Saved Addresses</h6>
    <span class="muted small"><?= count($addresses) ?> saved</span>
  </div>
  <p class="muted small mb-3">The default address is the one pre-selected at checkout.</p>

  <?php foreach ($addresses as $addr): ?>
    <div class="p-3 border rounded mb-2">
      <div class="flex between top wrap gap-2">
        <div>
          <span class="semibold"><?= htmlspecialchars($addr['label']) ?></span>
          <?php if ($addr['is_default']): ?><span class="tag ps-badge-otc ms-1">Default</span><?php endif; ?>
          <div class="muted small mt-1">
            <?= htmlspecialchars($addr['line1']) ?>, <?= htmlspecialchars($addr['city']) ?><?= $addr['postcode'] !== '' ? ' ' . htmlspecialchars($addr['postcode']) : '' ?>
          </div>
          <?php if ($addr['phone'] !== ''): ?>
            <div class="muted small"><?= htmlspecialchars($addr['phone']) ?></div>
          <?php endif; ?>
        </div>

        <div class="flex middle gap-2 wrap noshrink">
          <?php if (!$addr['is_default']): ?>
            <form method="POST" action="<?= BASE_URL ?>/customer/profile/default-address/<?= (int) $addr['id'] ?>" class="inline">
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-plain btn-sm">Make default</button>
            </form>
          <?php endif; ?>

          <form method="POST" action="<?= BASE_URL ?>/customer/profile/delete-address/<?= (int) $addr['id'] ?>" class="inline"
                onsubmit="return confirm('Delete the <?= htmlspecialchars($addr['label'], ENT_QUOTES) ?> address?');">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
          </form>
        </div>
      </div>

      <details class="mt-2">
        <summary class="small semibold text-primary">Edit this address</summary>
        <form method="POST" action="<?= BASE_URL ?>/customer/profile/update-address/<?= (int) $addr['id'] ?>" class="row g-2 mt-1">
          <?= csrf_field() ?>
          <div class="col-md-3">
            <label class="field-label small">Label</label>
            <select name="label" class="field field-sm">
              <?php foreach (Address::LABELS as $l): ?>
                <option value="<?= $l ?>" <?= $addr['label'] === $l ? 'selected' : '' ?>><?= $l ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-5">
            <label class="field-label small">Street address</label>
            <input type="text" name="line1" class="field field-sm" maxlength="255" value="<?= htmlspecialchars($addr['line1']) ?>" required>
          </div>
          <div class="col-md-4">
            <label class="field-label small">City</label>
            <input type="text" name="city" class="field field-sm" maxlength="100" value="<?= htmlspecialchars($addr['city']) ?>" required>
          </div>
          <div class="col-md-3">
            <label class="field-label small">Postcode</label>
            <input type="text" name="postcode" class="field field-sm" maxlength="20" value="<?= htmlspecialchars($addr['postcode']) ?>">
          </div>
          <div class="col-md-5">
            <label class="field-label small">Contact phone</label>
            <input type="tel" name="phone" class="field field-sm" maxlength="30" value="<?= htmlspecialchars($addr['phone']) ?>">
          </div>
          <div class="col-md-4 flex bottom gap-2">
            <label class="check-row small mt-3">
              <input type="checkbox" name="is_default" value="1" <?= $addr['is_default'] ? 'checked' : '' ?>>
              <span class="check-text">Default</span>
            </label>
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-ps-primary btn-sm w-100 mt-2"><?= icon('save', 'me-2') ?>Save</button>
          </div>
        </form>
      </details>
    </div>
  <?php endforeach; ?>

  <?php if (!$addresses): ?>
    <div class="muted small mb-3">No addresses saved yet. Add one below so checkout has somewhere to deliver.</div>
  <?php endif; ?>

  <details class="mt-2">
    <summary class="small semibold text-primary">Add a new address</summary>
    <form method="POST" action="<?= BASE_URL ?>/customer/profile/add-address" class="row g-2 mt-1">
      <?= csrf_field() ?>
      <div class="col-md-3">
        <label class="field-label small" for="newLabel">Label</label>
        <select id="newLabel" name="label" class="field field-sm">
          <?php foreach (Address::LABELS as $l): ?>
            <option value="<?= $l ?>"><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-5">
        <label class="field-label small" for="newLine1">Street address</label>
        <input type="text" id="newLine1" name="line1" class="field field-sm" maxlength="255" required>
      </div>
      <div class="col-md-4">
        <label class="field-label small" for="newCity">City</label>
        <input type="text" id="newCity" name="city" class="field field-sm" maxlength="100" required>
      </div>
      <div class="col-md-3">
        <label class="field-label small" for="newPostcode">Postcode</label>
        <input type="text" id="newPostcode" name="postcode" class="field field-sm" maxlength="20">
      </div>
      <div class="col-md-5">
        <label class="field-label small" for="newPhone">Contact phone</label>
        <input type="tel" id="newPhone" name="phone" class="field field-sm" maxlength="30">
      </div>
      <div class="col-md-4">
        <label class="check-row small mt-3">
          <input type="checkbox" name="is_default" value="1">
          <span class="check-text">Make this my default</span>
        </label>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-ps-primary btn-sm w-100 mt-2">Add address</button>
      </div>
    </form>
  </details>
</div>

<div class="ps-card p-4 mb-4">
  <div class="flex between middle mb-1">
    <h6 class="bold mb-0"><?= icon('house', 'me-2') ?>Family Profiles</h6>
    <span class="muted small"><?= count($members) ?> people</span>
  </div>
  <p class="muted small mb-3">People you order medicine for. A prescription is uploaded against one of these, so the pharmacist knows who it is for &mdash; and can check it against that person's own allergies.</p>

  <div class="row g-2 mb-3">
    <?php foreach ($members as $m): ?>
      <?php
        $mAllergies  = FamilyMember::allergiesOf($m);
        $mConditions = FamilyMember::conditionsOf($m);
      ?>
      <div class="col-md-6">
        <div class="ps-card p-3 h-100">
          <div class="flex middle gap-3">
            <div class="ps-avatar sm"><?= htmlspecialchars(mb_strtoupper(mb_substr($m['name'], 0, 1))) ?></div>
            <div class="grow">
              <div class="semibold small"><?= htmlspecialchars($m['name']) ?></div>
              <div class="muted small">
                <?= htmlspecialchars($m['relationship'] === 'Self' ? 'Account holder' : $m['relationship']) ?>
                <?php if (!empty($m['date_of_birth'])): ?>
                  &middot; born <?= date('Y', strtotime($m['date_of_birth'])) ?>
                <?php endif; ?>
              </div>
            </div>
            <a href="<?= BASE_URL ?>/customer/profile/member/<?= (int) $m['id'] ?>" class="btn btn-ps-outline btn-sm noshrink">Manage</a>
          </div>

          <div class="flex wrap gap-2 mt-2">
            <?php foreach ($mAllergies as $a): ?>
              <span class="tag rounded-pill" style="background:#fde8e6; color: var(--ps-danger);"><?= htmlspecialchars($a['value']) ?></span>
            <?php endforeach; ?>
            <?php foreach ($mConditions as $c): ?>
              <span class="tag rounded-pill ps-badge-otc"><?= htmlspecialchars($c['value']) ?></span>
            <?php endforeach; ?>
            <?php if (!$mAllergies && !$mConditions): ?>
              <span class="muted small">No allergies or conditions recorded.</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <form method="POST" action="<?= BASE_URL ?>/customer/profile/add-member" class="row g-2">
    <?= csrf_field() ?>
    <div class="col-md-5">
      <label class="field-label small" for="memberName">Name</label>
      <input type="text" id="memberName" name="member_name" class="field field-sm" maxlength="80" required>
    </div>
    <div class="col-md-3">
      <label class="field-label small" for="relationship">Relationship</label>
      <select id="relationship" name="relationship" class="field field-sm">
        <?php foreach (FamilyMember::RELATIONSHIPS as $r): ?>
          <?php if ($r === 'Self') continue; ?>
          <option value="<?= $r ?>"><?= $r ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="field-label small" for="dob">Date of birth</label>
      <input type="date" id="dob" name="date_of_birth" class="field field-sm" max="<?= date('Y-m-d') ?>">
    </div>
    <div class="col-md-4 flex bottom">
      <button type="submit" class="btn btn-ps-primary btn-sm w-100 mt-2">Add family member</button>
    </div>
  </form>
</div>

<div class="ps-card p-4" style="background:#fdf3f2; border-color:#f4c9c4;">
  <div class="flex between middle wrap gap-3">
    <div>
      <h6 class="bold text-danger mb-1">Privacy &amp; Data</h6>
      <p class="muted small mb-0">Request a copy of your health records or manage account deletion. We handle your medical data with enterprise-grade encryption.</p>
    </div>
    <div class="flex gap-2">
      <button type="button" class="btn btn-ps-outline btn-sm">Export Health Data</button>
      <button type="button" class="btn btn-danger btn-sm">Close Account</button>
    </div>
  </div>
</div>
