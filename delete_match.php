<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["username"])) {
    die("Access denied.");
}

include 'db_implement.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["match_id"])) {
    $match_id = intval($_POST["match_id"]);

    $stmt = $conn->prepare("DELETE FROM matches WHERE match_id = ?");
    $stmt->bind_param("i", $match_id);

    if ($stmt->execute()) {
        echo "<script>alert('Match deleted successfully!'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Error deleting match.'); window.location.href = 'dashboard.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
