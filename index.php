<?php
// Database connection (db_connect.php)
$servername = "localhost";
$username = "root";  // Default username for MAMP
$password = "root";  // Default password for MAMP
$dbname = "electronics_store";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Mahmoud Al -Esawi Online Electronic Shop</title>
    <link rel="icon" href="icon.png" type="image/png">
</head>
<body>
    <header>
        <img src="images/obn.png" alt="Shop Logo" class="logo">
        <input type="text" id="searchBar" placeholder=" Search for items ..." onkeyup="searchItems()">
        <div>      
              <button id="adminbutton" onclick="window.location.href='admin_login.php'">Admin Login</button>
              <button id="userbutton" onclick="window.location.href='user_register.php'">User Registration</button>

            </div>
    </header>
    </header>
    <nav>
        <ul id="categoryList">
            <li onclick="loadCategory('All')">All</li>
            <li onclick="loadCategory('Arduino')">Arduino</li>
            <li onclick="loadCategory('Sensors')">Sensors</li>
            <li onclick="loadCategory('Resistors')">Resistors</li>
            <li onclick="loadCategory('Motors')">Motors</li>
            <li onclick="loadCategory('Raspberry Pi')">Raspberry Pi</li>
            <li onclick="loadCategory('Shields')">Shields</li>
            <!-- this is the place to take the items have 'name' in common in a list and show them -->
            <!-- Add more categories as needed -->
        </ul>
    </nav>
    <div class="contanier">
        <aside>
            <h2>Shopping Cart</h2>
            <ul id="cartItems"></ul>
            <strong>Total: $<span id="totalPrice">0.00</span></strong>
            <button id="clearCart" onclick="clearCart()">Clear Cart</button> <!-- Clear Cart Button -->
            
            <h2>Payment Method</h2>
            <select id="paymentMethod">
                <option value="creditCard">Credit Card</option>
                <option value="paypal">PayPal</option>
                <option value="bankTransfer">Bank Transfer</option>
            </select>
            <button id="checkout" onclick="checkout()">Checkout</button> <!-- Checkout Button -->
        </aside>
        
    </aside>
    
    <main id="productContainer"><!-- here i dev the product --> 
        <div id="productList"></div>
        <div id="itemDetails" style="display: none;"></div>
        <div id="pagination"><!-- this for the pages num  --> 
            <button id="prevPage" onclick="changePage(-1)">Back</button>
            <span id="pageNumber">1</span>
            <button id="nextPage" onclick="changePage(1)">Next</button>
            <!-- In index.html or admin.html -->

        </div>
    </main>
</div>
<footer>
    <p>&copy; 2024 Mahmoud Al-Esawi. All rights reserved.</p>
</footer>
    
    <script src="script.js"></script>
</body>
</html>
