<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_implement.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format.");
    }

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT username, full_name, password_hash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        die("Error: Email not found.");
    }

    // Fetch user details
    $stmt->bind_result($username, $full_name, $hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Verify password
    if (password_verify($password, $hashed_password)) {
        // Store user details in session
        $_SESSION["username"] = $username;
        $_SESSION["full_name"] = $full_name;
        $_SESSION["user_email"] = $email; // Store email for easy reference

        echo "<script>alert('Login successful!'); window.location.href = 'dashboard.php';</script>";
    } else {
        die("Error: Incorrect password.");
    }

    $conn->close();
}
?>
