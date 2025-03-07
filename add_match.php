<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_implement.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form inputs
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $weight = intval($_POST["weight"]);
    $height = intval($_POST["height"]);
    $bench_press = intval($_POST["bench_press"]);
    $experience = $_POST["experience"];

    // Check if fields are empty
    if (empty($full_name) || empty($email) || empty($password) || empty($weight) || empty($height) || empty($bench_press) || empty($experience)) {
        die("Error: All fields must be filled out.");
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format.");
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        die("Error: Email already exists.");
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password_hash, weight, height, bench_press, experience) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiiis", $full_name, $email, $hashed_password, $weight, $height, $bench_press, $experience);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No POST request received.";
}
?>

// dashboard.php
<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION["user_id"])) {
    die("Access denied.");
}

$user_id = $_SESSION["user_id"];

// Get the current user's details
$stmt = $conn->prepare("SELECT weight, height, bench_press, experience FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_weight, $user_height, $user_bench, $user_experience);
$stmt->fetch();
$stmt->close();

// Find up to 5 potential matches with reasonable similarity and same experience level
$stmt = $conn->prepare("SELECT id, full_name FROM users WHERE id != ? AND experience = ? AND ABS(weight - ?) <= 10 AND ABS(height - ?) <= 3 AND ABS(bench_press - ?) <= 20 LIMIT 5");
$stmt->bind_param("isiii", $user_id, $user_experience, $user_weight, $user_height, $user_bench);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Your Scheduled Fights</h2>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Opponent: " . $row["full_name"] . "<br>";
    }
} else {
    echo "<p>Sorry, no suitable matches were found at this time.</p>";
}

$stmt->close();
$conn->close();
?>
