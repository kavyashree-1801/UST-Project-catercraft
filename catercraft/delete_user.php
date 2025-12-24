<?php
session_start();
include 'config.php';

// Only admin can delete users
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied.'); window.location='login.php';</script>";
    exit;
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    echo "<script>alert('No user ID provided.'); window.location='manage_users.php';</script>";
    exit;
}

$user_id = intval($_GET['id']);

// Check if user exists and is not admin
$stmt_check = $con->prepare("SELECT role FROM users WHERE id=?");
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('User not found.'); window.location='manage_users.php';</script>";
    exit;
}

$row = $result->fetch_assoc();
if ($row['role'] !== 'user') {
    echo "<script>alert('Cannot delete admin users.'); window.location='manage_users.php';</script>";
    exit;
}

// Delete the user
$stmt_delete = $con->prepare("DELETE FROM users WHERE id=?");
$stmt_delete->bind_param("i", $user_id);

if ($stmt_delete->execute()) {
    echo "<script>alert('User deleted successfully.'); window.location='manage_users.php';</script>";
} else {
    echo "<script>alert('Failed to delete user: ".$stmt_delete->error."'); window.location='manage_users.php';</script>";
}

$stmt_delete->close();
$stmt_check->close();
?>
