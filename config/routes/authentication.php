<?php
/**
 * Authentication routes.
 *
 * Paths are written WITHOUT the /authentication segment, so 'GET /login'
 * below is reachable at /authentication/login.
 *
 * Logout is POST only (with CSRF), so another site cannot sign people out
 * with a hidden link. To add a sign-out button to your own module:
 *
 *   <form method="post" action="<?= url('/' . AUTH_SLUG . '/logout') ?>">
 *       <?= csrf_field() ?>
 *       <button type="submit">Sign out</button>
 *   </form>
 */
return [

    'GET  /login'           => ['AuthenticationController', 'loginForm'],
    'POST /login'           => ['AuthenticationController', 'login'],
    'GET  /register'        => ['AuthenticationController', 'registerForm'],
    'POST /register'        => ['AuthenticationController', 'register'],
    'GET  /forgot-password' => ['AuthenticationController', 'forgotForm'],
    'POST /forgot-password' => ['AuthenticationController', 'forgot'],
    'GET  /reset-password'  => ['AuthenticationController', 'resetForm'],
    'POST /reset-password'  => ['AuthenticationController', 'resetPassword'],
    'POST /logout'          => ['AuthenticationController', 'logout'],

];
