<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT name, email, phone, address FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Profile - CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/profile.css">
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
        <li class="nav-item"><a class="nav-link" href="my_orders.php">My Orders</a></li>
      </ul>
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-2"><span class="nav-link text-warning">Hello, <?= htmlspecialchars($user['name']) ?></span></li>
        <li class="nav-item me-2"><a href="profile.php" class="btn btn-info btn-sm">Profile</a></li>
        <a href="logout.php" class="btn btn-danger btn-sm logout-btn">Logout</a>
      </ul>
    </div>
  </div>
</nav>

<div class="overlay pt-5">
<div class="profile-card">

    <h3 class="text-center mb-4">My Profile</h3>

    <div id="message"></div>

    <!-- UPDATE PROFILE FORM -->
    <form id="profileForm">
        <h5>Update Details</h5>
        <input type="hidden" name="action" value="update_profile">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email (Read Only)</label>
            <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control"><?= htmlspecialchars($user['address']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Update Profile</button>
    </form>

    <hr>

    <!-- CHANGE PASSWORD FORM -->
    <form id="passwordForm">
        <h5>Change Password</h5>
        <input type="hidden" name="action" value="change_password">
        <div class="mb-3 input-with-icon">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
            <span class="toggle-eye" onclick="togglePassword('current_password')">👁️</span>
        </div>
        <div class="mb-3 input-with-icon">
            <label class="form-label">New Password</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
            <span class="toggle-eye" onclick="togglePassword('new_password')">👁️</span>
        </div>
        <div class="mb-3 input-with-icon">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            <span class="toggle-eye" onclick="togglePassword('confirm_password')">👁️</span>
        </div>
        <button type="submit" class="btn btn-warning w-100">Change Password</button>
    </form>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/profile.js"></script>
</body>
</html>
