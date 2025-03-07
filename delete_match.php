<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_implement.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Access denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["match_id"])) {
    $match_id = intval($_POST["match_id"]);

    // Verify user is part of the match
    $stmt = $conn->prepare("SELECT challenger_name, opponent_name FROM matches WHERE match_id = ?");
    $stmt->bind_param("i", $match_id);
    $stmt->execute();
    $stmt->bind_result($challenger_name, $opponent_name);
    $stmt->fetch();
    $stmt->close();

    if ($challenger_name == $_SESSION["full_name"] || $opponent_name == $_SESSION["full_name"]) {
        // Delete the match
        $stmt = $conn->prepare("DELETE FROM matches WHERE match_id = ?");
        $stmt->bind_param("i", $match_id);

        if ($stmt->execute()) {
            echo "<script>alert('Match deleted successfully!'); window.location.href = 'dashboard.html';</script>";
        } else {
            echo "<script>alert('Error deleting match.'); window.location.href = 'dashboard.html';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('You are not authorized to delete this match.'); window.location.href = 'dashboard.html';</script>";
    }
}

$conn->close();
?>
