<?php
session_start();
include 'config.php';

if (empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty!'); window.location='products.php';</script>";
    exit;
}

$username = $_SESSION['name'] ?? 'Guest';

// Fetch cart items
$ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
$query = $con->query("SELECT * FROM products WHERE id IN ($ids)");

$cartItems = [];
$total = 0;
foreach ($query as $row) {
    $qty = $_SESSION['cart'][$row['id']];
    $subtotal = $row['price'] * $qty;
    $total += $subtotal;
    $cartItems[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'price' => $row['price'],
        'qty' => $qty,
        'subtotal' => $subtotal
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout - CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/checkout.css">
</head>
<body>

<div class="container checkout-container mt-5">

    <div class="card shadow">
        <div class="card-header text-center">
            Checkout
        </div>
        <div class="card-body">

            <div id="message"></div>

            <!-- Cart Summary -->
            <h5>Order Summary</h5>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price (₹)</th>
                        <th>Subtotal (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($cartItems as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td><?= number_format($item['price'],2) ?></td>
                            <td><?= number_format($item['subtotal'],2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="3" class="text-end">Total</td>
                        <td id="totalAmount">₹<?= number_format($total,2) ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- Payment Method -->
            <h5>Payment Method</h5>
            <form id="checkoutForm">
                <div class="payment-method mb-3">
                    <input type="radio" name="payment_method" value="online" id="online" checked>
                    <label for="online">Online Payment</label>

                    <input type="radio" name="payment_method" value="cod" id="cod">
                    <label for="cod">Cash on Delivery (COD)</label>
                </div>

                <button type="submit" class="btn btn-success w-100" id="payButton">Proceed to Pay</button>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="js/checkout.js"></script>

<script>
// Pass PHP total amount to JS
const TOTAL_AMOUNT = <?= $total ?>;
</script>

</body>
</html>
