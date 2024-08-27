<?php
// Database connection
$host = 'localhost'; // Replace with your database host
$dbname = 'electronics_store'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Admin credentials
$email = 'admin@example.com'; // Replace with your admin email
$plain_password = 'adminpassword'; // Replace with your admin password

// Hash the password
$hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);

try {
    // Prepare and execute the query
    $stmt = $pdo->prepare("INSERT INTO admin (email, password) VALUES (:email, :password)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->execute();
    echo "Admin user created successfully.";
} catch (PDOException $e) {
    die("Error inserting admin user: " . $e->getMessage());
}
