<?php 

$localConfig = __DIR__ . '/config.local.php';
if (is_file($localConfig)) {
    require_once $localConfig;
}

// Biến môi trường, dùng chung toàn hệ thống
// Khai báo dưới dạng HẰNG SỐ để không phải dùng $GLOBALS

$forwardedProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';
$isHttps = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
    || strtolower((string) $forwardedProto) === 'https';
$scheme = $isHttps ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/.');
$basePath = $basePath === '' ? '' : '/' . ltrim($basePath, '/');

define('BASE_URL', $scheme . '://' . $host . $basePath . '/');

define('DB_HOST'    , 'localhost');
define('DB_PORT'    , 3306);
define('DB_NAME'    , 'da1n1');  // Tên database
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

define('PATH_ROOT'    , __DIR__ . '/../');
