<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $match_id = intval($_POST["match_id"]); // Ensure match_id is an integer
    $opponent = trim($_POST["opponent"]);
    $fight_date = trim($_POST["fight_date"]);

    // Validate inputs
    if (empty($opponent) || empty($fight_date)) {
        die("Please fill in all fields.");
    }

    if (!strtotime($fight_date)) {
        die("Invalid date format.");
    }

    $stmt = $conn->prepare("UPDATE matches SET opponent=?, fight_date=? WHERE id=? AND username=?");
    $stmt->bind_param("ssis", $opponent, $fight_date, $match_id, $_SESSION["username"]);

    if ($stmt->execute()) {
        echo "Match updated successfully!";
    } else {
        error_log("Database error: " . $stmt->error); // Log the error
        echo "An error occurred. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>