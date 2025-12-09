<?php
session_start();
include "config.php";

/* IF NOT LOGGED IN REDIRECT */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = "";
$error = "";

/* FETCH USER DATA */
$stmt = $con->prepare("SELECT name, email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* UPDATE PROFILE */
if (isset($_POST['update_profile'])) {

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $address = mysqli_real_escape_string($con, $_POST['address']);

    $update = $con->prepare("UPDATE users SET name=?, phone=?, address=? WHERE id=?");
    $update->bind_param("sssi", $name, $phone, $address, $user_id);

    if ($update->execute()) {
        $_SESSION['name'] = $name; 
        $success = "Profile updated successfully!";
    } else {
        $error = "Error updating profile.";
    }
}

/* UPDATE PASSWORD */
if (isset($_POST['change_password'])) {

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Fetch stored password
    $check = $con->prepare("SELECT password FROM users WHERE id=?");
    $check->bind_param("i", $user_id);
    $check->execute();
    $row = $check->get_result()->fetch_assoc();

    // Verify current password
    if (!password_verify($current, $row['password'])) {
        $error = "Current password is incorrect!";
    }
    else if ($new !== $confirm) {
        $error = "Passwords do not match!";
    } 
    else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $updatePass = $con->prepare("UPDATE users SET password=? WHERE id=?");
        $updatePass->bind_param("si", $hashed, $user_id);

        if ($updatePass->execute()) {
            $success = "Password changed successfully!";
        } else {
            $error = "Error changing password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Profile - CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    margin: 0;
    padding: 0;
    background-image: url('https://img.freepik.com/free-photo/italian-food-concept-with-pasta-space-left_23-2147686519.jpg?w=740');
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

.profile-card {
    max-width: 750px;
    margin: 40px auto;
    padding: 25px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.1);
}

/* NAVBAR STYLING */
.navbar-nav .nav-link {
    font-weight: 700 !important;  /* reduced bold */
    padding: 0 18px !important;
    font-size: 16px;
    text-transform: none !important; 
}

.navbar-nav .nav-link:hover {
    color: #ddd !important; /* no orange hover */
}

/* Eye icon inside input */
.input-with-icon {
    position: relative;
}

.input-with-icon input {
    padding-right: 45px !important;
}

.toggle-eye {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 20px;
    color: #6c757d;
    user-select: none;
}

.toggle-eye:hover {
    color: #000;
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">CaterCraft</a>
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
        <li class="nav-item me-3">
            <span class="nav-link text-warning fw-bold">Hello, <?= htmlspecialchars($_SESSION['name']) ?></span>
        </li>
        <li class="nav-item me-2">
            <a href="profile.php" class="btn btn-info btn-sm disabled">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-danger fw-bold" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="overlay" style="padding-top: 80px;">
<div class="profile-card">

    <h3 class="text-center mb-4">My Profile</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- UPDATE PROFILE FORM -->
    <form method="POST">
        <h5>Update Details</h5>
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
            <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($user['address']) ?></textarea>
        </div>

        <button type="submit" name="update_profile" class="btn btn-primary w-100">Update Profile</button>
    </form>

    <hr class="my-4">

    <!-- CHANGE PASSWORD FORM -->
    <form method="POST">
        <h5>Change Password</h5>

        <div class="mb-3">
            <label class="form-label">Current Password</label>
            <div class="input-with-icon">
                <input type="password" name="current_password" id="current_password" class="form-control" required>
                <span class="toggle-eye" onclick="togglePassword('current_password')">👁️</span>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password</label>
            <div class="input-with-icon">
                <input type="password" name="new_password" id="new_password" class="form-control" required>
                <span class="toggle-eye" onclick="togglePassword('new_password')">👁️</span>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <div class="input-with-icon">
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                <span class="toggle-eye" onclick="togglePassword('confirm_password')">👁️</span>
            </div>
        </div>

        <button type="submit" name="change_password" class="btn btn-warning w-100">Change Password</button>
    </form>

</div>
</div>

<script>
function togglePassword(id) {
    let field = document.getElementById(id);
    field.type = field.type === "password" ? "text" : "password";
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
