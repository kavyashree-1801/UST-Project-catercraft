<?php
// Database credentials
$host = "localhost";
$user = "root";
$password = "";   // XAMPP default is empty
$database = "catercraft";

// Create MySQLi connection
$con = new mysqli($host, $user, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Optional: Set charset (recommended)
$con->set_charset("utf8mb4");
?>