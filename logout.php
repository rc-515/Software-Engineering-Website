<?php
session_start();
session_destroy();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out</title>
    <link rel="stylesheet" href="SignUpStyle.css"> <!-- Use the same CSS file for consistency -->
</head>
<body>
    <div class="container">
        <h1>Logged Out Successfully</h1>
        <p>You have been logged out. Thank you for using our service!</p>
        <a href="index.html" class="back-link">Return to Home</a> <!-- Link back to the home page -->
    </div>
</body>
</html>