<?php
// Set response header to JSON for API output
header('Content-Type: application/json');

// Enables error logging, disable display to prevent JSON corruption
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Initializes output buffering to catch unexpected output
ob_start();

// Establishs database connection using provided credentials
$conn = new mysqli('localhost', 'x9h24', 'x9h24x9h24', 'x9h24');
if ($conn->connect_error) {
    // Returns error response if database connection fails
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get categorys from query parameter, default to empty string if not provided
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Validates category input with stricter sanitization
if (empty($category) || !preg_match('/^[a-zA-Z\s]+$/', $category)) {
    // Returns error response for missing, empty, or invalid category (letters and spaces only)
    ob_end_clean();
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Valid category is required (letters and spaces only)']);
    exit;
}

// Prepares SQL query to fetch products by category securely, including more details
$stmt = $conn->prepare("SELECT id, name, category, price, description, image FROM products WHERE category = ?");
if (!$stmt) {
    // Returns error response if query preparation fails
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Query preparation failed: ' . $conn->error]);
    $conn->close();
    exit;
}

// Binds category parameter to prevent SQL injection
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

// Checks for query execution errors
if ($result === false) {
    // Returns error response if query execution fails
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Query execution failed']);
    $stmt->close();
    $conn->close();
    exit;
}

// Initializes array to store products
$products = [];
while ($row = $result->fetch_assoc()) {
    // Adds each product with all available fields to the array
    $products[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'category' => $row['category'],
        'price' => floatval($row['price']), 
        'description' => $row['description'] ?? '', // Handles NULL descriptions
        'image' => $row['image'] ?? '' // Handles NULL images
    ];
}

// Closes statement and database connection to free resources
$stmt->close();
$conn->close();

// Checks if products were found
if (empty($products)) {
    // Returns success response with empty array if no products found
    ob_end_clean();
    http_response_code(200);
    echo json_encode(['success' => true, 'data' => [], 'message' => 'No products found for this category']);
    exit;
}

// Returns successful response with product data
ob_end_clean();
http_response_code(200);
echo json_encode(['success' => true, 'data' => $products]);
?>