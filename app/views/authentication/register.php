<?php $__title = 'Sign up'; require __DIR__ . '/partials/auth-head.php'; ?>

        <h4 class="fw-bold mb-1">Create your account</h4>
        <p class="text-muted mb-4">Sign up to start ordering from PharmaSync.</p>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/register" data-validate novalidate>
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label">Full name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Phone number</label>
            <input type="tel" name="phone" class="form-control" placeholder="+94 7X XXX XXXX">
          </div>
          <div class="mb-3">
            <label class="form-label">Delivery address</label>
            <input type="text" name="address" class="form-control">
          </div>
          <div class="row">
            <div class="col-6 mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" minlength="6" required>
            </div>
            <div class="col-6 mb-3">
              <label class="form-label">Confirm password</label>
              <input type="password" name="confirm_password" class="form-control" minlength="6" required>
            </div>
          </div>
          <button type="submit" class="btn btn-ps-primary w-100 py-2 fw-semibold">Create account</button>
        </form>

        <p class="text-center mt-4 mb-0">
          Already have an account? <a href="<?= BASE_URL ?>/login" class="fw-semibold">Log in</a>
        </p>

<?php require __DIR__ . '/partials/auth-foot.php'; ?>
