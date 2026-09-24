<?php
// One-time messages ("Account deleted.", "Cannot delete...") set by a
// controller with $this->flash('success', '...') before a redirect.
// Reading them here also clears them, so they show exactly once.
foreach (Session::takeFlash() as $type => $message):
    $cssClass = $type === 'error' ? 'flash-error' : 'flash-success';
?>
<div class="flash <?= $cssClass ?>"><?= e($message) ?></div>
<?php endforeach; ?>
