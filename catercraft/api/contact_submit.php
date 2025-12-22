<?php
session_start();
include '../config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status"=>"error","message"=>"Please login first."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name'] ?? '');
    $email = mysqli_real_escape_string($con, $_POST['email'] ?? '');
    $message = mysqli_real_escape_string($con, $_POST['message'] ?? '');

    if ($name && $email && $message) {
        $stmt = $con->prepare("INSERT INTO contact_messages (name,email,message) VALUES (?,?,?)");
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            echo json_encode(["status"=>"success","message"=>"Your message has been sent successfully!"]);
        } else {
            echo json_encode(["status"=>"error","message"=>"Something went wrong. Please try again."]);
        }
    } else {
        echo json_encode(["status"=>"error","message"=>"All fields are required!"]);
    }
} else {
    echo json_encode(["status"=>"error","message"=>"Invalid request method."]);
}
