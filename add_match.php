<?php
include 'db_implement.php';
session_start();
if (!isset($_SESSION["user_id"])) {
    die("Access denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $opponent = $_POST["opponent"];
    $fight_date = $_POST["fight_date"];

    $stmt = $conn->prepare("INSERT INTO matches (user_id, opponent, fight_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $opponent, $fight_date);

    if ($stmt->execute()) {
        echo "Match scheduled successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
