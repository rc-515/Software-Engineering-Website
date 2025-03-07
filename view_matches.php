<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_implement.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Access denied.");
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT m.id, u.full_name, u.email, u.weight, u.height, u.bench_press, u.experience, m.fight_date 
        FROM matches m
        JOIN users u ON m.opponent_id = u.id
        WHERE m.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h3>Scheduled Fights</h3>";
    while ($row = $result->fetch_assoc()) {
        echo "Match ID: " . $row["id"] . " - Opponent: " . $row["full_name"] . " (" . $row["email"] . ")<br>";
        echo "Weight: " . $row["weight"] . " lbs, Height: " . $row["height"] . " inches, Bench Press: " . $row["bench_press"] . " lbs, Experience: " . $row["experience"] . "<br>";
        echo "Fight Date: " . $row["fight_date"] . "<br>";
        echo "<button onclick='deleteMatch(" . $row["id"] . ")'>Delete</button><br><br>";
    }
} else {
    echo "<p>No scheduled fights found.</p>";
}

$stmt->close();
$conn->close();
?>
