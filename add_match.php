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

// Generate a random date within the next 30 to 90 days
$random_days = rand(30, 90);
$fight_date = date('Y-m-d', strtotime("+$random_days days"));

// Debugging - Print user's stats
echo "<p><strong>DEBUG: Your Stats:</strong> Weight: $user_weight, Height: $user_height, Bench: $user_bench, Experience: $user_experience</p>";

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
        echo "<p>Opponent: " . $row["full_name"] . " (" . $row["email"] . ")<br>";
        echo "Weight: " . $row["weight"] . " lbs, Height: " . $row["height"] . " inches, Bench Press: " . $row["bench_press"] . " lbs, Experience: " . $row["experience"] . "</p>";
        echo "<form method='POST' action=''>
                <input type='hidden' name='opponent_id' value='" . $row["id"] . "'>
                <input type='hidden' name='fight_date' value='" . $fight_date . "'>
                <button type='submit' name='schedule_match' onclick="alert('Schedule button clicked');">Schedule</button>
              </form><br><br>";
    }
} else {
    echo "<p>No suitable matches found.</p>";
}

// Handle scheduling a match
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["schedule_match"])) {
    echo "<p>DEBUG: Form submitted.</p>";
    
    if (!isset($_POST["opponent_id"]) || !isset($_POST["fight_date"])) {
        echo "<p>DEBUG: Missing form data.</p>";
    } else {
        $opponent_id = intval($_POST["opponent_id"]);
        $fight_date = $_POST["fight_date"];
        
        echo "<p>DEBUG: Opponent ID = $opponent_id, Fight Date = $fight_date</p>";

        $stmt = $conn->prepare("INSERT INTO matches (user_id, opponent_id, fight_date) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $opponent_id, $fight_date);
        
        if ($stmt->execute()) {
            echo "<script>alert('Match scheduled successfully!'); window.location.href = window.location.href;</script>";
        } else {
            echo "<p>DEBUG: Error scheduling match: " . $stmt->error . "</p>";
        }
        
        $stmt->close();
    }
}

$stmt->close();
$conn->close();
?>
