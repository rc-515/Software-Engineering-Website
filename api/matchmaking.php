<?php
session_start();
include 'db_implement.php';

// Validate logged-in user
if (!isset($_SESSION['user_name'])) {
    die("Unauthorized access.");
}

// Get all potential opponents EXCEPT self
$userName = $_SESSION['user_name'];
$sql = "SELECT full_name, weight, height, bench_press, experience FROM users WHERE full_name != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userName);
$stmt->execute();
$result = $stmt->get_result();

$opponents = [];
while ($row = $result->fetch_assoc()) {
    // Simple win probability calc: 50 + weight/10 - opponent weight/10
    $winProb = 50 + ($row['weight'] - 180) / 2;  // assuming 180 is your weight
    $winProb = max(0, min(100, round($winProb))); // clamp 0-100

    $opponents[] = [
        'name' => $row['full_name'],
        'weight' => $row['weight'],
        'height' => $row['height'],
        'bench_press' => $row['bench_press'],
        'experience' => $row['experience'],
        'win_probability' => $winProb
    ];
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fight Matchmaking</title>
    <link rel="stylesheet" href="dashboardStyle.css">
    <style>
        .message { color: yellow; font-weight: bold; margin: 10px; }
    </style>
    <script>
        let opponents = <?php echo json_encode($opponents); ?>;
        let current = 0;

        function showOpponent() {
            if (current >= opponents.length) {
                document.getElementById('opponentBox').innerHTML = "No more opponents.";
                document.getElementById('acceptBtn').style.display = 'none';
                document.getElementById('rejectBtn').style.display = 'none';
                return;
            }
            let o = opponents[current];
            document.getElementById('opponentBox').innerHTML = `
                <strong>${o.name}</strong><br>
                Weight: ${o.weight} lbs<br>
                Height: ${o.height} inches<br>
                Bench Press: ${o.bench_press} lbs<br>
                Experience: ${o.experience}<br>
                Win Probability: ${o.win_probability}%
            `;
            document.getElementById('message').innerText = "";
        }

        function acceptMatch() {
            let o = opponents[current];
            fetch('matchmaking.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `action=accept&opponent_name=${encodeURIComponent(o.name)}`
            })
            .then(res => res.text())
            .then(data => {
                console.log(data);
                document.getElementById('message').innerText = "Match accepted!";
                setTimeout(() => { current++; showOpponent(); }, 1000);
            });
        }

        function rejectMatch() {
            document.getElementById('message').innerText = "Match rejected.";
            setTimeout(() => { current++; showOpponent(); }, 1000);
        }

        window.onload = showOpponent;
    </script>
</head>
<body>
    <div class="center-box">
        <h2>Find Your Fight</h2>
        <div id="opponentBox">Loading...</div>
        <div class="message" id="message"></div>
        <button id="acceptBtn" onclick="acceptMatch()" class="accept-button">Accept</button>
        <button id="rejectBtn" onclick="rejectMatch()" class="reject-button">Reject</button>
        <br><br>
        <button onclick="window.location.href='dashboard.php'" class="dashboard-button">Back to Dashboard</button>
    </div>

<?php
// Handle Accept POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'accept' && isset($_POST['opponent_name'])) {
    include 'db_implement.php';
    $challenger = $_SESSION['user_name'];
    $opponent = $_POST['opponent_name'];
    $fightDate = date('Y-m-d', strtotime('+7 days'));

    $stmt = $conn->prepare("INSERT INTO scheduled_matches (challenger_name, opponent_name, fight_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $challenger, $opponent, $fightDate);
    if ($stmt->execute()) {
        echo "Match inserted successfully.";
    } else {
        echo "Error inserting match: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    exit;
}
?>
</body>
</html>
