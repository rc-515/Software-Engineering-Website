<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

$sql = "SELECT * FROM matches WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION["username"]);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Scheduled Fights</h2>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Match ID: " . $row["id"] . " - User: " . $row["username"] . " vs " . $row["opponent"] . " on " . $row["fight_date"] . "<br>";
    }
} else {
    echo "No matches found.";
}

$stmt->close();
$conn->close();
?>