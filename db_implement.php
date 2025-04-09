<?php
// DEVELOPMENT VERSION FOR LOCAL TESTING
$host = "localhost";
$user = "root";
$password = "";
$database = "app_db";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
