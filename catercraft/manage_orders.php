<?php
session_start();
include 'config.php';

// Only admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$errors = [];
$success = "";

/* ----------------------------------------
   UPDATE ORDER STATUS
---------------------------------------- */
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $stmt = $con->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        $success = "Order status updated successfully.";
    } else {
        $errors[] = "Failed to update status.";
    }
}

/* ----------------------------------------
   DELETE ORDER
---------------------------------------- */
if (isset($_POST['delete_order'])) {
    $order_id = intval($_POST['order_id']);

    // delete order details first
    $con->query("DELETE FROM order_details WHERE order_id=$order_id");

    // delete main order
    if ($con->query("DELETE FROM orders WHERE id=$order_id")) {
        $success = "Order deleted successfully.";
    } else {
        $errors[] = "Failed to delete order.";
    }
}

/* ----------------------------------------
   FETCH ALL ORDERS
---------------------------------------- */
$orderQuery = $con->query("SELECT * FROM orders ORDER BY order_date DESC");

$orders = [];

if ($orderQuery && $orderQuery->num_rows > 0) {
    while ($o = $orderQuery->fetch_assoc()) {

        $oid = $o['id'];
        $items = [];
        $total = 0;

        // fetch items for each order
        $itemsQuery = $con->query("SELECT * FROM order_details WHERE order_id=$oid");

        if ($itemsQuery && $itemsQuery->num_rows > 0) {
            while ($it = $itemsQuery->fetch_assoc()) {
                $subtotal = $it['quantity'] * $it['price'];
                $total += $subtotal;

                $items[] = [
                    'product_name' => $it['product_name'],
                    'quantity' => $it['quantity'],
                    'price' => $it['price'],
                    'subtotal' => $subtotal
                ];
            }
        }

        $orders[$oid] = [
            'username' => $o['username'],
            'order_date' => $o['order_date'],
            'status' => $o['status'],
            'items' => $items,
            'total' => $total
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Orders - CaterCraft Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: url('https://media.istockphoto.com/id/1198623553/photo/restaurant-chef-serving-food.jpg?s=612x612&w=0&k=20&c=PQzI3_v0zDhm6N2zV0EHT6r4OQx6uF4mCM3A8IhSKdQ=') 
    no-repeat center center/cover;
    min-height: 100vh;
}
.table-container {
    margin-top: 100px;
    background: rgba(255,255,255,0.93);
    padding: 25px;
    border-radius: 15px;
}

/* ⭐ Navbar styling */
.navbar .nav-link,
.navbar-brand {
    font-weight: bold;
}

/* Space between navbar links */
.navbar-nav .nav-item {
    margin-right: 20px; /* Adjust spacing */
}

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

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand">CaterCraft</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_contact.php">Manage Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_feedback.php">Manage Feedback</a></li>
        <li class="nav-item"><a class="nav-link active" href="manage_orders.php">Manage Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_products.php">Manage Products</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
      </ul>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container table-container">

    <h2 class="text-center mb-4">All Orders</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $e) echo "<div>$e</div>"; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($orders)): ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Username</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Items</th>
                <th>Total</th>
                <th width="180">Actions</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($orders as $oid => $o): ?>
            <tr>
                <td><?= $oid ?></td>
                <td><?= htmlspecialchars($o['username']) ?></td>
                <td><?= date("d-m-Y H:i", strtotime($o['order_date'])) ?></td>
                <td>
                    <span class="status-badge status-<?= $o['status'] ?>">
                        <?= $o['status'] ?>
                    </span>
                </td>

                <td>
                    <?php foreach ($o['items'] as $i): ?>
                        <?= htmlspecialchars($i['product_name']) ?> (x<?= $i['quantity'] ?>) <br>
                    <?php endforeach; ?>
                </td>

                <td><strong>₹<?= number_format($o['total'], 2) ?></strong></td>

                <td>

                    <!-- Update Status -->
                    <form method="POST" class="mb-2">
                        <input type="hidden" name="order_id" value="<?= $oid ?>">

                        <select name="status" class="form-select form-select-sm mb-1">
                            <option value="Pending"     <?= $o['status']=="Pending"?"selected":"" ?>>Pending</option>
                            <option value="Processing" <?= $o['status']=="Processing"?"selected":"" ?>>Processing</option>
                            <option value="Completed"  <?= $o['status']=="Completed"?"selected":"" ?>>Completed</option>
                            <option value="Cancelled"  <?= $o['status']=="Cancelled"?"selected":"" ?>>Cancelled</option>
                        </select>

                        <button name="update_status" class="btn btn-primary btn-sm w-100">Update</button>
                    </form>

                    <!-- Delete Order -->
                    <form method="POST" onsubmit="return confirm('Delete this order? This cannot be undone!');">
                        <input type="hidden" name="order_id" value="<?= $oid ?>">
                        <button name="delete_order" class="btn btn-danger btn-sm w-100">
                            Delete
                        </button>
                    </form>

                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

    <?php else: ?>

        <p class="text-center text-warning fs-4">No orders found.</p>

    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
