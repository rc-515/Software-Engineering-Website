<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();

include __DIR__ . '/../db_implement.php';

$username = null;

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['email'] ?? null;
}

if (!$username) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

// Get requesting user's stats
$stmt = $conn->prepare("SELECT weight, height, bench_press, experience FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$userResult = $stmt->get_result();
$userData = $userResult->fetch_assoc();
$stmt->close();

if (!$userData) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

// Get all other users (potential opponents)
$stmt = $conn->prepare("SELECT username, full_name, weight, height, bench_press, experience FROM users WHERE username != ?");
$stmt->bind_param("s", $username);
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
exit;

// --- Utility functions ---
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
