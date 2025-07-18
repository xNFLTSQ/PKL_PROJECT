<?php
// Setup database untuk sistem buku tamu dan dispensasi

// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'guest_dispensation_system';

try {
    // Connect to MySQL server (without database)
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Setting up Database...</h2>";
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    echo "✅ Database '$dbname' created successfully<br>";
    
    // Use the database
    $pdo->exec("USE $dbname");
    
    // Create admin table
    $sql = "CREATE TABLE IF NOT EXISTS admin (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✅ Admin table created successfully<br>";
    
    // Create guest_book table
    $sql = "CREATE TABLE IF NOT EXISTS guest_book (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100),
        phone VARCHAR(20),
        institution VARCHAR(100),
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✅ Guest book table created successfully<br>";
    
    // Create dispensation table
    $sql = "CREATE TABLE IF NOT EXISTS dispensation (
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
    )";
    $pdo->exec($sql);
    echo "✅ Dispensation table created successfully<br>";
    
    // Delete existing admin and create new one with correct password
    $pdo->exec("DELETE FROM admin WHERE username = 'admin'");
    
    // Create new admin with correct password hash
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
    $stmt->execute(['admin', $hashedPassword]);
    echo "✅ Admin user created/updated successfully<br>";
    echo "<strong>Login Info:</strong><br>";
    echo "Username: <strong>admin</strong><br>";
    echo "Password: <strong>admin123</strong><br><br>";
    
    // Test the password hash
    echo "<strong>Password Hash Test:</strong><br>";
    if (password_verify('admin123', $hashedPassword)) {
        echo "✅ Password hash verification: SUCCESS<br>";
    } else {
        echo "❌ Password hash verification: FAILED<br>";
    }
    
    echo "<h3>✅ Database setup completed successfully!</h3>";
    echo "<p><a href='index.php'>Go to Homepage</a> | <a href='admin/login.php'>Go to Admin Login</a></p>";
    
} catch(PDOException $e) {
    echo "<h3>❌ Error: " . $e->getMessage() . "</h3>";
    echo "<p>Please check your MySQL connection and try again.</p>";
}
?>
