<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_implement.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if username is empty
    if (empty($username) || empty($password)) {
        die("Error: Username and password fields cannot be empty.");
    }

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ss", $username, $hashed_password);
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No POST request received.";
}
?>
