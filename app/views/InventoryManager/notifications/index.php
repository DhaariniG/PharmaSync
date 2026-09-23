<main class="main-content">
    <div class="content-area">
        <div>
            <h2 class="page-title">Notifications</h2>
            <p class="page-subtitle">Low stock, expiry and purchase order alerts.</p>
        </div>

        <div class="card notification-list">
            <?php foreach ($notifications as $n): ?>
                <a class="notification-item notification-<?= e($n['type']) ?>" href="<?= url($n['link']) ?>">
                    <span class="notification-icon">
                        <?= icon($n['icon'], 'material-symbols-outlined') ?>
                    </span>
                    <span class="notification-body">
                        <span class="notification-title"><?= e($n['title']) ?></span>
                        <span class="notification-time"><?= e($n['time']) ?></span>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</main>
