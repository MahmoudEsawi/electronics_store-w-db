// Utility functions for cookies
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name + '=; Max-Age=-99999999;';
}

// Arrays that will hold the list of all products and the currently filtered products
let products = [];
let filteredProducts = [];
let currentPage = 1; // Tracks the current page number for pagination
const itemsPerPage = 12; // Sets the number of products displayed per page (12 in this case)
let cart = []; // Array that will store items added to the shopping cart

// Load products from localStorage or fetch from JSON
function loadProducts() {
    const savedProducts = localStorage.getItem('products');
    if (savedProducts) {
        products = JSON.parse(savedProducts);
        filteredProducts = products;
        renderProducts();
    } else {
        fetch('products.json')
            .then(response => response.json())
            .then(data => {
                products = data;
                filteredProducts = products;
                localStorage.setItem('products', JSON.stringify(products)); // Save initial data to localStorage
                renderProducts();
            });
    }
}

// Load cart from cookies
function loadCart() {
    cart = JSON.parse(getCookie('cart') || '[]');
    updateCart(); // Update the cart display based on the loaded cart
}

// Render products on the page
function renderProducts() {
    const productList = document.getElementById('productList');
    productList.innerHTML = '';
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedItems = filteredProducts.slice(startIndex, endIndex);

    paginatedItems.forEach(product => {
        const productDiv = document.createElement('div');
        productDiv.classList.add('product');
        productDiv.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>${product.details}</p>
            <p>$${product.price.toFixed(2)}</p>
            <button onclick="addToCart(${product.id})">Add to Cart</button>
        `;
        productList.appendChild(productDiv);
    });

    document.getElementById('pageNumber').textContent = currentPage;
}

// Filter products based on the selected category
function loadCategory(category) {
    filteredProducts = category === 'All' ? products : products.filter(product => product.category === category);
    currentPage = 1;
    renderProducts();
}

// Search products based on the search query
function searchItems() {
    const query = document.getElementById('searchBar').value.toLowerCase();
    filteredProducts = products.filter(product => product.name.toLowerCase().includes(query) || product.details.toLowerCase().includes(query));
    currentPage = 1;
    renderProducts();
}

// Change page for pagination
function changePage(direction) {
    const maxPage = Math.ceil(filteredProducts.length / itemsPerPage);
    if (direction === -1 && currentPage > 1) {
        currentPage--;
    } else if (direction === 1 && currentPage < maxPage) {
        currentPage++;
    }
    renderProducts();
}

// Add a product to the cart
function addToCart(id) {
    const product = products.find(product => product.id === id);
    let cart = JSON.parse(getCookie('cart') || '[]');
    const cartItem = cart.find(item => item.id === id);

    if (cartItem) {
        cartItem.quantity++;
    } else {
        cart.push({ ...product, quantity: 1 });
    }

    setCookie('cart', JSON.stringify(cart), 7); // Save cart to cookie for 7 days
    updateCart();
}

// Remove a product from the cart
function removeFromCart(id) {
    let cart = JSON.parse(getCookie('cart') || '[]');
    const cartItemIndex = cart.findIndex(item => item.id === id);

    if (cartItemIndex > -1) {
        cart[cartItemIndex].quantity--;
        if (cart[cartItemIndex].quantity === 0) {
            cart.splice(cartItemIndex, 1);
        }
    }

    setCookie('cart', JSON.stringify(cart), 7); // Save cart to cookie for 7 days
    updateCart();
}

// Update the cart display
function updateCart() {
    const cart = JSON.parse(getCookie('cart') || '[]');
    const cartItems = document.getElementById('cartItems');
    cartItems.innerHTML = '';
    let totalPrice = 0;

    cart.forEach(item => {
        const cartItem = document.createElement('li');
        cartItem.innerHTML = `
            ${item.name} - $${item.price.toFixed(2)} x ${item.quantity}
            <button onclick="removeFromCart(${item.id})">Remove</button>
        `;
        cartItems.appendChild(cartItem);
        totalPrice += item.price * item.quantity;
    });

    document.getElementById('totalPrice').textContent = totalPrice.toFixed(2);
}

// Clear the cart
function clearCart() {
    eraseCookie('cart'); // Clear cart cookie
    cart = []; // Empty the cart array
    updateCart(); // Update the cart display
}

// Handle checkout
function checkout() {
    const paymentMethod = document.getElementById('paymentMethod').value;
    if (cart.length === 0) {
        alert("Your cart is empty.");
    } else {
        alert(`You have selected ${paymentMethod}. Proceeding to checkout.`);
        // Here you can implement further checkout logic
    }
}

// Load products and cart on page load
loadProducts();
loadCart();
