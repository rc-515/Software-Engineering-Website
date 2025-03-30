<?php
include '../db_implement.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER['REQUEST_METHOD'];

// Allow preflight requests to return early
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit();
}

switch ($method) {
    case 'GET':
        // Optional filtering by email
        $email = $_GET['email'] ?? null;

        if ($email) {
            $stmt = $conn->prepare("SELECT * FROM matches WHERE challenger_name = ? OR opponent_name = ?");
            $stmt->bind_param("ss", $email, $email);
        } else {
            $stmt = $conn->prepare("SELECT * FROM matches");
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $matches = [];

        while ($row = $result->fetch_assoc()) {
            $matches[] = $row;
        }

        echo json_encode($matches);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['challenger_name'], $data['opponent_name'], $data['fight_date'])) {
            http_response_code(400);
            echo json_encode(["error" => "Missing fields"]);
            exit();
        }

        $challenger = $data['challenger_name'];
        $opponent = $data['opponent_name'];
        $fight_date = $data['fight_date'];

        if ($challenger === $opponent) {
            http_response_code(400);
            echo json_encode(["error" => "Cannot match with yourself"]);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO matches (challenger_name, opponent_name, fight_date) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $challenger, $opponent, $fight_date);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Match created"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => $stmt->error]);
        }

        break;

    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'], $query);
        $match_id = $query['id'] ?? null;
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$match_id || !isset($data['fight_date'])) {
            http_response_code(400);
            echo json_encode(["error" => "Missing match ID or fight_date"]);
            exit();
        }

        $fight_date = $data['fight_date'];
        $stmt = $conn->prepare("UPDATE matches SET fight_date = ? WHERE match_id = ?");
        $stmt->bind_param("si", $fight_date, $match_id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Match updated"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => $stmt->error]);
        }

        break;

    case 'DELETE':
        parse_str($_SERVER['QUERY_STRING'], $query);
        $match_id = $query['id'] ?? null;

        if (!$match_id) {
            http_response_code(400);
            echo json_encode(["error" => "Missing match ID"]);
            exit();
        }

        $stmt = $conn->prepare("DELETE FROM matches WHERE match_id = ?");
        $stmt->bind_param("i", $match_id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Match deleted"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => $stmt->error]);
        }

        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method Not Allowed"]);
        break;
}

$conn->close();
?>
