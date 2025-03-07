<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_implement.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Access denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["match_id"]) && isset($_POST["new_date"])) {
    $match_id = intval($_POST["match_id"]);
    $new_date = $_POST["new_date"];

    // Verify user is part of the match
    $stmt = $conn->prepare("SELECT challenger_name, opponent_name FROM matches WHERE match_id = ?");
    $stmt->bind_param("i", $match_id);
    $stmt->execute();
    $stmt->bind_result($challenger_name, $opponent_name);
    $stmt->fetch();
    $stmt->close();

    if ($challenger_name == $_SESSION["full_name"] || $opponent_name == $_SESSION["full_name"]) {
        // Update the fight date
        $stmt = $conn->prepare("UPDATE matches SET fight_date = ? WHERE match_id = ?");
        $stmt->bind_param("si", $new_date, $match_id);

        if ($stmt->execute()) {
            echo "<script>alert('Match date updated successfully!'); window.location.href = 'dashboard.php';</script>";
        } else {
            echo "<script>alert('Error updating match date.'); window.location.href = 'dashboard.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('You are not authorized to edit this match.'); window.location.href = 'dashboard.php';</script>";
    }
}

$conn->close();
?>
