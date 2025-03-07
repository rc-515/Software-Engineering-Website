<?php
include 'db_implement.php';
session_start();
if (!isset($_SESSION["user_id"])) {
    die("Access denied.");
}

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["opponent_id"]) && isset($_POST["fight_date"])) {
    $opponent_id = intval($_POST["opponent_id"]);
    $fight_date = $_POST["fight_date"];

    $stmt = $conn->prepare("INSERT INTO matches (user_id, opponent_id, fight_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $opponent_id, $fight_date);

    if ($stmt->execute()) {
        echo "Match scheduled successfully!";
    } else {
        echo "Error scheduling match: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit();
}

// Get the current user's details
$stmt = $conn->prepare("SELECT weight, height, bench_press, experience FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_weight, $user_height, $user_bench, $user_experience);
$stmt->fetch();
$stmt->close();

// Find up to 5 potential matches
$stmt = $conn->prepare("SELECT id, full_name, email, weight, height, bench_press, experience 
                        FROM users 
                        WHERE id != ? AND experience = ? 
                        AND ABS(weight - ?) <= 10 
                        AND ABS(height - ?) <= 3 
                        AND ABS(bench_press - ?) <= 20 
                        LIMIT 5");
$stmt->bind_param("isiii", $user_id, $user_experience, $user_weight, $user_height, $user_bench);
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>Potential Matches</h3>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Opponent: " . $row["full_name"] . " (" . $row["email"] . ")<br>";
        echo "Weight: " . $row["weight"] . " lbs, Height: " . $row["height"] . " inches, Bench Press: " . $row["bench_press"] . " lbs, Experience: " . $row["experience"] . "<br>";
        echo "<button onclick='scheduleMatch(" . $row["id"] . ")'>Schedule</button><br><br>";
    }
} else {
    echo "<p>Sorry, no suitable matches were found at this time.</p>";
}

$stmt->close();
$conn->close();
?>
