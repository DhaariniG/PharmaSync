<?php
/**
 * Authentication routes.  Owner: <whoever takes login>.
 *
 * Paths are written WITHOUT the /authentication segment, so 'GET /login'
 * below is reachable at /authentication/login.
 *
 * AuthenticationController is a stub right now: it signs a demo user in so
 * the other four modules can be opened. Replace its body with a real users
 * table lookup - the rest of the project only depends on Session::login()
 * being handed an array with id, role and name.
 */
return [

    'GET  /login'    => ['AuthenticationController', 'loginForm'],
    'POST /login'    => ['AuthenticationController', 'login'],
    'GET  /register' => ['AuthenticationController', 'registerForm'],
    'POST /register' => ['AuthenticationController', 'register'],
    'GET  /logout'   => ['AuthenticationController', 'logout'],

];
