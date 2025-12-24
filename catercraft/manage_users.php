<?php
session_start();
include 'config.php'; // your DB connection

// Redirect if not logged in or not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$name = $_SESSION['name'];

// Fetch all users
$sql = "SELECT id, name, email, role, phone, address FROM users WHERE role='user'";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users - CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f5f5f5;
    font-family: 'Segoe UI', sans-serif;
}

/* NAVBAR */
.navbar-brand {
    font-weight: bold;
    font-size: 24px;
}
.navbar-nav .nav-link {
    font-weight: bold;
    margin-right: 15px;
}
.navbar-text.orange {
    color: orange;
    font-weight: bold;
}

/* TABLE */
.table-container {
    background: #fff;
    padding: 25px;
    margin: 30px auto;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.table th, .table td {
    vertical-align: middle;
}
.action-btn {
    margin-right: 5px;
}

/* PAGE TITLE */
.page-title {
    text-align: center;
    margin: 30px 0 20px 0;
    font-size: 32px;
    font-weight: bold;
    color: #333;
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand">CaterCraft</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_contact.php">Manage Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_feedback.php">Manage Feedback</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_orders.php">Manage Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_products.php">Manage Products</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_payments.php">Manage Payments</a></li>
        <li class="nav-item"><a class="nav-link active" href="manage_users.php">Manage Users</a></li>
      </ul>

      <span class="navbar-text orange me-3">
        Hello, <?= htmlspecialchars($name) ?>
      </span>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- PAGE TITLE -->
<div class="page-title">Manage Users</div>

<!-- USERS TABLE -->
<div class="container table-container">
    <h3 class="mb-4">All Registered Users</h3>
    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if($result->num_rows > 0): ?>
            <?php while($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['phone'] ?? '-') ?></td>
                <td><?= htmlspecialchars($user['address'] ?? '-') ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary action-btn">Edit</a>
                    <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger action-btn" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" class="text-center">No users found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
