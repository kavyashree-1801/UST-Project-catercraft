<?php
session_start();
include 'config.php';

/* REDIRECT IF NOT LOGGED IN */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$success = "";
$error = "";

/* FORM SUBMIT */
if (isset($_POST['submit_feedback'])) {

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $rating = intval($_POST['rating']);
    $feedback = mysqli_real_escape_string($con, $_POST['feedback']);

    if (!empty($name) && !empty($email) && !empty($feedback) && $rating > 0) {

        $stmt = $con->prepare("INSERT INTO feedback (name, email, rating, feedback) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $name, $email, $rating, $feedback);

        if ($stmt->execute()) {
            $success = "Thank you for your feedback!";
        } else {
            $error = "Something went wrong. Please try again.";
        }

    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Feedback - CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    margin: 0;
    padding: 0;
    background-image: url('https://images.squarespace-cdn.com/content/v1/6457b68da3147a4bfdb28026/25e14b23-a8d5-4d6a-b160-6db45a6eab90/Profession_Catering_Services_Homepage_Photo.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    font-family: Arial, sans-serif;
}

.overlay {
    background: rgba(0,0,0,0.3);
    min-height: 100vh;
    padding: 20px;
}

.feedback-card {
    max-width: 650px;
    margin: 40px auto;
    padding: 25px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* ⭐ STAR RATING ⭐ */
.star-rating input {
    display: none;
}
.star-rating label {
    font-size: 28px;
    color: #ddd;
    cursor: pointer;
}
.star-rating input:checked ~ label {
    color: gold;
}
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: gold;
}

/* ⭐ NAVBAR BOLD + SPACING ⭐ */
.navbar-nav .nav-link {
    font-weight: bold;
    margin: 0 14px;
    font-size: 16px;
}

.navbar-brand {
    font-weight: bold;
}

.navbar-nav .nav-link:hover {
    color: #fff !important;
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">CaterCraft</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
          <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link active" href="feedback.php">Feedback</a></li>
          <li class="nav-item"><a class="nav-link" href="products.php">Menu</a></li>
          <li class="nav-item"><a class="nav-link" href="my_orders.php">My Orders</a></li>
      </ul>

      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-2">
            <span class="nav-link text-warning">Hello, <?= htmlspecialchars($_SESSION['name']) ?></span>
        </li>

        <li class="nav-item me-2">
            <a href="profile.php" class="btn btn-info btn-sm">Profile</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-danger" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar -->

<div class="overlay" style="padding-top: 80px;">
<div class="container">
    <div class="feedback-card">

        <h3 class="text-center mb-3">Give Us Your Feedback</h3>
        <p class="text-muted text-center">Your feedback helps us improve our services!</p>

        <?php if(!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <?php if(!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Your Name</label>
                <input type="text" name="name" class="form-control"
                       value="<?= htmlspecialchars($_SESSION['name'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Your Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required>
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

            <button type="submit" name="submit_feedback" class="btn btn-primary w-100">
                Submit Feedback
            </button>
        </form>

    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
