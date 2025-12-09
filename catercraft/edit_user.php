<?php
session_start();
include 'config.php';

// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Get user ID from query string
$user_id = intval($_GET['id']);

// Initialize messages
$success = "";
$error = "";

// Fetch user details
$stmt = $con->prepare("SELECT name,email,phone,address FROM users WHERE id=? AND role='user'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<script>alert('User not found'); window.location='manage_users.php';</script>";
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if (isset($_POST['update_user'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if ($name === "" || $email === "") {
        $error = "Name and Email are required.";
    } else {
        $stmt = $con->prepare("UPDATE users SET name=?, email=?, phone=?, address=? WHERE id=? AND role='user'");
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);
        if ($stmt->execute()) {
            $success = "User updated successfully!";
            // Refresh user info
            $user['name'] = $name;
            $user['email'] = $email;
            $user['phone'] = $phone;
            $user['address'] = $address;
        } else {
            $error = "Error updating user: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User - CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: Arial, sans-serif; margin-top:70px; background:#f8f8f8; }
.container { max-width: 600px; margin-top:50px; }
</style>
</head>
<body>

<div class="container">
    <h2 class="mb-4">Edit User</h2>

    <?php if($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
        </div>
        <button type="submit" name="update_user" class="btn btn-success">Update User</button>
        <a href="manage_users.php" class="btn btn-secondary">Back</a>
    </form>
</div>

</body>
</html>
