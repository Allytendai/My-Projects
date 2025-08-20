<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="description" content="Online grocery store">
    <!--SEO -->
    <meta name="keywords" content="grocery, shopping, online">
    <title>Grocery Store - Home</title>
    <!--Bootstrap CSS for responsive styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--custom styles for consistent design -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Main container for page content -->
    <div class="container">
        <h1>Sanel Grocery Store</h1>

        <?php if (isset($_SESSION['email'])): ?>
            <!-- Display users info and navigation for logged-in users -->
            <p>
                Logged in as <?php echo htmlspecialchars($_SESSION['email']); ?> | 
                <a href="orders.php" class="btn btn-primary">Go to Orders</a> | 
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </p>
        <?php else: ?>
            <!-- Display login/register links for non-logged-in users -->
            <p>
                <a href="login.php" class="btn btn-primary">Login</a> | 
                <a href="register.php" class="btn btn-success">Register</a>
            </p>
        <?php endif; ?>

        <h2>Browse Products</h2>
        <!-- Dropdown for selecting product category -->
        <select id="category" class="form-select" onchange="loadProducts()" aria-label="Select product category">
            <option value="">Select Category</option>
            <option value="Vegetables">Vegetables</option>
            <option value="Meat">Meat</option>
            <option value="Fruits">Fruits</option>
            <option value="Dairy">Dairy</option>
        </select>
        <!-- Dropdown for selecting products (initially disabled) -->
        <select id="product" class="form-select" disabled aria-label="Select product">
            <option value="">Select Product</option>
        </select>
        <!-- for displaying product details -->
        <div id="product-details"></div>
        <!-- Button to place an order (initially disabled) -->
        <button id="order-btn" class="btn btn-primary" onclick="placeOrder()" disabled>Place Order</button>

        <footer>
            <p>Sanel Grocery Store. All rights reserved © 2025.</p>
        </footer>
    </div>

    <!-- Bootstrap JavaScript for interactive components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        //  loads products based on selected category
        function loadProducts() {
            const category = document.getElementById('category').value;
            // Gets references to product dropdown, details div, and order button
            const productSelect = document.getElementById('product');
            const detailsDiv = document.getElementById('product-details');
            const orderBtn = document.getElementById('order-btn');

            // Disables product dropdown and clear existing options
            productSelect.disabled = true;
            productSelect.innerHTML = '<option value="">Select Product</option>';
            // Clear product details and disable order button
            detailsDiv.innerHTML = '';
            orderBtn.disabled = true;

            // If a category is selected, fetch products
            if (category) {
                fetch(`get_products.php?category=${encodeURIComponent(category)}`)
                    .then(response => {
                        // Checks if the response is valid
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Checks if the response is successful
                        if (data.success) {
                            // Uses data.data to access the products array
                            const products = data.data; 
                            if (products.length === 0) {
                                detailsDiv.innerHTML = '<p class="text-warning">No products found for this category.</p>';
                                return;
                            }
                            // Populates product dropdown with fetched products
                            products.forEach(product => {
                                const option = document.createElement('option');
                                option.value = product.id;
                                option.textContent = product.name;
                                productSelect.appendChild(option);
                            });
                            // Enables product dropdown
                            productSelect.disabled = false;
                        } else {
                            detailsDiv.innerHTML = `<p class="text-danger">${data.message}</p>`;
                        }
                    })
                    .catch(error => {
                        // Logs error and show user-friendly message
                        console.error('Error loading products:', error);
                        detailsDiv.innerHTML = '<p class="text-danger">Failed to load products. Please try again.</p>';
                    });
            }
        }

        document.getElementById('product').addEventListener('change', function() {
            const productId = this.value;
            const detailsDiv = document.getElementById('product-details');
            const orderBtn = document.getElementById('order-btn');

            detailsDiv.innerHTML = '';
            orderBtn.disabled = true;

            if (productId) {
                fetch(`get_product_details.php?id=${productId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(response => {
                        if (response.success) {
                            const product = response.data;
                            detailsDiv.innerHTML = `
                                <h3>${product.name}</h3>
                                ${product.image ? `<img src="${product.image}" alt="${product.name}" class="product-image">` : ''}
                                <p>Price: £${product.price}</p>
                                <p>${product.description}</p>
                            `;
                            orderBtn.disabled = false;
                        } else {
                            detailsDiv.innerHTML = `<p class="text-danger">${response.message}</p>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading details:', error);
                        detailsDiv.innerHTML = '<p class="text-danger">Failed to load product details. Please try again.</p>';
                    });
            }
        });

        // Function to handle order placement
        function placeOrder() {
            <?php if (!isset($_SESSION['email'])): ?>
                // Redirects to login page if user is not logged in
                window.location.href = 'login.php';
            <?php else: ?>
                // Gets the selected product ID
                const productId = document.getElementById('product').value;
                // Gets the details div for error messages
                const detailsDiv = document.getElementById('product-details');

                // Validates that a product is selected
                if (productId) {
                    // Sends order request to server
                    fetch('submit_order.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ productId })
                    })
                        .then(response => {
                            // Checks if the response is valid
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Handles server response
                            if (data.success) {
                                alert('Order placed successfully!');
                                window.location.href = 'orders.php';
                            } else {
                                alert(`Order failed: ${data.message}`);
                                detailsDiv.innerHTML += `<p class="text-danger">${data.message}</p>`;
                            }
                        })
                        .catch(error => {
                            // Logs error and show user-friendly message
                            console.error('Order error:', error);
                            alert('Order failed. Please try again.');
                            detailsDiv.innerHTML += '<p class="text-danger">Failed to place order. Please try again.</p>';
                        });
                } else {
                    // Shows error if no product is selected
                    alert('Please select a product.');
                    detailsDiv.innerHTML = '<p class="text-danger">Please select a product.</p>';
                }
            <?php endif; ?>
        }
    </script>
</body>
</html>