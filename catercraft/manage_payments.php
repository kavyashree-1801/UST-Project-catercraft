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

/* ----------------------------------------
   UPDATE PAYMENT STATUS OR ORDER STATUS
---------------------------------------- */
if (isset($_POST['update'])) {
    $order_id = intval($_POST['order_id']);
    $new_payment_status = $_POST['payment_status'];
    $new_order_status = $_POST['order_status'];

    $stmt = $con->prepare("UPDATE orders SET payment_status=?, order_status=? WHERE id=?");
    $stmt->bind_param("ssi", $new_payment_status, $new_order_status, $order_id);

    if ($stmt->execute()) {
        $success = "Order updated successfully!";
    } else {
        $error = "Failed to update order: " . $stmt->error;
    }
}

/* ----------------------------------------
   DELETE ORDER
---------------------------------------- */
if (isset($_GET['delete'])) {
    $order_id = intval($_GET['delete']);
    // Delete order details first due to FK constraints
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

/* ----------------------------------------
   FETCH ORDERS
---------------------------------------- */
$ordersQuery = $con->query("
    SELECT id, username, payment_id, payment_status, order_status, order_date, created_at
    FROM orders
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Payments - CaterCraft Admin</title>
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

/* Payment badges */
.status-badge { padding: 4px 10px; border-radius: 12px; font-weight: bold; color: #fff; font-size:0.9rem; }
.status-Paid { background:#198754; }         
.status-COD { background:#0d6efd; }         
.status-Pending { background:#ffc107; } 

/* Order status badges */
.status-Pending { background:#ffc107; }
.status-Processing { background:#0dcaf0; }
.status-Completed { background:#198754; }
.status-Cancelled { background:#dc3545; }
.status-Default { background:#6c757d; }

/* Navbar styling */
.navbar-brand,
.navbar .nav-link,
.navbar-text {
    font-weight: 700; /* bold */
}

/* Add space between navbar links */
.navbar-nav .nav-item {
    margin-right: 20px; 
}
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
        <li class="nav-item"><a class="nav-link" href="manage_orders.php">Manage Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_products.php">Manage Products</a></li>
        <li class="nav-item"><a class="nav-link active" href="manage_payments.php">Manage Payments</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
      </ul>
      <span class="navbar-text text-warning me-3">Hello, <?= htmlspecialchars($_SESSION['name']) ?></span>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container table-container">
<h2 class="text-center mb-4">Manage Payments</h2>

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
    <th>Payment ID</th>
    <th>Payment Status</th>
    <th>Order Status</th>
    <th>Order Date</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php $sn=1; while($order = $ordersQuery->fetch_assoc()): 

    // Determine Payment ID display
    $display_payment_id = ($order['payment_status']=='Paid' && $order['payment_id']) 
                          ? htmlspecialchars($order['payment_id']) 
                          : ($order['payment_status']=='Paid' ? 'Cash Paid' : '');

    // Payment badge
    if($order['payment_status'] === 'Paid'){
        $paymentBadge = 'status-Paid';
    } elseif($order['payment_status'] === 'Pending'){
        $paymentBadge = 'status-COD';
    } else {
        $paymentBadge = 'status-Default';
    }

    // Order badge
    $orderBadge = in_array($order['order_status'], ['Pending','Processing','Completed','Cancelled']) 
                  ? 'status-' . $order['order_status'] 
                  : 'status-Default';
?>
<tr>
    <td><?= $sn++ ?></td>
    <td><?= htmlspecialchars($order['username']) ?></td>
    <td><?= $display_payment_id ?></td>
    <td><span class="status-badge <?= $paymentBadge ?>"><?= $order['payment_status'] ?></span></td>
    <td><span class="status-badge <?= $orderBadge ?>"><?= $order['order_status'] ?></span></td>
    <td><?= date("d-m-Y H:i", strtotime($order['order_date'])) ?></td>
    <td>
        <form method="POST" class="d-flex gap-1 mb-1">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <select name="payment_status" class="form-select form-select-sm">
                <option value="Paid" <?= $order['payment_status']=='Paid'?'selected':'' ?>>Paid</option>
                <option value="Pending" <?= $order['payment_status']=='Pending'?'selected':'' ?>>Pending</option>
            </select>
            <select name="order_status" class="form-select form-select-sm">
                <option value="Pending" <?= $order['order_status']=='Pending'?'selected':'' ?>>Pending</option>
                <option value="Processing" <?= $order['order_status']=='Processing'?'selected':'' ?>>Processing</option>
                <option value="Completed" <?= $order['order_status']=='Completed'?'selected':'' ?>>Completed</option>
                <option value="Cancelled" <?= $order['order_status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
            </select>
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
  // Auto-hide success/error alerts after 3 seconds
  setTimeout(function() {
      const successAlert = document.getElementById('alert-success');
      if(successAlert) successAlert.style.display = 'none';

      const errorAlert = document.getElementById('alert-error');
      if(errorAlert) errorAlert.style.display = 'none';
  }, 3000);
</script>
</body>
</html>
