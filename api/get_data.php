<?php
header('Content-Type: application/json');
include '../config.php';

// 1. Ambil nilai sampah (waste) terkini dari sensor_data
$waste_query = $conn->query("SELECT reading_value FROM sensor_data WHERE sensor_type = 'waste' ORDER BY recorded_at DESC, id DESC LIMIT 1")->fetch_assoc();
$waste_level = $waste_query ? floatval($waste_query['reading_value']) : 0;

// 2. Ambil sejarah data dan amaran untuk graf & jadual
$limit = max(1, min(100, (int) ($_GET['limit'] ?? 10)));
$history = $conn->query("SELECT * FROM sensor_data ORDER BY recorded_at DESC, id DESC LIMIT $limit");
$alerts = $conn->query("SELECT * FROM alerts ORDER BY id DESC LIMIT 5");

$historyArr = [];
if ($history) {
    while ($row = $history->fetch_assoc()) {
        $row['created_at'] = $row['recorded_at'];
        $row['soap_level'] = 100;
        $row['waste_level'] = $row['sensor_type'] === 'waste' ? (float) $row['reading_value'] : 0;
        $row['status'] = $row['waste_level'] > 80 ? 'Attention Needed' : 'Normal';
        $historyArr[] = $row;
    }
}

$alertArr = [];
if ($alerts) {
    while ($row = $alerts->fetch_assoc()) {
        $row['type'] = $row['alert_type'] ?? $row['type'] ?? '';
        $alertArr[] = $row;
    }
}

// 3. Pulangkan JSON dengan sokongan semua nama kunci (key) JavaScript
echo json_encode([
    "soap_level"    => 100,
    "waste_level"   => $waste_level,
    "status"        => ($waste_level > 80) ? 'Attention Needed' : 'Normal',
    "latest"        => [
        "soap_level"    => 100,
        "waste_level"   => $waste_level,
        "reading_value" => $waste_level,
        "status" => $waste_query ? (($waste_level > 80) ? 'Attention Needed' : 'Normal') : 'Waiting for sensor data'
    ],
    "history"       => $historyArr,
    "alerts"        => $alertArr
]);
?>
