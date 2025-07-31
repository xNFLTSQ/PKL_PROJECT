<?php
/**
 * Helper functions for the Government Guest Book & Dispensation System
 * 
 * This file contains utility functions used throughout the application
 * for data sanitization, validation, authentication, and formatting.
 */

class SecurityHelper {
    /**
     * Sanitize input data to prevent XSS attacks
     * 
     * @param string $data Raw input data
     * @return string Sanitized data
     */
    public static function sanitizeInput($data) {
        if (!is_string($data)) {
            return $data;
        }
        
        return htmlspecialchars(trim(stripslashes($data)), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Generate CSRF token for form security
     * 
     * @return string CSRF token
     */
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Verify CSRF token
     * 
     * @param string $token Token to verify
     * @return bool True if valid, false otherwise
     */
    public static function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

class AuthHelper {
    /**
     * Check if admin is logged in
     * 
     * @return bool True if admin is logged in
     */
    public static function isAdminLoggedIn() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    /**
     * Require admin authentication, redirect if not logged in
     * 
     * @return void
     */
    public static function requireAdmin() {
        if (!self::isAdminLoggedIn()) {
            header('Location: admin/login.php');
            exit();
        }
    }
}

class ValidationHelper {
    /**
     * Validate email address
     * 
     * @param string $email Email to validate
     * @return bool True if valid email
     */
    public static function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate phone number (Indonesian format)
     * 
     * @param string $phone Phone number to validate
     * @return bool True if valid phone number
     */
    public static function isValidPhone($phone) {
        return preg_match('/^(\+62|62|0)[0-9]{9,13}$/', $phone);
    }
}

class FormatHelper {
    /**
     * Format date for display
     * 
     * @param string $date Date string
     * @param string $format Date format (default: 'd/m/Y H:i')
     * @return string Formatted date
     */
    public static function formatDate($date, $format = 'd/m/Y H:i') {
        try {
            return date($format, strtotime($date));
        } catch (Exception $e) {
            return 'Invalid Date';
        }
    }
    
    /**
     * Get status badge HTML
     * 
     * @param string $status Status value
     * @return string HTML badge
     */
    public static function getStatusBadge($status) {
        $badges = [
            'pending' => '<span class="badge bg-warning text-dark">Menunggu</span>',
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>'
        ];
        
        return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
    
    /**
     * Truncate text with ellipsis
     * 
     * @param string $text Text to truncate
     * @param int $length Maximum length
     * @return string Truncated text
     */
    public static function truncateText($text, $length = 150) {
        return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
    }
}

// Backward compatibility functions
function sanitize_input($data) {
    return SecurityHelper::sanitizeInput($data);
}

function is_admin_logged_in() {
    return AuthHelper::isAdminLoggedIn();
}

function require_admin() {
    AuthHelper::requireAdmin();
}

function format_date($date) {
    return FormatHelper::formatDate($date);
}

function get_status_badge($status) {
    return FormatHelper::getStatusBadge($status);
}

function is_valid_email($email) {
    return ValidationHelper::isValidEmail($email);
}

function generate_csrf_token() {
    return SecurityHelper::generateCSRFToken();
}

function verify_csrf_token($token) {
    return SecurityHelper::verifyCSRFToken($token);
}
?>
