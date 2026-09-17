<?php
include '../config.php';

if (getenv('APP_ENV') === 'production') {
    $expectedKey = getenv('SENSOR_API_KEY');
    $providedKey = $_SERVER['HTTP_X_SENSOR_KEY'] ?? '';
    if (!$expectedKey || !hash_equals($expectedKey, $providedKey)) {
        http_response_code(401);
        exit('Unauthorized sensor');
    }
}

// 1. Ambil nilai peratusan waste daripada ESP32
$waste = filter_var($_POST['waste'] ?? $_GET['waste'] ?? null, FILTER_VALIDATE_INT);

if ($waste === false || $waste === null || $waste < 0 || $waste > 100) {
    http_response_code(400);
    echo "Missing data";
    exit;
}

// 2. Semak threshold dari settings
$waste_threshold = 80;
$s_result = $conn->query("SELECT * FROM settings WHERE id=1");
if ($s_result && $s_result->num_rows > 0) {
    $s = $s_result->fetch_assoc();
    if (isset($s['waste_threshold'])) {
        $waste_threshold = $s['waste_threshold'];
    }
}

// 3. Tetapkan maklumat sensor
$sensor_type = 'waste';
$reading_value = $waste;
$location = 'Restroom 1';

// 4. Masukkan data ke jadual sensor_data
$stmt = $conn->prepare("INSERT INTO sensor_data (sensor_type, reading_value, location) VALUES (?, ?, ?)");
$stmt->bind_param("sds", $sensor_type, $reading_value, $location);

if ($stmt->execute()) {
    echo "OK";
} else {
    http_response_code(500);
    echo "Unable to save sensor reading";
}
?>
