<?php
session_start();
include 'config.php';

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty!'); window.location='products.php';</script>";
    exit;
}

// Use logged-in user's name
$username = $_SESSION['name'] ?? 'Guest';  // <-- change here

// Prepare cart items
$cart_products = [];
$ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
$cart_sql = "SELECT * FROM products WHERE id IN ($ids)";
$cart_result = $con->query($cart_sql);

while($row = $cart_result->fetch_assoc()) {
    $row['quantity'] = $_SESSION['cart'][$row['id']];
    $cart_products[] = $row;
}

// Insert order into orders table with default status 'Pending'
$stmt = $con->prepare("INSERT INTO orders (username, status) VALUES (?, 'Pending')");
$stmt->bind_param("s", $username);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// Insert each cart item into order_details table
foreach($cart_products as $item) {
    $stmt = $con->prepare("INSERT INTO order_details (order_id, product_name, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isid", $order_id, $item['name'], $item['quantity'], $item['price']);
    $stmt->execute();
    $stmt->close();
}

// Clear cart
$_SESSION['cart'] = [];

// Confirmation message
echo "<script>alert('Your order has been placed successfully!'); window.location='products.php';</script>";
exit;
?>
