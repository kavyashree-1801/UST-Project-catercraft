<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['name'] ?? 'Customer';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Successful | CaterCraft</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/order_success.css">
</head>
<body>

<div class="success-wrapper">
    <div class="success-card">

        <div class="success-icon">✔</div>

        <h2>Order Placed Successfully!</h2>

        <p class="username">
            Thank you, <strong><?= htmlspecialchars($username) ?></strong>
        </p>

        <p class="message">
            Your order has been placed using <strong>Cash on Delivery</strong>.
            Our team is preparing your food.
        </p>

        <div class="actions">
            <a href="products.php" class="btn btn-primary">Continue Shopping</a>
            <a href="my_orders.php" class="btn btn-outline-success">My Orders</a>
        </div>

    </div>
</div>

<!-- Custom JS -->
<script src="js/order_success.js"></script>
</body>
</html>
