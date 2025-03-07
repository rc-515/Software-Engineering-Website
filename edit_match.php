<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["username"])) {
    die("Access denied.");
}

include 'db_implement.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["match_id"]) && isset($_POST["new_date"])) {
    $match_id = intval($_POST["match_id"]);
    $new_date = $_POST["new_date"];

    $stmt = $conn->prepare("UPDATE matches SET fight_date = ? WHERE match_id = ?");
    $stmt->bind_param("si", $new_date, $match_id);

    if ($stmt->execute()) {
        echo "<script>alert('Match date updated successfully!'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating match.'); window.location.href = 'dashboard.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
