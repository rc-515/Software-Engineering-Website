<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_implement.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Access denied.");
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT matches.id, users.full_name, matches.opponent, matches.fight_date 
        FROM matches 
        JOIN users ON matches.user_id = users.id 
        WHERE users.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h3>Scheduled Fights</h3>";
    while ($row = $result->fetch_assoc()) {
        echo "Match ID: " . $row["id"] . " - " . $row["full_name"] . " vs " . $row["opponent"] . " on " . $row["fight_date"];
        echo " <button onclick='deleteMatch(" . $row["id"] . ")'>Delete</button><br>";
    }
} else {
    echo "<p>No scheduled fights found.</p>";
}

$stmt->close();
$conn->close();
?>
