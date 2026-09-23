<?php
/**
 * Personal mail settings - copy this file to config/local.php and fill in
 * your own Mailtrap sandbox SMTP credentials. config/local.php is
 * git-ignored, so your credentials never get committed.
 */

define('MAIL_ENABLED',   true);
define('MAIL_HOST',      'sandbox.smtp.mailtrap.io');
define('MAIL_PORT',      587);
define('MAIL_USERNAME',  '');
define('MAIL_PASSWORD',  '');
define('MAIL_FROM',      'no-reply@pharmasync.test');
define('MAIL_FROM_NAME', 'PharmaSync');
