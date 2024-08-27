<?php
// Database connection
$servername = "localhost";
$username = "root"; // Update with your database username
$password = "root"; // Update with your database password
$dbname = "electronics_store";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    // Execute statement
    if ($stmt->execute()) {
        echo "User registered successfully!";
        // Redirect to login page or other actions
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<img src="images/obn.png" alt="Shop Logo" class="logo">

    <title>User Registration</title>
    <link rel="stylesheet" type="text/css" href="admin-styles.css">
</head>
<body>
    <div id="registrationSection">
        <h2>User Registration</h2>
        <form method="POST" action="">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Register">
        </form>
        <a href="index.php" class="home-link">Back to Home</a>
    </div>
</body>
</html>
