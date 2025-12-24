<?php
session_start();
include 'config.php';

// Only admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$success = "";
$error = "";

/* ----------------------------
   UPDATE ORDER STATUS + PAYMENT STATUS
---------------------------- */
if (isset($_POST['update'])) {
    $order_id = intval($_POST['order_id']);
    $new_order_status = $_POST['order_status'];
    $new_payment_status = $_POST['payment_status']; // Pending / Received

    $stmt = $con->prepare("UPDATE orders SET order_status=?, payment_status=? WHERE id=?");
    $stmt->bind_param("ssi", $new_order_status, $new_payment_status, $order_id);

    if ($stmt->execute()) {
        $success = "Order updated successfully!";
    } else {
        $error = "Failed to update order: " . $stmt->error;
    }
}

/* ----------------------------
   DELETE ORDER
---------------------------- */
if (isset($_GET['delete'])) {
    $order_id = intval($_GET['delete']);
    $stmt1 = $con->prepare("DELETE FROM order_details WHERE order_id=?");
    $stmt1->bind_param("i", $order_id);
    $stmt1->execute();
    $stmt1->close();

    $stmt2 = $con->prepare("DELETE FROM orders WHERE id=?");
    $stmt2->bind_param("i", $order_id);
    if($stmt2->execute()){
        $success = "Order deleted successfully!";
    } else {
        $error = "Failed to delete order.";
    }
    $stmt2->close();
}

/* ----------------------------
   FETCH ORDERS
---------------------------- */
$ordersQuery = $con->query("
    SELECT id, username, payment_status, order_status, order_date, created_at
    FROM orders
    ORDER BY id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Orders - CaterCraft Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
    background: #f3f3f3;
}
.table-container {
    margin-top: 100px;
    background: white;
    padding: 20px;
    border-radius: 10px;
}

/* Navbar improvements */
.navbar-nav {
    flex-wrap: nowrap;       /* prevent wrapping */
    overflow-x: auto;        /* horizontal scroll if too long */
}
.navbar-nav .nav-item {
    margin-right: 15px;      /* spacing between links */
}
.navbar-nav .nav-link {
    font-weight: 700;        /* bold */
    text-transform: none;    /* no uppercase */
}
.navbar-brand {
    font-weight: 700;
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">CaterCraft</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_contact.php">Manage Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_feedback.php">Manage Feedback</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_orders.php">Manage Orders</a></li>
        <li class="nav-item"><a class="nav-link active" href="manage_payments.php">Manage Payments</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_products.php">Manage Products</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
      </ul>
      <span class="navbar-text text-warning me-3">
          Hello, <?= htmlspecialchars($_SESSION['name']) ?>
      </span>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container table-container">
<h2 class="text-center mb-4">Manage Orders (COD Only)</h2>

<?php if($success): ?>
<div id="alert-success" class="alert alert-success"><?= $success ?></div>
<?php endif; ?>
<?php if($error): ?>
<div id="alert-error" class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if ($ordersQuery && $ordersQuery->num_rows > 0): ?>
<table class="table table-bordered table-hover">
<thead class="table-dark">
<tr>
    <th>#</th>
    <th>Username</th>
    <th>Payment Method</th>
    <th>Payment Status</th>
    <th>Order Status</th>
    <th>Order Date</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php $sn=1; while($order = $ordersQuery->fetch_assoc()): 
    $orderBadge = in_array($order['order_status'], ['Pending','Processing','Completed','Cancelled']) 
                  ? 'status-' . $order['order_status'] 
                  : 'status-Default';
?>
<tr>
    <td><?= $sn++ ?></td>
    <td><?= htmlspecialchars($order['username']) ?></td>
    <td>Cash on Delivery (COD)</td>
    <td>
        <form method="POST" class="d-flex gap-1 align-items-center mb-1">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <select name="payment_status" class="form-select form-select-sm">
                <option value="Pending" <?= $order['payment_status']=='Pending'?'selected':'' ?>>Pending</option>
                <option value="Received" <?= $order['payment_status']=='Received'?'selected':'' ?>>Received</option>
            </select>
    </td>
    <td>
            <select name="order_status" class="form-select form-select-sm">
                <option value="Pending" <?= $order['order_status']=='Pending'?'selected':'' ?>>Pending</option>
                <option value="Processing" <?= $order['order_status']=='Processing'?'selected':'' ?>>Processing</option>
                <option value="Completed" <?= $order['order_status']=='Completed'?'selected':'' ?>>Completed</option>
                <option value="Cancelled" <?= $order['order_status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
            </select>
    </td>
    <td><?= date("d-m-Y H:i", strtotime($order['order_date'])) ?></td>
    <td>
            <button type="submit" name="update" class="btn btn-sm btn-primary">Update</button>
        </form>
        <a class="btn btn-danger btn-sm" href="manage_payments.php?delete=<?= $order['id'] ?>" onclick="return confirm('Delete this order?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p class="text-center text-warning fs-4">No orders found.</p>
<?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
setTimeout(function() {
    const successAlert = document.getElementById('alert-success');
    if(successAlert) successAlert.style.display = 'none';
    const errorAlert = document.getElementById('alert-error');
    if(errorAlert) errorAlert.style.display = 'none';
}, 3000);
</script>
</body>
</html>
