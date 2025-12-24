<?php
session_start();
include 'config.php';

/* =========================
   CHECK LOGIN
========================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* =========================
   CHECK CART
========================= */
if (empty($_SESSION['cart'])) {
    header("Location: products.php");
    exit;
}

$username = $_SESSION['name']; // username
$payment_method = 'COD';
$payment_status = 'Pending';
$order_status   = 'Placed';
$order_date     = date('Y-m-d');

/* =========================
   FETCH CART PRODUCTS
========================= */
$cart = $_SESSION['cart'];
$ids = implode(',', array_map('intval', array_keys($cart)));

$result = $con->query("SELECT name, price FROM products WHERE id IN ($ids)");

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[$row['name']] = $row['price'];
}

/* =========================
   CREATE ORDER
========================= */
$con->begin_transaction();

try {

    // Insert into orders table
    $stmt = $con->prepare("
        INSERT INTO orders 
        (username, order_date, order_status, payment_method, payment_status, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param(
        "sssss",
        $username,
        $order_date,
        $order_status,
        $payment_method,
        $payment_status
    );

    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    /* =========================
       INSERT ORDER DETAILS
    ========================= */
    $stmt = $con->prepare("
        INSERT INTO order_details 
        (order_id, product_name, quantity, price)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($cart as $product_id => $qty) {

        // Get product name & price
        $prodRes = $con->query("SELECT name, price FROM products WHERE id = $product_id");
        if (!$prodRes || $prodRes->num_rows == 0) continue;

        $product = $prodRes->fetch_assoc();

        $product_name = $product['name'];
        $price = $product['price'];

        $stmt->bind_param(
            "isid",
            $order_id,
            $product_name,
            $qty,
            $price
        );
        $stmt->execute();
    }

    $stmt->close();

    /* =========================
       CLEAR CART
    ========================= */
    unset($_SESSION['cart']);

    $con->commit();

    /* =========================
       REDIRECT
    ========================= */
    header("Location: order_success.php");
    exit;

} catch (Exception $e) {

    $con->rollback();
    echo "Order failed. Please try again.";
}
