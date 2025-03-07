<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_implement.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Access denied.");
}

$user_id = $_SESSION["user_id"];

// Retrieve matches where the logged-in user is either the creator (`id`) or the opponent (`opponent_id`)
$sql = "SELECT m.id AS match_id, 
               u1.full_name AS user_name, u1.email AS user_email, u1.weight AS user_weight, u1.height AS user_height, u1.bench_press AS user_bench, u1.experience AS user_experience, 
               u2.full_name AS opponent_name, u2.email AS opponent_email, u2.weight AS opponent_weight, u2.height AS opponent_height, u2.bench_press AS opponent_bench, u2.experience AS opponent_experience, 
               m.fight_date 
        FROM matches m
        JOIN users u1 ON m.id = u1.id
        JOIN users u2 ON m.opponent_id = u2.id
        WHERE m.id = ? OR m.opponent_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h3>Scheduled Fights</h3>";
    while ($row = $result->fetch_assoc()) {
        $opponent = ($row["user_email"] === $_SESSION["email"]) ? $row["opponent_name"] : $row["user_name"];
        $opponent_email = ($row["user_email"] === $_SESSION["email"]) ? $row["opponent_email"] : $row["user_email"];
        $opponent_weight = ($row["user_email"] === $_SESSION["email"]) ? $row["opponent_weight"] : $row["user_weight"];
        $opponent_height = ($row["user_email"] === $_SESSION["email"]) ? $row["opponent_height"] : $row["user_height"];
        $opponent_bench = ($row["user_email"] === $_SESSION["email"]) ? $row["opponent_bench"] : $row["user_bench"];
        $opponent_experience = ($row["user_email"] === $_SESSION["email"]) ? $row["opponent_experience"] : $row["user_experience"];

        echo "Match ID: " . $row["match_id"] . " - Opponent: " . $opponent . " (" . $opponent_email . ")<br>";
        echo "Weight: " . $opponent_weight . " lbs, Height: " . $opponent_height . " inches, Bench Press: " . $opponent_bench . " lbs, Experience: " . $opponent_experience . "<br>";
        echo "Fight Date: " . $row["fight_date"] . "<br>";
        echo "<button onclick='deleteMatch(" . $row["match_id"] . ")'>Delete</button><br><br>";
    }
} else {
    echo "<p>No scheduled fights found.</p>";
}

$stmt->close();
$conn->close();
?>
