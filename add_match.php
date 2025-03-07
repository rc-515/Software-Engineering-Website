<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_implement.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Access denied.");
}

$user_id = $_SESSION["user_id"];

// Get current user's attributes
$stmt = $conn->prepare("SELECT weight, height, bench_press, experience FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_weight, $user_height, $user_bench, $user_experience);
$stmt->fetch();
$stmt->close();

// Debugging - Print user's stats
echo "<p><strong>Your Stats:</strong> Weight: $user_weight, Height: $user_height, Bench: $user_bench, Experience: $user_experience</p>";

// Find up to 5 potential matches based on stats and exact experience match
$stmt = $conn->prepare("SELECT id, full_name, email, weight, height, bench_press, experience 
                        FROM users 
                        WHERE id != ? 
                        AND experience = ? 
                        AND ABS(weight - ?) <= 15 
                        AND ABS(height - ?) <= 4 
                        AND ABS(bench_press - ?) <= 25 
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
    echo "<p>No suitable matches found.</p>";
}

$stmt->close();
$conn->close();
?>
