<?php
include 'config.php';
session_start();
$errors = $success = [];
$token = $_GET['token'] ?? '';

if (!$token) die("Invalid request.");

// Verify token
$stmt = $con->prepare("SELECT id, token_expiry FROM users WHERE reset_token=?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) die("Invalid or expired token.");

$user = $result->fetch_assoc();
if (strtotime($user['token_expiry']) < time()) die("Token has expired.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if (!$password || !$confirm) $errors[] = "All fields are required.";
    elseif ($password !== $confirm) $errors[] = "Passwords do not match.";
    else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt2 = $con->prepare("UPDATE users SET password=?, reset_token=NULL, token_expiry=NULL WHERE id=?");
        $stmt2->bind_param("si", $hash, $user['id']);
        if ($stmt2->execute()) {
            $success[] = "Password reset successfully.";
            $show_alert = true; // flag to trigger JS alert
        } else $errors[] = "Failed to reset password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Reset Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.eye { position: absolute; right: 15px; top: 35%; cursor: pointer; }
.position-relative { position: relative; }
</style>
</head>
<body>
<div class="container mt-5" style="max-width:500px;">
<h3 class="mb-3">Reset Password</h3>

<?php foreach ($errors as $e) echo "<div class='alert alert-danger'>$e</div>"; ?>
<?php foreach ($success as $s) echo "<div class='alert alert-success'>$s</div>"; ?>

<?php if(empty($success) || !isset($show_alert)): ?>
<form method="POST">
    <div class="mb-3 position-relative">
        <label>New Password</label>
        <input type="password" id="password" name="password" class="form-control" required>
        <span class="eye" onclick="toggle('password')">👁️</span>
    </div>
    <div class="mb-3 position-relative">
        <label>Confirm Password</label>
        <input type="password" id="confirm" name="confirm" class="form-control" required>
        <span class="eye" onclick="toggle('confirm')">👁️</span>
    </div>
    <button class="btn btn-primary w-100">Reset Password</button>
</form>
<?php endif; ?>

</div>

<script>
function toggle(id) {
    let x = document.getElementById(id);
    x.type = x.type === "password" ? "text" : "password";
}

// Trigger alert if password changed successfully
<?php if(isset($show_alert) && $show_alert): ?>
alert("Password changed successfully!");
window.location.href = "login.php"; // redirect to login after alert
<?php endif; ?>
</script>

</body>
</html>
