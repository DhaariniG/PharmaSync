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
      <form method="POST" action="<?= BASE_URL ?>/profile/update">
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

<div class="ps-card p-4 mb-4" id="healthProfile">
  <div class="flex between middle mb-3">
    <h6 class="bold mb-0"><?= icon('clipboard-plus', 'me-2') ?>Health Profile</h6>
  </div>
  <div class="row g-3">
    <div class="col-md-6">
      <div class="ps-card p-3 h-100">
        <div class="small bold muted mb-2"><?= icon('triangle-alert', 'text-danger me-1') ?>Known Allergies</div>
        <div class="flex wrap gap-2 mb-2">
          <?php foreach (($user['allergies'] ?? []) as $a): ?>
            <span class="tag rounded-pill" style="background:#fde8e6; color: var(--ps-danger);"><?= htmlspecialchars($a) ?></span>
          <?php endforeach; ?>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/profile/add-allergy" class="flex gap-2">
          <?= csrf_field() ?>
          <input type="text" name="value" class="field field-sm" placeholder="Add allergy">
          <button type="submit" class="btn btn-ps-outline btn-sm">Add</button>
        </form>
      </div>
    </div>
    <div class="col-md-6">
      <div class="ps-card p-3 h-100">
        <div class="small bold muted mb-2"><?= icon('activity', 'me-1') ?>Chronic Conditions</div>
        <div class="flex wrap gap-2 mb-2">
          <?php foreach (($user['conditions'] ?? []) as $c): ?>
            <span class="tag rounded-pill ps-badge-otc"><?= htmlspecialchars($c) ?></span>
          <?php endforeach; ?>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/profile/add-condition" class="flex gap-2">
          <?= csrf_field() ?>
          <input type="text" name="value" class="field field-sm" placeholder="Add condition">
          <button type="submit" class="btn btn-ps-outline btn-sm">Add</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="ps-card p-4 mb-4">
  <div class="flex between middle mb-3">
    <h6 class="bold mb-0"><?= icon('map-pin', 'me-2') ?>Saved Addresses</h6>
  </div>
  <?php foreach ($addresses as $addr): ?>
    <div class="flex between top p-3 border rounded mb-2">
      <div>
        <span class="semibold"><?= htmlspecialchars($addr['label']) ?></span>
        <?php if ($addr['is_default']): ?><span class="tag ps-badge-otc ms-1">Default</span><?php endif; ?>
        <div class="muted small mt-1"><?= htmlspecialchars($addr['line1']) ?>, <?= htmlspecialchars($addr['city']) ?></div>
      </div>
      <?= icon('ellipsis-vertical', 'muted') ?>
    </div>
  <?php endforeach; ?>
</div>

<div class="ps-card p-4" style="background:#fdf3f2; border-color:#f4c9c4;">
  <div class="flex between middle wrap gap-3">
    <div>
      <h6 class="bold text-danger mb-1">Privacy & Data</h6>
      <p class="muted small mb-0">Request a copy of your health records or manage account deletion. We handle your medical data with enterprise-grade encryption.</p>
    </div>
    <div class="flex gap-2">
      <button type="button" class="btn btn-ps-outline btn-sm">Export Health Data</button>
      <button type="button" class="btn btn-danger btn-sm">Close Account</button>
    </div>
  </div>
</div>

<div class="ps-card p-4 mt-3">
  <div class="flex between middle mb-1">
    <h6 class="bold mb-0"><?= icon('house', 'me-2') ?>Family Profiles</h6>
    <span class="muted small"><?= count($members) ?> people</span>
  </div>
  <p class="muted small mb-3">People you order medicine for. A prescription is uploaded against one of these, so the pharmacist knows who it is for.</p>

  <div class="row g-2 mb-3">
    <?php foreach ($members as $m): ?>
      <div class="col-md-6">
        <div class="ps-card p-3 h-100 flex middle gap-3">
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
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <form method="POST" action="<?= BASE_URL ?>/profile/add-member" class="row g-2">
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
    <div class="col-md-1 flex bottom">
      <button type="submit" class="btn btn-ps-primary btn-sm w-100">Add</button>
    </div>
  </form>
</div>
