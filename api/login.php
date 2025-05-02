<?php
include '../db_implement.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$email = $data["email"] ?? '';
$password = $data["password"] ?? '';

$stmt = $conn->prepare("SELECT full_name, password_hash FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($full_name, $password_hash);
$stmt->fetch();

if ($full_name && password_verify($password, $password_hash)) {
    http_response_code(201);
    echo json_encode([
        "success" => true,
        "full_name" => $full_name,
        "email" => $email
    ]);
} else {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Invalid credentials"]);
}

$stmt->close();
$conn->close();
?>

