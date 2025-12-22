<?php
session_start();
include 'config.php';

// Make sure cart exists
if (empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty!'); window.location='products.php';</script>";
    exit;
}

// Check if payment_id is received (for online payments)
$payment_id = $_GET['payment_id'] ?? null;

// User info
$username = $_SESSION['name'] ?? 'Guest';

// Determine payment status
$payment_status = $payment_id ? 'Paid' : 'Pending Payment';

// Default order status
$order_status = 'Pending'; // New orders start as Pending

// Insert order into orders table
$stmt = $con->prepare("
    INSERT INTO orders (username, payment_id, payment_status, order_status, created_at)
    VALUES (?, ?, ?, ?, NOW())
");
$stmt->bind_param("ssss", $username, $payment_id, $payment_status, $order_status);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// Insert each cart item into order_details
foreach ($_SESSION['cart'] as $product_id => $qty) {
    // Fetch product info
    $productQuery = $con->prepare("SELECT name, price FROM products WHERE id = ?");
    $productQuery->bind_param("i", $product_id);
    $productQuery->execute();
    $productResult = $productQuery->get_result();
    if ($productResult->num_rows > 0) {
        $product = $productResult->fetch_assoc();
        $stmt2 = $con->prepare("
            INSERT INTO order_details (order_id, product_name, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        $stmt2->bind_param("isid", $order_id, $product['name'], $qty, $product['price']);
        $stmt2->execute();
        $stmt2->close();
    }
    $productQuery->close();
}

// Clear cart after order is placed
unset($_SESSION['cart']);

// Redirect to my_orders page with success message
echo "<script>
alert('Payment Successful! Your order has been placed.');
window.location='my_orders.php';
</script>";
