<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__) . '/config.php';
$schema = [
    "CREATE TABLE IF NOT EXISTS users (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)",
    "CREATE TABLE IF NOT EXISTS sensor_data (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, sensor_type VARCHAR(50) NOT NULL, reading_value DECIMAL(10,2) NOT NULL, location VARCHAR(100), recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)",
    "CREATE TABLE IF NOT EXISTS alerts (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, alert_type VARCHAR(50) NOT NULL, message TEXT NOT NULL, status VARCHAR(20) DEFAULT 'unread', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)",
    "CREATE TABLE IF NOT EXISTS settings (id INT NOT NULL PRIMARY KEY, soap_threshold INT NOT NULL DEFAULT 20, waste_threshold INT NOT NULL DEFAULT 80)"
];
foreach ($schema as $sql) { $conn->query($sql); }
// Only initialize a new settings table; preserve any existing configuration.
$columns = array_column($conn->query('SHOW COLUMNS FROM settings')->fetch_all(MYSQLI_ASSOC), 'Field');
if (in_array('soap_threshold', $columns, true)) {
    $conn->query('INSERT IGNORE INTO settings (id, soap_threshold, waste_threshold) VALUES (1,20,80)');
}
echo "Database initialization complete.\n";
