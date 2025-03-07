<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    die("Access denied.");
}

include 'db_implement.php';

// Fetch user details
$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT full_name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($full_name, $email);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboardStyle.css">
    <script>
        function fetchScheduledMatches() {
            fetch("view_matches.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("scheduledMatches").innerHTML = data;
            })
            .catch(error => console.error("Error:", error));
        }
        
        function fetchPotentialMatches() {
            fetch("add_match.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("potentialMatches").innerHTML = data;
            })
            .catch(error => console.error("Error:", error));
        }

        function scheduleMatch(opponentId) {
            let fightDate = prompt("Enter fight date (YYYY-MM-DD):");
            if (!fightDate) return;
            
            let formData = new FormData();
            formData.append("opponent_id", opponentId);
            formData.append("fight_date", fightDate);

            fetch("schedule_match.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                fetchScheduledMatches();
            })
            .catch(error => console.error("Error:", error));
        }
        
        window.onload = function() {
            fetchScheduledMatches();
            fetchPotentialMatches();
        };
    </script>
</head>
<body>
    <div class="container">
        <p><strong>Logged in as:</strong> <?php echo $full_name . " (" . $email . ")"; ?></p>
        <hr>

        <h2>Your Scheduled Fights</h2>
        <div id="scheduledMatches"></div>

        <h2>Potential Matches</h2>
        <div id="potentialMatches"></div>
        
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>
