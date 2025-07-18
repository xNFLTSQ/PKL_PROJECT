<?php
// Database configuration template
// Copy this file to config.php and update with your database settings

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'guest_dispensation_system');

// Create database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start session
session_start();

// Site configuration
define('SITE_NAME', 'Sistem Buku Tamu & Dispensasi');
define('SITE_DESCRIPTION', 'Sistem Informasi Buku Tamu dan Dispensasi Pemerintahan');
?>
