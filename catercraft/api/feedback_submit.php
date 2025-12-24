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
    $rating = intval($_POST['rating'] ?? 0);
    $feedback = mysqli_real_escape_string($con, $_POST['feedback'] ?? '');

    if($name && $email && $rating > 0 && $feedback) {
        $stmt = $con->prepare("INSERT INTO feedback (name,email,rating,feedback) VALUES (?,?,?,?)");
        $stmt->bind_param("ssis", $name,$email,$rating,$feedback);

        if($stmt->execute()) {
            echo json_encode(["status"=>"success","message"=>"Thank you for your feedback!"]);
        } else {
            echo json_encode(["status"=>"error","message"=>"Database error: ".$stmt->error]);
        }
    } else {
        echo json_encode(["status"=>"error","message"=>"All fields are required!"]);
    }
} else {
    echo json_encode(["status"=>"error","message"=>"Invalid request method."]);
}
