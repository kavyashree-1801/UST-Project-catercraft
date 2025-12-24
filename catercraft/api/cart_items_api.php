<?php
session_start();
include '../config.php';
header('Content-Type: application/json');

$cart_products = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $result = $con->query("SELECT * FROM products WHERE id IN ($ids)");
    while ($row = $result->fetch_assoc()) {
        $qty = $_SESSION['cart'][$row['id']];
        $subtotal = $row['price'] * $qty;
        $total += $subtotal;
        $cart_products[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'qty' => $qty,
            'subtotal' => $subtotal,
            'image' => $row['image']
        ];
    }
}

echo json_encode([
    'status' => 'success',
    'cart_count' => array_sum($_SESSION['cart'] ?? []),
    'total' => $total,
    'items' => $cart_products
]);
