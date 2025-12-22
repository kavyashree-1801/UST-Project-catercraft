<?php
session_start();
include 'config.php';

/* REDIRECT IF NOT LOGGED IN */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Feedback - CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/feedback.css">
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
        <li class="nav-item"><a class="nav-link " href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link active" href="feedback.php">Feedback</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="my_orders.php">My Orders</a></li>
      </ul>
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-2"><span class="nav-link text-warning">Hello, <?= htmlspecialchars($_SESSION['name']) ?></span></li>
        <li class="nav-item me-2"><a href="profile.php" class="btn btn-info btn-sm">Profile</a></li>
        <a href="logout.php" class="btn btn-danger btn-sm logout-btn">Logout</a>
      </ul>
    </div>
  </div>
</nav>

<div class="overlay" style="padding-top: 80px;">
    <div class="container">
        <div class="feedback-card">
            <h3 class="text-center mb-3">Give Us Your Feedback</h3>
            <p class="text-muted text-center">Your feedback helps us improve our services!</p>

            <div id="alertMsg"></div>

            <form id="feedbackForm">
                <div class="mb-3">
                    <label class="form-label">Your Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_SESSION['name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Your Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Rating</label>
                    <div class="star-rating d-flex flex-row-reverse justify-content-end">
                        <input type="radio" name="rating" id="star5" value="5"><label for="star5">★</label>
                        <input type="radio" name="rating" id="star4" value="4"><label for="star4">★</label>
                        <input type="radio" name="rating" id="star3" value="3"><label for="star3">★</label>
                        <input type="radio" name="rating" id="star2" value="2"><label for="star2">★</label>
                        <input type="radio" name="rating" id="star1" value="1"><label for="star1">★</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Your Feedback</label>
                    <textarea name="feedback" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Submit Feedback</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/feedback.js"></script>
</body>
</html>
