<?php
include 'db_implement.php';
session_start();

$sql = "SELECT matches.id, users.full_name, matches.opponent, matches.fight_date FROM matches JOIN users ON matches.user_id = users.id";
$result = $conn->query($sql);

echo "<h2>Scheduled Fights</h2>";
while ($row = $result->fetch_assoc()) {
    echo "Match ID: " . $row["id"] . " - " . $row["full_name"] . " vs " . $row["opponent"] . " on " . $row["fight_date"] . "<br>";
}
$conn->close();
?>
