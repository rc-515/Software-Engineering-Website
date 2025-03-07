<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_implement.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Access denied.");
}

$user_id = $_SESSION["user_id"];

// Get the logged-in user's name
$stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_name);
$stmt->fetch();
$stmt->close();

// Get all matches involving this user
$stmt = $conn->prepare("SELECT match_id, challenger_name, opponent_name, fight_date FROM matches WHERE challenger_name = ? OR opponent_name = ?");
$stmt->bind_param("ss", $user_name, $user_name);
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>Your Matches</h3>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Determine the opponent
        $opponent = ($row["challenger_name"] == $user_name) ? $row["opponent_name"] : $row["challenger_name"];

        echo "<p>Match ID: " . $row["match_id"] . "<br>";
        echo "Opponent: " . $opponent . "<br>";
        echo "Fight Date: " . $row["fight_date"] . "</p><hr>";
    }
} else {
    echo "<p>No scheduled matches.</p>";
}

$stmt->close();
$conn->close();
?>
