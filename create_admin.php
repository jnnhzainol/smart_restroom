<?php
include 'config.php';

$name = "Admin";
$email = "admin@restroom.com";
$password = "admin123"; 
$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashed);

if ($stmt->execute()) {
    echo "Admin successfully created! Email: $email | Password: $password";
} else {
    echo "Error: " . $conn->error;
}
?>