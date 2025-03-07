l<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);


include 'db_implement.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($password_hash);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $password_hash)) {
        $_SESSION["email"] = $email;
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>
