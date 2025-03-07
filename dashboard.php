<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["username"])) {
    die("Access denied. Please <a href='login.php'>log in</a>.");
}

include 'db_implement.php';

// Fetch user details from session
$username = $_SESSION["username"];
$full_name = $_SESSION["full_name"];
$user_email = $_SESSION["user_email"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboardStyle.css">
</head>
<body>
    <div class="container">
        <p><strong>Logged in as:</strong> <?php echo htmlspecialchars($full_name) . " (" . htmlspecialchars($user_email) . ")"; ?></p>
        <hr>

        <h2>Your Scheduled Fights</h2>
        <div id="scheduledMatches">Loading...</div>

        <h2>Potential Matches</h2>
        <div id="potentialMatches">Loading...</div>

        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <script>
        function fetchScheduledMatches() {
            fetch("view_matches.php", { credentials: "include" })
            .then(response => response.text())
            .then(data => {
                document.getElementById("scheduledMatches").innerHTML = data;
            })
            .catch(error => console.error("Error fetching scheduled matches:", error));
        }

        function fetchPotentialMatches() {
            fetch("add_match.php", { credentials: "include" })
            .then(response => response.text())
            .then(data => {
                document.getElementById("potentialMatches").innerHTML = data;
            })
            .catch(error => console.error("Error fetching potential matches:", error));
        }

        window.onload = function() {
            fetchScheduledMatches();
            fetchPotentialMatches();
        };
    </script>
</body>
</html>
