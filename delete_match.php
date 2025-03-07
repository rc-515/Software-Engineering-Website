<?php
include 'db_implement.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Access denied.");
}

if (isset($_GET["id"])) {
    $match_id = intval($_GET["id"]);

    $stmt = $conn->prepare("DELETE FROM matches WHERE id = ?");
    $stmt->bind_param("i", $match_id);

    if ($stmt->execute()) {
        echo "Match deleted successfully!";
    } else {
        echo "Error deleting match: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
