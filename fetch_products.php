<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "electronics_store";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the components table
$sql = "SELECT id, name, image, details, category, price FROM components";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Output data in JSON format
header('Content-Type: application/json');
echo json_encode($products);

$conn->close();
?>
