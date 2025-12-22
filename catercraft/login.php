<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CaterCraft | Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="auth-wrapper">

    <!-- LEFT IMAGE -->
    <div class="auth-left">
        <div>
            <h2>CaterCraft</h2>
            <p>Delicious moments start here</p>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="auth-right">
        <div>

            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#login">Login</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#signup">Sign Up</button>
                </li>
            </ul>

            <div class="tab-content">

                <!-- LOGIN -->
                <div class="tab-pane fade show active" id="login">
                    <form id="loginForm">

                        <select class="form-select mb-3" id="loginType">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>

                        <input type="email" id="loginEmail" class="form-control mb-3" placeholder="Email" required>

                        <div class="position-relative mb-3">
                            <input type="password" id="loginPassword" class="form-control" placeholder="Password" required
                                   data-bs-toggle="tooltip" title="Enter your password. Click 👁 to show/hide.">
                            <span class="eye" data-toggle="loginPassword">👁</span>
                        </div>

                        <div class="text-end mb-3">
                             <a href="forgot_password.php" class="small text-decoration-none">Forgot Password?</a>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Login
                        </button>
                    </form>
                </div>

                <!-- SIGNUP -->
                <div class="tab-pane fade" id="signup">
                    <form id="signupForm">

                        <input class="form-control mb-2" id="signupName" placeholder="Name" required>
                        <input class="form-control mb-2" id="signupEmail" placeholder="Email" required>
                        <input class="form-control mb-2" id="signupPhone" placeholder="Phone" required>
                        <textarea class="form-control mb-2" id="signupAddress" placeholder="Address" required></textarea>

                        <div class="position-relative mb-2">
                            <input type="password" class="form-control" id="signupPassword" placeholder="Password" required
                                   data-bs-toggle="tooltip" title="Password must be at least 6 characters. Click 👁 to show/hide.">
                            <span class="eye" data-toggle="signupPassword">👁</span>
                        </div>

                        <div class="position-relative mb-3">
                            <input type="password" class="form-control" id="signupConfirm" placeholder="Confirm Password" required
                                   data-bs-toggle="tooltip" title="Re-enter your password. Click 👁 to show/hide.">
                            <span class="eye" data-toggle="signupConfirm">👁</span>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Sign Up
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/auth.js"></script>

</body>
</html>
