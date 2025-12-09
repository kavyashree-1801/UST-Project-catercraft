<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to home or login page
header("Location: login.php"); 
exit;
?>
