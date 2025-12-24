<?php
header("Content-Type: application/json; charset=utf-8");

function sendJson($status, $message, $httpCode = 200) {
    http_response_code($httpCode);
    echo json_encode(["status" => $status, "message" => $message]);
    exit;
}

require_once __DIR__ . "/../config.php";

if (!isset($con) || !$con) {
    sendJson("error", "Database connection failed.", 500);
}

$token = $_POST['token'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (!$token || !$new_password || !$confirm_password) {
    sendJson("error", "All fields are required.", 400);
}

if ($new_password !== $confirm_password) {
    sendJson("error", "Passwords do not match.", 400);
}

$stmt = $con->prepare("SELECT id, token_expiry FROM users WHERE reset_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    sendJson("error", "Invalid or expired token.", 400);
}

$stmt->bind_result($user_id, $token_expiry);
$stmt->fetch();
$stmt->close();

if (new DateTime() > new DateTime($token_expiry)) {
    sendJson("error", "This reset link has expired.", 400);
}

$hashed = password_hash($new_password, PASSWORD_DEFAULT);
$update = $con->prepare("
    UPDATE users
    SET password = ?, reset_token = NULL, token_expiry = NULL
    WHERE id = ?
");
$update->bind_param("si", $hashed, $user_id);

if ($update->execute()) {
    sendJson("success", "Password has been reset successfully.");
} else {
    sendJson("error", "Failed to update password.", 500);
}
