<?php
include '../db_implement.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$full_name = trim($data["full_name"]);
$email = trim($data["email"]);
$password = $data["password"];
$weight = intval($data["weight"]);
$height = intval($data["height"]);
$bench_press = intval($data["bench_press"]);
$experience = $data["experience"];
$username = $email; // Make username a duplicate of email

if (!$full_name || !$email || !$password || !$weight || !$height || !$bench_press || !$experience) {
    http_response_code(400);
    echo json_encode(["error" => "All fields required"]);
    exit();
}

if (strlen($password) < 10) {
    http_response_code(400);
    echo json_encode(["error" => "Password must be at least 10 characters"]);
    exit();
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["error" => "Email already exists"]);
    exit();
}
$stmt->close();

$stmt = $conn->prepare("INSERT INTO users (full_name, email, username, password_hash, weight, height, bench_press, experience) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssiiis", $full_name, $email, $username, $hashed, $weight, $height, $bench_press, $experience);

if ($stmt->execute()) {
    echo json_encode(["message" => "Registration successful"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => $stmt->error]);
}
$stmt->close();
$conn->close();
?>
