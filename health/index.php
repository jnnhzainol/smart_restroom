<?php
require dirname(__DIR__) . '/config.php';
$conn->query('SELECT id FROM sensor_data LIMIT 1');
header('Content-Type: application/json');
echo '{"status":"ok"}';
