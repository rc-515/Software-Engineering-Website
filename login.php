<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);


include 'db_implement.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($password_hash);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $password_hash)) {
        $_SESSION["username"] = $username;
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>
