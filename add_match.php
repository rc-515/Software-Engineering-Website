<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION["username"];
    $opponent = trim($_POST["opponent"]);
    $fight_date = trim($_POST["fight_date"]);

    // Validate inputs
    if (empty($opponent) || empty($fight_date)) {
        die("Please fill in all fields.");
    }

    if (!strtotime($fight_date)) {
        die("Invalid date format.");
    }

    $stmt = $conn->prepare("INSERT INTO matches (username, opponent, fight_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $opponent, $fight_date);

    if ($stmt->execute()) {
        echo "Match scheduled successfully!";
    } else {
        error_log("Database error: " . $stmt->error); // Log the error
        echo "An error occurred. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>