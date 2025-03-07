<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $match_id = intval($_POST["match_id"]); // Ensure match_id is an integer

    $stmt = $conn->prepare("DELETE FROM matches WHERE id=? AND username=?");
    $stmt->bind_param("is", $match_id, $_SESSION["username"]);

    if ($stmt->execute()) {
        echo "Match deleted successfully!";
    } else {
        error_log("Database error: " . $stmt->error); // Log the error
        echo "An error occurred. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>