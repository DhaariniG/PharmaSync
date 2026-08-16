<div class="flex between top mb-3 wrap gap-2">
  <div>
    <h3 class="bold mb-1">Notifications</h3>
    <p class="muted mb-0">Stay updated on your prescriptions, orders, and health journey.</p>
  </div>
  <div class="flex gap-2">
    <form method="POST" action="<?= BASE_URL ?>/notifications/mark-all-read">
          <?= csrf_field() ?>
      <button type="submit" class="btn btn-ps-outline btn-sm"><?= icon('check-check', 'me-2') ?>Mark all read</button>
    </form>
    <form method="POST" action="<?= BASE_URL ?>/notifications/mark-all-unread">
          <?= csrf_field() ?>
      <button type="submit" class="btn btn-ps-outline btn-sm"><?= icon('mail', 'me-2') ?>Mark all unread</button>
    </form>
    <form method="POST" action="<?= BASE_URL ?>/notifications/clear">
          <?= csrf_field() ?>
      <button type="submit" class="btn btn-ps-outline btn-sm"><?= icon('trash-2', 'me-2') ?>Clear all</button>
    </form>
  </div>
</div>

<div class="flex wrap gap-2 mb-4">
  <?php
    $types = ['all' => 'All Alerts', 'orders' => 'Orders', 'prescriptions' => 'Prescriptions', 'health-tips' => 'Health Tips', 'promos' => 'Promos'];
    foreach ($types as $key => $label): ?>
      <a href="<?= BASE_URL ?>/notifications?type=<?= $key ?>" class="btn btn-sm <?= $activeType === $key ? 'btn-ps-primary' : 'btn-ps-outline' ?> rounded-pill"><?= $label ?></a>
  <?php endforeach; ?>
</div>

<?php if (empty($notifications)): ?>
  <div class="ps-empty-state ps-card">
    <?= icon('bell') ?>
    <h5 class="mt-3">You're all caught up</h5>
    <p class="muted">No notifications here.</p>
  </div>
<?php else: ?>
  <?php foreach ($notifications as $n): ?>
    <div class="ps-card p-3 mb-3 <?= $n['color'] === 'banner' ? 'ps-banner' : '' ?> <?= !$n['read'] && $n['color'] !== 'banner' ? 'border-start border-4 border-primary' : '' ?>">
      <div class="flex gap-3">
        <?php if ($n['color'] !== 'banner'): ?>
          <div class="ps-stat-icon noshrink <?= $n['color'] === 'danger' ? 'text-danger' : '' ?>" style="<?= $n['color'] === 'danger' ? 'background:#fde8e6;color:var(--ps-danger);' : '' ?>">
            <?= icon($n['icon']) ?>
          </div>
        <?php endif; ?>
        <div class="grow">
          <div class="flex between top gap-2">
            <div class="bold <?= $n['color'] === 'banner' ? 'text-white' : ($n['color'] === 'danger' ? 'text-danger' : '') ?>">
              <?= htmlspecialchars($n['title']) ?>
              <?php if (!$n['read']): ?>
                <span class="tag ps-badge-rx ms-1" style="font-size:.6rem;vertical-align:middle;">NEW</span>
              <?php endif; ?>
            </div>
            <div class="flex middle gap-2 noshrink">
              <span class="small <?= $n['color'] === 'banner' ? 'text-white-50' : 'muted' ?>"><?= htmlspecialchars($n['time']) ?></span>
              <!-- Lets the customer restore ("re-enable") a notification they
                   already marked as seen, or dismiss one they've dealt with. -->
              <form method="POST" action="<?= BASE_URL ?>/notifications/toggle-read" class="inline">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int) $n['id'] ?>">
                <button type="submit" class="btn btn-sm btn-text p-0 <?= $n['color'] === 'banner' ? 'text-white' : 'muted' ?>"
                        title="<?= $n['read'] ? 'Mark as unread' : 'Mark as read' ?>">
                  <?= icon($n['read'] ? 'mail' : 'mail-open') ?>
                </button>
              </form>
            </div>
          </div>
          <p class="small mb-2 <?= $n['color'] === 'banner' ? 'text-white-50' : 'muted' ?>"><?= htmlspecialchars($n['body']) ?></p>
          <?php if (!empty($n['actions'])): ?>
            <div class="flex gap-2">
              <?php foreach ($n['actions'] as $a): ?>
                <a href="<?= BASE_URL . $a['href'] ?>" class="btn btn-sm <?= $a['style'] === 'primary' ? 'btn-ps-primary' : ($a['style'] === 'danger' ? 'btn-danger' : ($a['style'] === 'light' ? 'btn-plain' : 'btn-ps-outline')) ?>"><?= htmlspecialchars($a['label']) ?></a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
