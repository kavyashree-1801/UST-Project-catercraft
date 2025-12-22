<?php
session_start();
include 'config.php';

$name = $_SESSION['name'] ?? "Guest";
$role = $_SESSION['role'] ?? "guest";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Privacy Policy - CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { padding-top: 70px; }
.navbar-brand { font-weight: bold; font-size: 24px; }
.navbar-text.orange { color: orange; font-weight: bold; margin-right: 15px; }
footer { background: #212529; color: white; padding: 40px 0; }
footer a { color: orange; text-decoration: none; }
footer a:hover { text-decoration: underline; }
</style>
</head>
<body>

<!-- NAVBAR -->
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
        <li class="nav-item"><a class="nav-link" href="my_orders.php">My orders</a></li>
      </ul>
      <span class="navbar-text orange">Hello, <?= htmlspecialchars($name) ?></span>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- PAGE CONTENT -->
<div class="container mt-5">
    <h1 class="mb-4">Privacy Policy</h1>
    <p>At <strong>CaterCraft</strong>, we value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, and safeguard your data.</p>

    <h4>1. Information We Collect</h4>
    <p>We may collect the following information:</p>
    <ul>
        <li>Personal details (name, email, contact number) provided during registration or orders.</li>
        <li>Order history and preferences.</li>
        <li>Feedback and contact messages.</li>
    </ul>

    <h4>2. How We Use Your Information</h4>
    <ul>
        <li>To process and manage your orders.</li>
        <li>To communicate important updates and promotions.</li>
        <li>To improve our services and website experience.</li>
    </ul>

    <h4>3. Data Protection</h4>
    <p>We implement industry-standard security measures to protect your information from unauthorized access, alteration, or disclosure.</p>

    <h4>4. Sharing Your Information</h4>
    <p>We do not sell, trade, or rent users' personal information. Information may be shared with trusted third-party service providers for processing orders.</p>

    <h4>5. Cookies</h4>
    <p>Our website may use cookies to enhance your browsing experience and track usage patterns.</p>

    <h4>6. Your Rights</h4>
    <p>You can review, update, or delete your personal information by contacting us at <a href="contact.php">Contact Us</a>.</p>

    <p>By using our website, you agree to the terms outlined in this Privacy Policy.</p>
</div>

<!-- FOOTER -->
<footer class="mt-5">
    <div class="container text-center">
        <p>&copy; <?= date('Y') ?> CaterCraft. All Rights Reserved.</p>
        <p><a href="contact.php">Contact Us</a> | <a href="privacy.php">Privacy Policy</a></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
