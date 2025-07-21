<?php
require_once '../includes/config.example.php';

// Destroy session
session_destroy();

// Redirect to login page
header('Location: login.php');
exit();
?>
