// cart.js

// Function to add item to the cart
function addToCart(userId, componentId, quantity) {
    $.ajax({
        url: 'cart_action.php',
        type: 'POST',
        data: {
            action: 'add',
            user_id: userId,
            component_id: componentId,
            quantity: quantity
        },
        success: function(response) {
            console.log("Item added to cart:", response);
            updateCartDisplay();
        },
        error: function(xhr, status, error) {
            console.error("Error adding item to cart:", status, error);
        }
    });
}

// Function to remove item from the cart
function removeFromCart(userId, componentId) {
    $.ajax({
        url: 'cart_action.php',
        type: 'POST',
        data: {
            action: 'remove',
            user_id: userId,
            component_id: componentId
        },
        success: function(response) {
            console.log("Item removed from cart:", response);
            updateCartDisplay();
        },
        error: function(xhr, status, error) {
            console.error("Error removing item from cart:", status, error);
        }
    });
}

// Function to clear the cart
function clearCart(userId) {
    $.ajax({
        url: 'cart_action.php',
        type: 'POST',
        data: {
            action: 'clear',
            user_id: userId
        },
        success: function(response) {
            console.log("Cart cleared:", response);
            updateCartDisplay();
        },
        error: function(xhr, status, error) {
            console.error("Error clearing cart:", status, error);
        }
    });
}

// Function to calculate the total price
function calculateTotalPrice(userId) {
    $.ajax({
        url: 'cart_action.php',
        type: 'POST',
        data: {
            action: 'calculate_total',
            user_id: userId
        },
        success: function(response) {
            console.log("Total Price:", response);
        },
        error: function(xhr, status, error) {
            console.error("Error calculating total price:", status, error);
        }
    });
}

// Function to update the cart display (placeholder for UI update)
function updateCartDisplay() {
    // Example: Update the cart display based on your application's UI
    console.log("Cart display updated.");
}
