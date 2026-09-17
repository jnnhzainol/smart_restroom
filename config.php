<?php
if (PHP_SAPI !== 'cli') {
    session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax', 'use_strict_mode' => true,
        'cookie_secure' => getenv('APP_ENV') === 'production']);
}

$host = getenv('MYSQLHOST') ?: 'localhost';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$dbname = getenv('MYSQLDATABASE') ?: 'smart_restroom_';
$port = (int) (getenv('MYSQLPORT') ?: 3306);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($host, $user, $pass, $dbname, $port);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $error) {
    error_log('Database connection failed: ' . $error->getMessage());
    http_response_code(503);
    exit('Database temporarily unavailable.');
}
?>
