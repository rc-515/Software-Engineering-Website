<?php
header('Content-Type: application/json');
include 'db_implement.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$currentUser = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['opponent_username']) || !isset($data['swipe_result'])) {
    echo json_encode(["error" => "Missing fields"]);
    exit;
}

$opponentUsername = $data['opponent_username'];
$swipeResult = $data['swipe_result'];

if (!in_array($swipeResult, ['accepted', 'rejected'])) {
    echo json_encode(["error" => "Invalid swipe result"]);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $currentUser);
$stmt->execute();
$userRow = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $opponentUsername);
$stmt->execute();
$opponentRow = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$userRow || !$opponentRow) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

$userId = $userRow['id'];
$opponentId = $opponentRow['id'];

$stmt = $conn->prepare("INSERT INTO swipes (user_id, opponent_id, swipe_result) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $userId, $opponentId, $swipeResult);

if ($stmt->execute()) {
    echo json_encode(["success" => "Swipe recorded"]);
} else {
    echo json_encode(["error" => "Failed to record swipe"]);
}

$stmt->close();
$conn->close();
?>
