<?php
include("header.html");
session_start();  // Start session
session_destroy();  // Destroy the session

// Redirect to login page after logout
header("Location: login.php");
exit();
?>
