<?php
header('Content-Type: application/json');
include '../db_implement.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Method Not Allowed']);
    exit;
}

$data = $_POST;



$full_name = trim($data['full_name'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$weight = intval($data['weight'] ?? 0);
$height = intval($data['height'] ?? 0);
$bench_press = intval($data['bench_press'] ?? 0);
$experience = trim($data['experience'] ?? 'Beginner');
$username = $email;  // Use email as username to satisfy PRIMARY KEY

// Basic validation
if (!$full_name || !$email || !$password || !$weight || !$height || !$bench_press || !$experience) {
    http_response_code(400);
    echo json_encode(['message' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid email format.']);
    exit;
}

if (strlen($password) < 10) {
    http_response_code(400);
    echo json_encode(['message' => 'Password must be at least 10 characters.']);
    exit;
}

// Check duplicate username/email
$stmt = $conn->prepare("SELECT username FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    http_response_code(400);
    echo json_encode(['message' => 'Username or Email already exists.']);
    exit;
}
$stmt->close();

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$stmt = $conn->prepare("
    INSERT INTO users (username, full_name, email, password_hash, weight, height, bench_press, experience)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param(
    "ssssiiis",
    $username, $full_name, $email, $hashed_password, $weight, $height, $bench_press, $experience
);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(['message' => 'Registration successful.']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Database error: '.$stmt->error]);
}
$stmt->close();
$conn->close();
?>

