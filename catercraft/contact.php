<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$name = $_SESSION['name'] ?? "";
$email = $_SESSION['email'] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact Us - CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/contact.css">
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
        <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="my_orders.php">My Orders</a></li>
      </ul>
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-2"><span class="nav-link text-warning">Hello, <?= htmlspecialchars($name) ?></span></li>
        <li class="nav-item me-2"><a href="profile.php" class="btn btn-info btn-sm">Profile</a></li>
        <a href="logout.php" class="btn btn-danger btn-sm logout-btn">Logout</a>
      </ul>
    </div>
  </div>
</nav>

<!-- Contact Form -->
<div class="overlay">
    <div class="container">
        <div class="contact-card">
            <h3 class="text-center mb-3">Contact Us</h3>
            <p class="text-muted text-center">We’d love to hear from you!</p>

            <div id="alertMsg"></div>

            <form id="contactForm">
                <div class="mb-3">
                    <label class="form-label">Your Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Your Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Send Message</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/contact.js"></script>
</body>
</html>
