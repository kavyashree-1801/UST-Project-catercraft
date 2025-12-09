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
if (isset($_POST['send_message'])) {

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $message = mysqli_real_escape_string($con, $_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {

        $stmt = $con->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            $success = "Your message has been sent successfully!";
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
<title>Contact Us - CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    margin: 0;
    padding: 0;
    background-image: url('https://choices-catering.com/wp-content/uploads/2020/06/intro-bg-1920x1080.jpg');
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

.contact-card {
    max-width: 600px;
    margin: 40px auto;
    padding: 25px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* ⭐ NAVBAR SPACING + BOLD ⭐ */
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
        <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="my_orders.php">My Orders</a></li>
      </ul>

      <ul class="navbar-nav ms-auto align-items-center">
        <!-- Username -->
        <li class="nav-item me-2">
            <span class="nav-link text-warning">Hello, <?= htmlspecialchars($_SESSION['name']) ?></span>
        </li>

        <!-- Profile Button -->
        <li class="nav-item me-2">
            <a href="profile.php" class="btn btn-info btn-sm">Profile</a>
        </li>

        <!-- Logout -->
        <li class="nav-item">
            <a class="nav-link text-danger" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar -->

<div class="overlay">
    <div class="container">
        <div class="contact-card">

            <h3 class="text-center mb-3">Contact Us</h3>
            <p class="text-muted text-center">We’d love to hear from you!</p>

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
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" name="send_message" class="btn btn-primary w-100">
                    Send Message
                </button>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
