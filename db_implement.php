<?php
$servername = "sql206.infinityfree.com";
$username = "if0_38463553"; 
$password = "GlotzerCahill"; 
$database = "if0_38463553_appdb"; 

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
