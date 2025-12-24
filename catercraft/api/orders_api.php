<?php
session_start();
header('Content-Type: application/json');
include '../config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$username = $_SESSION['name'] ?? 'Guest';

/* =========================
   FETCH ORDERS
========================= */
$orderQuery = $con->prepare("
    SELECT id, payment_status, order_status,
           COALESCE(order_date, created_at) AS order_date
    FROM orders
    WHERE username = ?
    ORDER BY id DESC
");
$orderQuery->bind_param("s", $username);
$orderQuery->execute();
$result = $orderQuery->get_result();

$orders = [];

while ($o = $result->fetch_assoc()) {

    $oid = $o['id'];
    $items = [];
    $total = 0;

    /* =========================
       FETCH ORDER ITEMS
    ========================= */
    $itemsQuery = $con->prepare("
        SELECT product_name, quantity, price
        FROM order_details
        WHERE order_id = ?
    ");
    $itemsQuery->bind_param("i", $oid);
    $itemsQuery->execute();
    $itemsResult = $itemsQuery->get_result();

    while ($it = $itemsResult->fetch_assoc()) {
        $subtotal = $it['quantity'] * $it['price'];
        $total += $subtotal;

        $items[] = [
            'product_name' => $it['product_name'],
            'quantity' => (int)$it['quantity'],
            'price' => (float)$it['price'],
            'subtotal' => $subtotal
        ];
    }
    $itemsQuery->close();

    $orders[] = [
        'order_date' => $o['order_date'],
        'payment_status' => $o['payment_status'] ?? 'Pending',
        'order_status' => $o['order_status'] ?? 'Pending',
        'items' => $items,
        'total' => $total
    ];
}

$orderQuery->close();

echo json_encode([
    'status' => 'success',
    'orders' => $orders
]);
