<?php
header("Content-Type: application/json; charset=utf-8");

// Disable direct error output
ini_set('display_errors', 0);
error_reporting(E_ALL);

// JSON helper
function sendJson($status, $message, $link = null, $httpCode = 200) {
    http_response_code($httpCode);
    $response = [
        "status" => $status,
        "message" => $message
    ];
    if ($link !== null) {
        $response["link"] = $link;
    }
    echo json_encode($response);
    exit;
}

// Include database config
$configPath = __DIR__ . "/../config.php";
if (!file_exists($configPath)) {
    sendJson("error", "Config file not found.", null, 500);
}
require_once $configPath;

// Check DB connection ($con)
if (!isset($con) || !$con) {
    sendJson("error", "Database connection failed: " . (isset($con->connect_error) ? $con->connect_error : ""), null, 500);
}

// Get email
$email = isset($_POST['email']) ? trim($_POST['email']) : "";
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJson("error", "Please enter a valid email.", null, 400);
}

// Check if user exists
$stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
if ($stmt === false) {
    sendJson("error", "Database SELECT error: " . $con->error, null, 500);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    sendJson("error", "No account found with that email.", null, 404);
}

$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Generate token & expiry
$token = bin2hex(random_bytes(32));

// Set token expiry to 5 hours from now
$expires = date("Y-m-d H:i:s", strtotime("+5 hours"));

// Prepare correct UPDATE with your column names
$sql = "UPDATE users SET reset_token = ?, token_expiry = ? WHERE id = ?";
$update = $con->prepare($sql);
if ($update === false) {
    sendJson("error", "Prepare error (UPDATE): " . $con->error, null, 500);
}

$update->bind_param("ssi", $token, $expires, $user_id);

// Execute update
if (!$update->execute()) {
    sendJson("error", "Execute error (UPDATE): " . $update->error, null, 500);
}

$update->close();

// Build the reset link
$reset_link = "reset_password.php?token=" . urlencode($token);

// Success
sendJson("success", "Reset link generated successfully.", $reset_link, 200);
