<?php
/**
 * One family member: read, edit, their own allergies and chronic
 * conditions, and delete. Reached from the Family Profiles card on
 * /customer/profile.
 */
$isSelf = $member['relationship'] === 'Self';
?>

<nav aria-label="breadcrumb">
  <ol class="crumbs small">
    <li class="crumb"><a href="<?= BASE_URL ?>/customer/profile">Profile</a></li>
    <li class="crumb active"><?= htmlspecialchars($member['name']) ?></li>
  </ol>
</nav>

<div class="flex between top mb-4 wrap gap-2">
  <div class="flex middle gap-3">
    <div class="ps-avatar"><?= htmlspecialchars(mb_strtoupper(mb_substr($member['name'], 0, 1))) ?></div>
    <div>
      <h3 class="bold mb-1"><?= htmlspecialchars($member['name']) ?></h3>
      <p class="muted mb-0">
        <?= htmlspecialchars($isSelf ? 'Account holder' : $member['relationship']) ?>
        <?php if (!empty($member['date_of_birth'])): ?>
          &middot; born <?= date('j M Y', strtotime($member['date_of_birth'])) ?>
        <?php endif; ?>
      </p>
    </div>
  </div>
  <a href="<?= BASE_URL ?>/customer/profile" class="btn btn-ps-outline btn-sm">Back to profile</a>
</div>

<div class="ps-card p-4 mb-4">
  <h6 class="bold mb-3"><?= icon('id-card', 'me-2') ?>Details</h6>

  <form method="POST" action="<?= BASE_URL ?>/customer/profile/update-member/<?= (int) $member['id'] ?>" class="row g-3">
    <?= csrf_field() ?>

    <div class="col-md-6">
      <label class="field-label small" for="memberName">Full name</label>
      <input type="text" id="memberName" name="member_name" class="field" maxlength="80"
             value="<?= htmlspecialchars($member['name']) ?>" required>
    </div>

    <div class="col-md-3">
      <label class="field-label small" for="relationship">Relationship</label>
      <?php if ($isSelf): ?>
        <input type="text" id="relationship" class="field" value="Self (account holder)" disabled>
      <?php else: ?>
        <select id="relationship" name="relationship" class="field">
          <?php foreach (FamilyMember::RELATIONSHIPS as $r): ?>
            <?php if ($r === 'Self') continue; ?>
            <option value="<?= $r ?>" <?= $member['relationship'] === $r ? 'selected' : '' ?>><?= $r ?></option>
          <?php endforeach; ?>
        </select>
      <?php endif; ?>
    </div>

    <div class="col-md-3">
      <label class="field-label small" for="dob">Date of birth</label>
      <input type="date" id="dob" name="date_of_birth" class="field" max="<?= date('Y-m-d') ?>"
             value="<?= htmlspecialchars($member['date_of_birth']) ?>">
    </div>

    <div class="col-md-6">
      <label class="field-label small" for="notes">Notes for the pharmacist</label>
      <input type="text" id="notes" name="notes" class="field" maxlength="255"
             placeholder="e.g. takes Metformin twice daily"
             value="<?= htmlspecialchars($member['notes']) ?>">
    </div>

    <div class="col-md-6 flex bottom">
      <button type="submit" class="btn btn-ps-primary mt-2"><?= icon('save', 'me-2') ?>Save changes</button>
    </div>
  </form>
</div>

<div class="ps-card p-4 mb-4">
  <h6 class="bold mb-1"><?= icon('clipboard-plus', 'me-2') ?>Health Profile</h6>
  <p class="muted small mb-3">
    Recorded against <?= htmlspecialchars($isSelf ? 'you' : $member['name']) ?> only. The pharmacist checks every
    prescription uploaded for this person against this list.
  </p>

  <div class="row g-3">

    <div class="col-md-6">
      <div class="ps-card p-3 h-100">
        <div class="small bold muted mb-2"><?= icon('triangle-alert', 'text-danger me-1') ?>Known Allergies</div>

        <div class="flex wrap gap-2 mb-3">
          <?php foreach ($allergies as $a): ?>
            <span class="tag rounded-pill flex middle gap-1" style="background:#fde8e6; color: var(--ps-danger);">
              <?= htmlspecialchars($a['value']) ?>
              <form method="POST" action="<?= BASE_URL ?>/customer/profile/delete-flag" class="inline"
                    onsubmit="return confirm('Remove <?= htmlspecialchars($a['value'], ENT_QUOTES) ?>?');">
                <?= csrf_field() ?>
                <input type="hidden" name="member_id" value="<?= (int) $member['id'] ?>">
                <input type="hidden" name="flag_id" value="<?= (int) $a['id'] ?>">
                <button type="submit" class="btn-text small" aria-label="Remove <?= htmlspecialchars($a['value'], ENT_QUOTES) ?>">&times;</button>
              </form>
            </span>
          <?php endforeach; ?>
          <?php if (!$allergies): ?>
            <span class="muted small">None recorded.</span>
          <?php endif; ?>
        </div>

        <form method="POST" action="<?= BASE_URL ?>/customer/profile/member/<?= (int) $member['id'] ?>/add-flag" class="flex gap-2">
          <?= csrf_field() ?>
          <input type="hidden" name="type" value="allergy">
          <input type="text" name="value" class="field field-sm" maxlength="120" placeholder="e.g. Penicillin" required>
          <button type="submit" class="btn btn-ps-outline btn-sm">Add</button>
        </form>
      </div>
    </div>

    <div class="col-md-6">
      <div class="ps-card p-3 h-100">
        <div class="small bold muted mb-2"><?= icon('activity', 'me-1') ?>Chronic Conditions</div>

        <div class="flex wrap gap-2 mb-3">
          <?php foreach ($conditions as $c): ?>
            <span class="tag rounded-pill ps-badge-otc flex middle gap-1">
              <?= htmlspecialchars($c['value']) ?>
              <form method="POST" action="<?= BASE_URL ?>/customer/profile/delete-flag" class="inline"
                    onsubmit="return confirm('Remove <?= htmlspecialchars($c['value'], ENT_QUOTES) ?>?');">
                <?= csrf_field() ?>
                <input type="hidden" name="member_id" value="<?= (int) $member['id'] ?>">
                <input type="hidden" name="flag_id" value="<?= (int) $c['id'] ?>">
                <button type="submit" class="btn-text small" aria-label="Remove <?= htmlspecialchars($c['value'], ENT_QUOTES) ?>">&times;</button>
              </form>
            </span>
          <?php endforeach; ?>
          <?php if (!$conditions): ?>
            <span class="muted small">None recorded.</span>
          <?php endif; ?>
        </div>

        <form method="POST" action="<?= BASE_URL ?>/customer/profile/member/<?= (int) $member['id'] ?>/add-flag" class="flex gap-2">
          <?= csrf_field() ?>
          <input type="hidden" name="type" value="condition">
          <input type="text" name="value" class="field field-sm" maxlength="120" placeholder="e.g. Type 2 Diabetes" required>
          <button type="submit" class="btn btn-ps-outline btn-sm">Add</button>
        </form>
      </div>
    </div>

  </div>
</div>

<?php if (!$isSelf): ?>
  <div class="ps-card p-4" style="background:#fdf3f2; border-color:#f4c9c4;">
    <div class="flex between middle wrap gap-3">
      <div>
        <h6 class="bold text-danger mb-1">Remove this family profile</h6>
        <p class="muted small mb-0">
          <?= htmlspecialchars($member['name']) ?> and their recorded allergies and conditions are deleted.
          A profile with prescriptions already on file cannot be removed.
        </p>
      </div>
      <form method="POST" action="<?= BASE_URL ?>/customer/profile/delete-member/<?= (int) $member['id'] ?>"
            onsubmit="return confirm('Delete <?= htmlspecialchars($member['name'], ENT_QUOTES) ?> from your family profiles? This cannot be undone.');">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-danger btn-sm noshrink">Delete profile</button>
      </form>
    </div>
  </div>
<?php else: ?>
  <div class="note note-plain mb-0">
    This is your own profile, so it cannot be deleted. Change your name and phone number under Personal Information on the
    <a href="<?= BASE_URL ?>/customer/profile">profile page</a>.
  </div>
<?php endif; ?>
