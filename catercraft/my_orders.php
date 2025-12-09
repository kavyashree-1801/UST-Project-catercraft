<?php
session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['name']; // Logged-in user's name
$role = $_SESSION['role'] ?? 'user';

// Fetch user's orders
$orderQuery = $con->prepare("SELECT * FROM orders WHERE username=? ORDER BY order_date DESC");
$orderQuery->bind_param("s", $username);
$orderQuery->execute();
$result = $orderQuery->get_result();

$orders = [];
while ($o = $result->fetch_assoc()) {
    $oid = $o['id'];
    $items = [];
    $total = 0;

    // Fetch items for each order
    $itemsQuery = $con->prepare("SELECT * FROM order_details WHERE order_id=?");
    $itemsQuery->bind_param("i", $oid);
    $itemsQuery->execute();
    $itemsResult = $itemsQuery->get_result();

    while ($it = $itemsResult->fetch_assoc()) {
        $subtotal = $it['quantity'] * $it['price'];
        $total += $subtotal;
        $items[] = [
            'product_name' => $it['product_name'],
            'quantity' => $it['quantity'],
            'price' => $it['price'],
            'subtotal' => $subtotal
        ];
    }
    $itemsQuery->close();

    $orders[] = [
        'order_date' => $o['order_date'],
        'status' => $o['status'],
        'items' => $items,
        'total' => $total
    ];
}
$orderQuery->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders - CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { margin-top: 70px; font-family: Arial, sans-serif; background: #f8f8f8; }
.navbar-nav .nav-link { font-weight: bold; margin-right: 15px; }
.navbar-nav .nav-item:last-child .nav-link { margin-right: 0; }
.table-container { margin-top: 20px; background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

/* Status badges */
.status-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-weight: bold;
    color: #fff;
}
.status-Pending { background:#ffc107; }
.status-Processing { background:#0dcaf0; }
.status-Completed { background:#198754; }
.status-Cancelled { background:#dc3545; }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="homepage.php">CaterCraft</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Menu</a></li>
        <li class="nav-item"><a class="nav-link active" href="my_orders.php">My Orders</a></li>
      </ul>
      <span class="navbar-text text-warning me-3">Hello, <?= htmlspecialchars($username) ?></span>
      <?php if($role !== 'admin'): ?>
        <a href="profile.php" class="btn btn-info btn-sm me-2">Profile</a>
      <?php endif; ?>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container table-container">
    <h2 class="text-center mb-4">My Orders</h2>

    <?php if (!empty($orders)): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th> <!-- Serial number -->
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Items</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            <?php $sn = 1; ?>
            <?php foreach($orders as $o): ?>
                <tr>
                    <td><?= $sn++ ?></td>
                    <td><?= date("d-m-Y H:i", strtotime($o['order_date'])) ?></td>
                    <td>
                        <span class="status-badge status-<?= $o['status'] ?>"><?= $o['status'] ?></span>
                    </td>
                    <td>
                        <?php foreach($o['items'] as $item): ?>
                            <?= htmlspecialchars($item['product_name']) ?> (x<?= $item['quantity'] ?>)<br>
                        <?php endforeach; ?>
                    </td>
                    <td><strong>₹<?= number_format($o['total'],2) ?></strong></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-warning fs-4">You have not placed any orders yet.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
