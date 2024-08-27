<?php
// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin_login.php');
    exit;
}

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

// Handle item creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_item'])) {
    $name = $_POST['name'];
    $details = $_POST['details'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    
    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $image = 'images/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    } else {
        $image = NULL;
    }

    // Insert new item into the database
    $stmt = $conn->prepare("INSERT INTO components (name, image, details, category, price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $name, $image, $details, $category, $price);

    if ($stmt->execute()) {
        echo "New item created successfully.";
    } else {
        echo "Error creating item: " . $conn->error;
    }

    $stmt->close();
}

// Handle item updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_item'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $details = $_POST['details'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    
    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $image = 'images/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    } else {
        $image = $_POST['existing_image'];
    }

    // Update item in the database
    $stmt = $conn->prepare("UPDATE components SET name = ?, image = ?, details = ?, category = ?, price = ? WHERE id = ?");
    $stmt->bind_param("ssssdi", $name, $image, $details, $category, $price, $id);

    if ($stmt->execute()) {
        echo "Item updated successfully.";
    } else {
        echo "Error updating item: " . $conn->error;
    }

    $stmt->close();
}

// Handle item deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_item'])) {
    $id = $_POST['id'];

    // Delete item from the database
    $stmt = $conn->prepare("DELETE FROM components WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Item deleted successfully.";
    } else {
        echo "Error deleting item: " . $conn->error;
    }

    $stmt->close();
}

// Pagination setup
$items_per_page = 10;  // Number of items per page
$total_items_query = $conn->query("SELECT COUNT(*) AS total FROM components");
$total_items_row = $total_items_query->fetch_assoc();
$total_items = $total_items_row['total'];
$total_pages = ceil($total_items / $items_per_page);

// Get current page from URL, default to 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, min($current_page, $total_pages));

// Calculate the offset for the current page
$offset = ($current_page - 1) * $items_per_page;

// Fetch items for the current page
$result = $conn->query("SELECT * FROM components LIMIT $offset, $items_per_page");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="admin-styles.css">
</head>
<body>
    <div id="adminDashboard">
        <h2>Admin Dashboard</h2>
        <a href="admin_logout.php" class="home-link">Logout</a>
        
        <!-- Create Item Form -->
        <div id="createSection">
            <h3>Create New Item</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Name" required>
                <input type="text" name="details" placeholder="Details" required>
                <input type="text" name="category" placeholder="Category" required>
                <input type="number" name="price" placeholder="Price" step="0.01" required>
                <input type="file" name="image">
                <button type="submit" name="create_item">Create Item</button>
            </form>
        </div>
        
        <!-- Items Table -->
        <table border="1" style="width: 100%; border-collapse: collapse;">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Details</th>
                <th>Category</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <td><?php echo $row['id']; ?></td>
                    <td><input type="text" name="name" value="<?php echo $row['name']; ?>" required></td>
                    <td>
                        <img src="<?php echo $row['image']; ?>" alt="Image" style="max-width: 100px;"><br>
                        <input type="hidden" name="existing_image" value="<?php echo $row['image']; ?>">
                        <input type="file" name="image">
                    </td>
                    <td><input type="text" name="details" value="<?php echo $row['details']; ?>" required></td>
                    <td><input type="text" name="category" value="<?php echo $row['category']; ?>" required></td>
                    <td><input type="number" name="price" value="<?php echo $row['price']; ?>" step="0.01" required></td>
                    <td>
                        <button type="submit" name="update_item">Update</button>
                        <button type="submit" name="delete_item" style="background-color: #d9534f;">Delete</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Pagination Controls -->
        <div id="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?php echo $current_page - 1; ?>" class="pagination-link">Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="pagination-link <?php echo $i === $current_page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?php echo $current_page + 1; ?>" class="pagination-link">Next</a>
            <?php endif; ?>
        </div>
    </div>
    <a href="index.php" class="home-link">Back to Home</a>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
