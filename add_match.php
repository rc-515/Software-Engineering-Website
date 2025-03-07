<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_implement.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Access denied.");
}

$user_id = $_SESSION["user_id"];

// Get the logged-in user's full name
$stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_name);
$stmt->fetch();
$stmt->close();

// Fetch all users except the logged-in user
$stmt = $conn->prepare("SELECT id, full_name, weight, height, bench_press, experience FROM users WHERE id != ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>Available Opponents</h3>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>Opponent:</strong> " . $row["full_name"] . "<br>";
        echo "<strong>Weight:</strong> " . $row["weight"] . " lbs<br>";
        echo "<strong>Height:</strong> " . $row["height"] . " inches<br>";
        echo "<strong>Bench Press:</strong> " . $row["bench_press"] . " lbs<br>";
        echo "<strong>Experience:</strong> " . $row["experience"] . "<br>";
        echo "<form method='POST' action='add_match.php' style='display:inline;'>
                <input type='hidden' name='opponent_id' value='" . $row["id"] . "'>
                <button type='submit' name='schedule_match'>Schedule</button>
            </form></p><hr>";
    }
} else {
    echo "<p>No opponents available.</p>";
}
$stmt->close();

// Handle scheduling a match
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["opponent_id"])) {
    $opponent_id = intval($_POST["opponent_id"]);

    // Ensure the user is not scheduling a match with themselves
    if ($opponent_id == $user_id) {
        die("<p>ERROR: You cannot schedule a match with yourself.</p>");
    }

    // Get the opponent's full name
    $stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
    $stmt->bind_param("i", $opponent_id);
    $stmt->execute();
    $stmt->bind_result($opponent_name);
    $stmt->fetch();
    $stmt->close();

    // Generate a random fight date between 30 and 90 days in the future
    $fight_date = date('Y-m-d', strtotime('+' . rand(30, 90) . ' days'));

    // Insert match into matches table
    $stmt = $conn->prepare("INSERT INTO matches (challenger_name, opponent_name, fight_date) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("<p>ERROR: Prepare statement failed - " . $conn->error . "</p>");
    }
    $stmt->bind_param("sss", $user_name, $opponent_name, $fight_date);

    if (!$stmt->execute()) {
        die("<p>ERROR: Execution failed - " . $stmt->error . "</p>");
    }

    $stmt->close();
    $conn->close();

    echo "<script>alert('Match scheduled successfully!'); window.location.href = 'dashboard.php';</script>";
}
?>
