<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['name'] ?? 'Guest';
$role = $_SESSION['role'] ?? 'user';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders - CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/orders.css">
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
        <li class="nav-item"><a class="nav-link  active" href="my_orders.php">My Orders</a></li>
      </ul>
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-2"><span class="nav-link text-warning">Hello, <?= htmlspecialchars($username) ?></span></li>
        <li class="nav-item me-2"><a href="profile.php" class="btn btn-info btn-sm">Profile</a></li>
        <a href="logout.php" class="btn btn-danger btn-sm logout-btn">Logout</a>
      </ul>
    </div>
  </div>
</nav>

<div class="container table-container mt-5 pt-4">
    <h2 class="text-center mb-4">My Orders</h2>
    <div id="ordersContainer">Loading orders...</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/orders.js"></script>
</body>
</html>
