-- Database setup for Guest Book and Dispensation System
CREATE DATABASE IF NOT EXISTS guest_dispensation_system;
USE guest_dispensation_system;

-- Admin table
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Guest book table
CREATE TABLE guest_book (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    institution VARCHAR(100),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Dispensation table
CREATE TABLE dispensation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    nim_nip VARCHAR(50),
    department VARCHAR(100),
    reason TEXT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    proof_photo TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (username: admin, password: admin123)
-- Password hash generated with password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO admin (username, password) VALUES ('admin', '$2y$10$YourHashWillBeGeneratedBySetupScript');
