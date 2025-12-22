<?php
require_once "config.php"; // DB connection ($con)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons for eye toggle -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/reset_password.css">
</head>
<body>

<section class="reset-section d-flex align-items-center justify-content-center">
    <div class="card reset-card shadow">
        <div class="card-body p-4">
            <h3 class="card-title text-center mb-3">Reset Your Password</h3>

            <?php
            $token = isset($_GET['token']) ? trim($_GET['token']) : "";

            if (!$token) {
                echo '<div class="alert alert-danger">Invalid or missing reset token.</div>';
            } else {
                $stmt = $con->prepare("SELECT id, token_expiry FROM users WHERE reset_token = ?");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows === 0) {
                    echo '<div class="alert alert-danger">Invalid or expired reset link.</div>';
                } else {
                    $stmt->bind_result($user_id, $token_expiry);
                    $stmt->fetch();
                    $stmt->close();

                    $now = new DateTime();
                    $expiryDate = new DateTime($token_expiry);

                    if ($now > $expiryDate) {
                        echo '<div class="alert alert-warning">This reset link has expired. Please request a new one.</div>';
                    } else {
                        ?>

                        <div id="resetMessage"></div>

                        <form id="resetForm">
                            <input type="hidden" id="token" name="token"
                                   value="<?php echo htmlspecialchars($token); ?>">

                            <div class="mb-3 input-group">
                                <input type="password" class="form-control" id="new_password"
                                       name="new_password" placeholder="New Password" required>
                                <span class="input-group-text toggle-password" data-target="new_password">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>

                            <div class="mb-3 input-group">
                                <input type="password" class="form-control" id="confirm_password"
                                       name="confirm_password" placeholder="Confirm Password" required>
                                <span class="input-group-text toggle-password" data-target="confirm_password">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Save New Password</button>
                        </form>

                        <?php
                    }
                }
            }
            ?>

        </div>
    </div>
</section>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS for form + eye toggle -->
<script src="js/reset_password.js"></script>
</body>
</html>
