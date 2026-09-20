<?php

// 1. Load configurations & core framework
require_once __DIR__ . '/../app/core/config.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/Session.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/core/App.php';

// 2. Start global session using secure cookie defaults
Session::start();

// 3. Launch the MVC router
$app = new App();