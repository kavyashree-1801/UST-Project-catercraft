<?php
session_start();
header('Content-Type: application/json');
include '../config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error','message'=>'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'update_profile') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    $stmt = $con->prepare("UPDATE users SET name=?, phone=?, address=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $phone, $address, $user_id);

    if ($stmt->execute()) {
        $_SESSION['name'] = $name;
        echo json_encode(['status'=>'success','message'=>'Profile updated successfully']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Error updating profile']);
    }

} elseif ($action === 'change_password') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $stmt = $con->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!password_verify($current, $row['password'])) {
        echo json_encode(['status'=>'error','message'=>'Current password is incorrect']);
    } elseif ($new !== $confirm) {
        echo json_encode(['status'=>'error','message'=>'Passwords do not match']);
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $update = $con->prepare("UPDATE users SET password=? WHERE id=?");
        $update->bind_param("si", $hashed, $user_id);
        if ($update->execute()) {
            echo json_encode(['status'=>'success','message'=>'Password changed successfully']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Error changing password']);
        }
    }
} else {
    echo json_encode(['status'=>'error','message'=>'Invalid action']);
}
