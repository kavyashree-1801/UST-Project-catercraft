<?php
include 'config.php';
session_start();
$errors = $success = [];
$reset_link = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!$email) $errors[] = "Please enter your email.";
    else {
        $stmt = $con->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $token = bin2hex(random_bytes(50));
            $expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));
            $stmt2 = $con->prepare("UPDATE users SET reset_token=?, token_expiry=? WHERE email=?");
            $stmt2->bind_param("sss", $token, $expiry, $email);
            $stmt2->execute();

            $reset_link = "http://localhost/catercraft/reset_password.php?token=$token";
            $success[] = "Password reset link generated successfully!";
        } else {
            $errors[] = "Email not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Forgot Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.link-box {
    background: #f1f1f1;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    word-break: break-all;
    font-weight: bold;
}
</style>
</head>
<body>
<div class="container mt-5" style="max-width:500px;">
<h3 class="mb-3">Forgot Password</h3>

<?php foreach ($errors as $e) echo "<div class='alert alert-danger'>$e</div>"; ?>
<?php foreach ($success as $s) echo "<div class='alert alert-success'>$s</div>"; ?>

<?php if($reset_link): ?>
<div class="link-box">
    Reset Link: <a href="<?php echo $reset_link; ?>"><?php echo $reset_link; ?></a>
</div>
<p class="mt-3 text-center"><a href="login.php">Back to Login</a></p>
<?php else: ?>
<form method="POST">
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <button class="btn btn-primary w-100">Generate Reset Link</button>
</form>
<p class="mt-3 text-center"><a href="login.php">Back to Login</a></p>
<?php endif; ?>

</div>
</body>
</html>
