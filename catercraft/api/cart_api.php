<?php
session_start();
include '../config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$product_id = intval($_POST['product_id'] ?? 0);

if ($product_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Product ID missing']);
    exit;
}

// Add to cart
if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = 1;
} else {
    $_SESSION['cart'][$product_id] += 1;
}

// Return success + cart count
echo json_encode([
    'status' => 'success',
    'message' => 'Product added to cart',
    'cart_count' => array_sum($_SESSION['cart'])
]);
