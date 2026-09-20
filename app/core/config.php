<?php
/**
 * PharmaSync - Global configuration
 * Group 46
 */

/* ---------- Database ---------- */
define('DB_HOST', 'localhost');
define('DB_NAME', 'pharmasync');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/* ---------- Paths ---------- */
define('ROOT_PATH',   dirname(dirname(__DIR__))); 
define('APP_PATH',    ROOT_PATH . '/app');          
define('PUBLIC_PATH', ROOT_PATH . '/public');       
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
/* ---------- Base URL (auto-detected) ---------- */
$protocol  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host      = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$scriptDir = ($scriptDir === '/' || $scriptDir === '.') ? '' : $scriptDir;

define('BASE_URL', $protocol . '://' . $host . rtrim($scriptDir, '/'));

/* ---------- Roles ---------- */
define('ROLE_SLUGS', [
    'Customer'          => 'customer',
    'Pharmacist'        => 'pharmacist',
    'Admin'             => 'admin',
    'Inventory_Manager' => 'InventoryManager',
    'Delivery_Partner'  => 'deliverypartner',
]);

/** Reverse map: slug -> database ENUM value */
define('SLUG_ROLES', array_flip(ROLE_SLUGS));

/* ---------- Uploads ---------- */
define('MAX_UPLOAD_BYTES', 5 * 1024 * 1024);   // 5 MB
define('ALLOWED_IMAGE_MIME', ['image/jpeg', 'image/png', 'image/webp']);

/* ---------- Environment ---------- */
define('APP_ENV', 'dev');

if (APP_ENV === 'dev') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

date_default_timezone_set('Asia/Colombo');