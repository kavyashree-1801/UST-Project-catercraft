<?php
include 'config.php';
session_start();

$login_errors = [];
$signup_errors = [];
$signup_success = "";

// ---------------- LOGIN ----------------
if (isset($_POST['login_submit'])) {
    $login_type = $_POST['login_type'];
    $email = trim($_POST['login_email']);
    $password = $_POST['login_password'];

    if (!$email || !$password) {
        $login_errors[] = "All fields are required.";
    } else {
        if ($login_type == "admin") {
            $stmt = $con->prepare("SELECT id,name,password,role FROM users WHERE email=? AND role='admin'");
        } else {
            $stmt = $con->prepare("SELECT id,name,password,role FROM users WHERE email=? AND role='user'");
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["role"] = $user["role"];
                header("Location: homepage.php");
                exit;
            } else {
                $login_errors[] = "Incorrect Password.";
            }
        } else {
            $login_errors[] = "Account not found.";
        }
    }
}

// ---------------- SIGNUP ----------------
if (isset($_POST['signup_submit'])) {
    $name = trim($_POST['signup_name']);
    $email = trim($_POST['signup_email']);
    $phone = trim($_POST['signup_phone']);
    $address = trim($_POST['signup_address']);
    $password = $_POST['signup_password'];
    $confirm_password = $_POST['signup_confirm_password'];

    if (!$name) $signup_errors[] = "Name is required.";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $signup_errors[] = "Valid email is required.";

    // Phone validation
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $signup_errors[] = "Phone must be exactly 10 digits.";
    }

    if (!$address) $signup_errors[] = "Address is required.";
    if (!$password || strlen($password) < 6) $signup_errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm_password) $signup_errors[] = "Passwords do not match.";

    // Email exists?
    if (empty($signup_errors)) {
        $stmt = $con->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) $signup_errors[] = "Email already exists.";
    }

    if (empty($signup_errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $role = "user";
        $stmt = $con->prepare("INSERT INTO users (name,email,phone,address,password,role) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $address, $hashed, $role);
        if ($stmt->execute()) {
            $signup_success = "Registration successful! Please login.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login & Signup - CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-left">
        <div>
            <h2>Welcome to CaterCraft</h2>
            <p>Login or Sign Up to proceed.</p>
        </div>
    </div>

    <div class="auth-right p-4">

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#login">Login</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#signup">Sign Up</button>
            </li>
        </ul>

        <div class="tab-content">

            <!-- LOGIN TAB -->
            <div class="tab-pane fade show active" id="login">
                <form method="POST" id="loginForm">

                    <select class="form-select mb-3" name="login_type" required>
                        <option value="user" selected>User</option>
                        <option value="admin">Admin</option>
                    </select>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="login_email" name="login_email" required>
                        <label>Email</label>
                    </div>

                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control" id="login_password" name="login_password" required>
                        <label>Password</label>
                        <span class="eye" onclick="toggle('login_password')">👁</span>
                    </div>

                    <button type="submit" name="login_submit" class="btn btn-success w-100">Login</button>
                </form>
            </div>

            <!-- SIGNUP TAB -->
            <div class="tab-pane fade" id="signup">
                <form method="POST" id="signupForm">

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="signup_name" id="signup_name" required>
                        <label>Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="signup_email" id="signup_email" required>
                        <label>Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="signup_phone" id="signup_phone" required>
                        <label>Phone (10 digits)</label>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="signup_address" id="signup_address" required></textarea>
                        <label>Address</label>
                    </div>

                    <div class="form-floating mb-2 position-relative">
                        <input type="password" class="form-control" name="signup_password" id="signup_password" required>
                        <label>Password</label>
                        <span class="eye" onclick="toggle('signup_password')">👁</span>
                    </div>

                    <div id="strengthBar" class="password-strength"></div>
                    <span id="strengthText" class="ms-1"></span>

                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control" name="signup_confirm_password" id="signup_confirm_password" required>
                        <label>Confirm Password</label>
                        <span class="eye" onclick="toggle('signup_confirm_password')">👁</span>
                    </div>

                    <button type="submit" name="signup_submit" class="btn btn-success w-100">Sign Up</button>
                </form>
            </div>

        </div>

    </div>
</div>


<!-- REQUIRED Bootstrap JS (fixes tab switching) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Toggle password visibility
function toggle(id) {
    let x = document.getElementById(id);
    x.type = x.type === "password" ? "text" : "password";
}

// Password strength meter
document.getElementById("signup_password").addEventListener("input", function () {
    let pwd = this.value;
    let strengthBar = document.getElementById("strengthBar");
    let strengthText = document.getElementById("strengthText");

    let strength = 0;
    if (pwd.length >= 6) strength++;
    if (/[A-Z]/.test(pwd)) strength++;
    if (/[0-9]/.test(pwd)) strength++;
    if (/[@$!%*?&#]/.test(pwd)) strength++;

    let colors = ["red", "orange", "yellow", "lightgreen", "green"];
    strengthBar.style.background = colors[strength];

    let msg = ["Very Weak", "Weak", "Medium", "Strong", "Very Strong"];
    strengthText.innerHTML = msg[strength];
});

// Red border + shake
document.querySelectorAll("input, textarea").forEach(f => {
    f.addEventListener("blur", function () {
        if (!this.value.trim()) {
            this.classList.add("is-invalid", "shake");
            setTimeout(() => this.classList.remove("shake"), 300);
        } else {
            this.classList.remove("is-invalid");
        }
    });
});

// Phone validation
document.getElementById("signup_phone").addEventListener("input", function () {
    if (!/^[0-9]{10}$/.test(this.value)) {
        this.classList.add("is-invalid", "shake");
        setTimeout(() => this.classList.remove("shake"), 300);
    } else {
        this.classList.remove("is-invalid");
    }
});

// PHP message alerts
<?php
if ($login_errors) echo "alert('Login Error:\\n".implode("\\n", $login_errors)."');";
if ($signup_errors) echo "alert('Signup Error:\\n".implode("\\n", $signup_errors)."');";
if ($signup_success) echo "alert('$signup_success');";
?>
</script>

</body>
</html>
