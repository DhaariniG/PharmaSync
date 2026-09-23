<?php
// One-time messages set by a controller with $this->flash('success', '...')
// before a redirect. Reading them here also clears them.
foreach (Session::takeFlash() as $type => $message):
    $isError = ($type === 'error');
?>
<div style="margin: 0 0 16px; padding: 12px 16px; border-radius: 6px; font-size: 14px;
            background: <?= $isError ? '#fef2f2' : '#f0fdf4' ?>; color: <?= $isError ? '#dc2626' : '#16a34a' ?>;">
    <?= e($message) ?>
</div>
<?php endforeach; ?>
