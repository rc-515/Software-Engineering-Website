<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/../db_implement.php';

// Get POST body
$input = json_decode(file_get_contents('php://input'), true);
$challenger = $input['email'] ?? null;
$opponent = $input['opponent_username'] ?? null;
$swipe_result = $input['swipe_result'] ?? null;

if (!$challenger || !$opponent || $swipe_result !== 'accepted') {
    echo json_encode(["error" => "Unauthorized or irrelevant swipe"]);
    exit;
}

// Generate a random fight date 30–90 days from now
$daysAhead = rand(30, 90);
$fight_date = date('Y-m-d', strtotime("+$daysAhead days"));

// Insert into matches table
$stmt = $conn->prepare("INSERT INTO matches (challenger_name, opponent_name, fight_date) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $challenger, $opponent, $fight_date);

if ($stmt->execute()) {
    echo json_encode(["result" => "match", "fight_date" => $fight_date]);
} else {
    echo json_encode(["error" => "Failed to create match", "details" => $stmt->error]);
}

$stmt->close();
$conn->close();
