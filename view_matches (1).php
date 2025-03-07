<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["user_email"])) {
    die("Access denied.");
}

include 'db_implement.php';

$user_email = $_SESSION["user_email"];

// Retrieve all matches and get challenger & opponent details from the `users` table
$stmt = $conn->prepare("
    SELECT m.match_id, m.challenger_name, m.opponent_name, m.fight_date, 
           u1.full_name AS challenger_full_name, u1.weight AS challenger_weight, u1.height AS challenger_height, 
           u1.bench_press AS challenger_bench, u1.experience AS challenger_experience,
           u2.full_name AS opponent_full_name, u2.weight AS opponent_weight, u2.height AS opponent_height, 
           u2.bench_press AS opponent_bench, u2.experience AS opponent_experience
    FROM matches m
    JOIN users u1 ON m.challenger_name = u1.email
    JOIN users u2 ON m.opponent_name = u2.email
");
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>All Scheduled Matches</h3>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $match_id = $row["match_id"];
        $challenger_name = $row["challenger_full_name"];
        $challenger_weight = $row["challenger_weight"];
        $challenger_height = $row["challenger_height"];
        $challenger_bench = $row["challenger_bench"];
        $challenger_experience = $row["challenger_experience"];

        $opponent_name = $row["opponent_full_name"];
        $opponent_weight = $row["opponent_weight"];
        $opponent_height = $row["opponent_height"];
        $opponent_bench = $row["opponent_bench"];
        $opponent_experience = $row["opponent_experience"];

        $fight_date = $row["fight_date"];

        echo "<p><strong>Match ID:</strong> $match_id<br>";
        echo "<strong>Challenger:</strong> $challenger_name<br>";
        echo "Weight: $challenger_weight lbs | Height: $challenger_height inches | Bench Press: $challenger_bench lbs | Experience: $challenger_experience <br>";
        echo "<strong>Opponent:</strong> $opponent_name<br>";
        echo "Weight: $opponent_weight lbs | Height: $opponent_height inches | Bench Press: $opponent_bench lbs | Experience: $opponent_experience <br>";
        echo "<strong>Fight Date:</strong> $fight_date</p>";

        // Check if the logged-in user's email matches either the challenger or the opponent
        if ($user_email == $row["challenger_name"] || $user_email == $row["opponent_name"]) {
            echo "<form method='POST' action='edit_match.php' style='display:inline;'>
                    <input type='hidden' name='match_id' value='$match_id'>
                    <label for='new_date'>New Date:</label>
                    <input type='date' name='new_date' required>
                    <button type='submit' name='edit_match'>Edit</button>
                </form>";

            echo "<form method='POST' action='delete_match.php' style='display:inline;'>
                    <input type='hidden' name='match_id' value='$match_id'>
                    <button type='submit' name='delete_match'>Delete</button>
                </form>";
        } else {
            echo "<p><em>You cannot edit or delete this match.</em></p>";
        }

        echo "<hr>";
    }
} else {
    echo "<p>No scheduled matches.</p>";
}

$stmt->close();
$conn->close();
?>
