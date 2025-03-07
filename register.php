<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_implement.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form inputs
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $weight = intval($_POST["weight"]);
    $height = intval($_POST["height"]);
    $bench_press = intval($_POST["bench_press"]);
    $experience = $_POST["experience"];

    // Check if fields are empty
    if (empty($full_name) || empty($email) || empty($password) || empty($weight) || empty($height) || empty($bench_press) || empty($experience)) {
        die("Error: All fields must be filled out.");
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format.");
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        die("Error: Email already exists.");
    }
    $stmt->close();

    //check password length
    if (strlen($password) < 10) {
        die("Error: Password must be at least 10 characters long.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password_hash, weight, height, bench_press, experience) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiiis", $full_name, $email, $hashed_password, $weight, $height, $bench_press, $experience);

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
