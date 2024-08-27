<?php
// Database connection
$servername = "localhost";
$username = "root";     // Default username for MAMP
$password = "root";         // Default password for MAMP
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
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);

    // Execute statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if admin exists
    if ($result->num_rows > 0) {
        session_start();
        $_SESSION['loggedin'] = true;
        header('Location: admin_dashboard.php');
        exit;
    } else {
        echo "Invalid username or password.";
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
    <header>
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="admin-styles.css">
    <img src="images/obn.png" alt="Shop Logo" class="logo">

    </header
</head>
<body>
    <div id="loginSection">
        <h2>Admin Login</h2>
        <form method="POST" action="">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
