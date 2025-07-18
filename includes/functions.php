<?php
// Helper functions for the application

// Sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if admin is logged in
function is_admin_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Redirect to login if not admin
function require_admin() {
    if (!is_admin_logged_in()) {
        header('Location: admin/login.php');
        exit();
    }
}

// Format date for display
function format_date($date) {
    return date('d/m/Y H:i', strtotime($date));
}

// Get status badge HTML
function get_status_badge($status) {
    $badges = [
        'pending' => '<span class="badge badge-warning">Menunggu</span>',
        'approved' => '<span class="badge badge-success">Disetujui</span>',
        'rejected' => '<span class="badge badge-danger">Ditolak</span>'
    ];
    return $badges[$status] ?? '<span class="badge badge-secondary">Unknown</span>';
}

// Validate email
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Generate CSRF token
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
