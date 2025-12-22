<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password | CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/forgot_password.css">
</head>
<body>

<div class="forgot-container d-flex align-items-center justify-content-center">
    <div class="forgot-card p-4 shadow rounded">
        <h3 class="text-center mb-4">Forgot Password</h3>

        <div id="message"></div>

        <form id="forgotForm">
            <div class="mb-3">
                <label for="email" class="form-label">Enter your registered email</label>
                <input type="email" id="email" class="form-control" placeholder="example@mail.com" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
        </form>

        <p class="mt-3 text-center">
            Remembered? <a href="login.php">Login here</a>
        </p>
    </div>
</div>

<script src="js/forgot_password.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
