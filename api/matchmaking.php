<?php
header('Content-Type: application/json');
include 'db_implement.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$currentUser = $_SESSION['username'];

$stmt = $conn->prepare("SELECT weight, height, bench_press, experience FROM users WHERE username = ?");
$stmt->bind_param("s", $currentUser);
$stmt->execute();
$userResult = $stmt->get_result();
$userData = $userResult->fetch_assoc();
$stmt->close();

if (!$userData) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

$stmt = $conn->prepare("SELECT username, full_name, weight, height, bench_press, experience FROM users WHERE username != ?");
$stmt->bind_param("s", $currentUser);
$stmt->execute();
$opponentResult = $stmt->get_result();

$potentialMatches = [];

while ($row = $opponentResult->fetch_assoc()) {
    $winProbability = calculateWinProbability($userData, $row);

    $potentialMatches[] = [
        "username" => $row['username'],
        "full_name" => $row['full_name'],
        "weight" => $row['weight'],
        "height" => $row['height'],
        "bench_press" => $row['bench_press'],
        "experience" => $row['experience'],
        "win_probability" => $winProbability
    ];
}

echo json_encode(["matches" => $potentialMatches]);
$conn->close();

function calculateWinProbability($user, $opponent) {
    $scoreUser = ($user['weight'] * 0.2) + ($user['height'] * 0.1) + ($user['bench_press'] * 0.7) + experienceToScore($user['experience']);
    $scoreOpponent = ($opponent['weight'] * 0.2) + ($opponent['height'] * 0.1) + ($opponent['bench_press'] * 0.7) + experienceToScore($opponent['experience']);

    if ($scoreUser + $scoreOpponent == 0) return 50;
    return round(($scoreUser / ($scoreUser + $scoreOpponent)) * 100, 2);
}

function experienceToScore($exp) {
    switch (strtolower($exp)) {
        case "beginner": return 10;
        case "intermediate": return 20;
        case "advanced": return 30;
        default: return 15;
    }
}
?>
