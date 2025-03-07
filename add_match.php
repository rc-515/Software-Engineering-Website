<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["user_email"])) {
    die("Access denied.");
}

include 'db_implement.php';

$user_email = $_SESSION["user_email"];

// Fetch all users except the logged-in user
$stmt = $conn->prepare("SELECT email, full_name, weight, height, bench_press, experience FROM users WHERE email != ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>Available Opponents</h3>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>Opponent:</strong> " . htmlspecialchars($row["full_name"]) . "<br>";
        echo "<strong>Weight:</strong> " . htmlspecialchars($row["weight"]) . " lbs<br>";
        echo "<strong>Height:</strong> " . htmlspecialchars($row["height"]) . " inches<br>";
        echo "<strong>Bench Press:</strong> " . htmlspecialchars($row["bench_press"]) . " lbs<br>";
        echo "<strong>Experience:</strong> " . htmlspecialchars($row["experience"]) . "<br>";
        echo "<form method='POST' action='add_match.php' style='display:inline;'>
                <input type='hidden' name='opponent_email' value='" . htmlspecialchars($row["email"]) . "'>
                <button type='submit' name='schedule_match'>Schedule</button>
            </form></p><hr>";
    }
} else {
    echo "<p>No opponents available.</p>";
}
$stmt->close();

// Handle scheduling a match
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["opponent_email"])) {
    $opponent_email = $_POST["opponent_email"];

    if ($opponent_email == $user_email) {
        die("Error: You cannot schedule a match with yourself.");
    }

    // Insert match with emails (not names)
    $fight_date = date('Y-m-d', strtotime('+' . rand(30, 90) . ' days'));

    $stmt = $conn->prepare("INSERT INTO matches (challenger_name, opponent_name, fight_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user_email, $opponent_email, $fight_date);

    if ($stmt->execute()) {
        echo "<script>alert('Match scheduled successfully!'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Error scheduling match.'); window.location.href = 'dashboard.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
