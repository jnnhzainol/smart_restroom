<?php
include '../config.php';

$soap = intval($_GET['soap'] ?? -1);
$waste = intval($_GET['waste'] ?? -1);

if ($soap < 0 || $waste < 0) {
    echo "Missing data";
    exit;
}

$s = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();
$status = "Normal";
if ($soap < $s['soap_threshold'] || $waste > $s['waste_threshold']) {
    $status = "Attention Needed";
}

$stmt = $conn->prepare("INSERT INTO sensor_data (soap_level, waste_level, status) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $soap, $waste, $status);
$stmt->execute();

if ($soap < $s['soap_threshold']) {
    $conn->query("INSERT INTO alerts (type, message) VALUES ('Soap Low', 'Soap level is below {$s['soap_threshold']}%')");
}
if ($waste > $s['waste_threshold']) {
    $conn->query("INSERT INTO alerts (type, message) VALUES ('Waste High', 'Waste bin is above {$s['waste_threshold']}%')");
}

echo "OK";
?>