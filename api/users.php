<?php
include '../db_implement.php';
header('Content-Type: application/json');
$result = $conn->query("SELECT username, full_name FROM users");
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
echo json_encode($users);
$conn->close();
http_response_code(200);
?>
