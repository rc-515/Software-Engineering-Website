<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_implement.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Access denied.");
}

$user_id = $_SESSION["user_id"];

// Retrieve all matches
$stmt = $conn->prepare("SELECT match_id, challenger_name, opponent_name, fight_date FROM matches");
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>All Scheduled Matches</h3>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $challenger_name = $row["challenger_name"];
        $opponent_name = $row["opponent_name"];
        $fight_date = $row["fight_date"];
        $match_id = $row["match_id"];

        // Fetch challenger stats
        $stmt2 = $conn->prepare("SELECT weight, height, bench_press, experience FROM users WHERE full_name = ?");
        $stmt2->bind_param("s", $challenger_name);
        $stmt2->execute();
        $stmt2->bind_result($challenger_weight, $challenger_height, $challenger_bench, $challenger_experience);
        $stmt2->fetch();
        $stmt2->close();

        // Fetch opponent stats
        $stmt3 = $conn->prepare("SELECT weight, height, bench_press, experience FROM users WHERE full_name = ?");
        $stmt3->bind_param("s", $opponent_name);
        $stmt3->execute();
        $stmt3->bind_result($opponent_weight, $opponent_height, $opponent_bench, $opponent_experience);
        $stmt3->fetch();
        $stmt3->close();

        echo "<p><strong>Match ID:</strong> $match_id<br>";
        echo "<strong>Challenger:</strong> $challenger_name<br>";
        echo "<strong>Weight:</strong> $challenger_weight lbs | <strong>Height:</strong> $challenger_height inches | <strong>Bench Press:</strong> $challenger_bench lbs | <strong>Experience:</strong> $challenger_experience<br>";
        echo "<strong>Opponent:</strong> $opponent_name<br>";
        echo "<strong>Weight:</strong> $opponent_weight lbs | <strong>Height:</strong> $opponent_height inches | <strong>Bench Press:</strong> $opponent_bench lbs | <strong>Experience:</strong> $opponent_experience<br>";
        echo "<strong>Fight Date:</strong> $fight_date<br>";

        // Allow the user to edit or delete if they are part of the match
        if ($challenger_name == $_SESSION["full_name"] || $opponent_name == $_SESSION["full_name"]) {
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
        }

        echo "</p><hr>";
    }
} else {
    echo "<p>No scheduled matches.</p>";
}

$stmt->close();
$conn->close();
?>
