<?php
session_start();
include "../config.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$action = $data['action'] ?? '';

/* ======================
   LOGIN
====================== */
if ($action === "login") {

    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';
    $type = $data['type'] ?? 'user';

    if (!$email || !$password) {
        echo json_encode([
            "status" => "error",
            "message" => "All fields are required"
        ]);
        exit;
    }

    $role = ($type === "admin") ? "admin" : "user";

    $stmt = $con->prepare(
        "SELECT id, name, password, role FROM users WHERE email=? AND role=?"
    );
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['role']    = $user['role'];

            echo json_encode([
                "status"   => "success",
                "redirect" => "homepage.php"   // ✅ REDIRECT HERE
            ]);
            exit;
        }
    }

    echo json_encode([
        "status" => "error",
        "message" => "Invalid email or password"
    ]);
    exit;
}

/* ======================
   SIGNUP
====================== */
if ($action === "signup") {

    $name     = trim($data['name'] ?? '');
    $email    = trim($data['email'] ?? '');
    $phone    = trim($data['phone'] ?? '');
    $address  = trim($data['address'] ?? '');
    $password = $data['password'] ?? '';

    if (!$name || !$email || !$password) {
        echo json_encode([
            "status" => "error",
            "message" => "All fields are required"
        ]);
        exit;
    }

    $check = $con->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Email already exists"
        ]);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $role = "user";

    $stmt = $con->prepare(
        "INSERT INTO users (name,email,phone,address,password,role)
         VALUES (?,?,?,?,?,?)"
    );
    $stmt->bind_param(
        "ssssss",
        $name,
        $email,
        $phone,
        $address,
        $hash,
        $role
    );

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Signup failed"
        ]);
    }
}
