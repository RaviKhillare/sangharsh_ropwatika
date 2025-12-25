<?php
$servername = "localhost";
$username = "root";
$password = "";

// 1. Create connection
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Create database
$sql = "CREATE DATABASE IF NOT EXISTS ropvatika";
if ($conn->query($sql) === TRUE) {
    echo "Database 'ropvatika' checked/created successfully.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

$conn->select_db("ropvatika");

// 3. Products Table (Updated)
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) DEFAULT 'indoor',
    description TEXT,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);
// Add category column if missing
$check = $conn->query("SHOW COLUMNS FROM products LIKE 'category'");
if ($check->num_rows == 0) {
    $conn->query("ALTER TABLE products ADD COLUMN category VARCHAR(50) DEFAULT 'indoor' AFTER price");
    echo "Added 'category' column to products.<br>";
}

// 4. Admin Users Table
$sql = "CREATE TABLE IF NOT EXISTS admin_users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    // Create default admin if not exists (admin / admin123)
    $check_admin = $conn->query("SELECT * FROM admin_users WHERE username='admin'");
    if ($check_admin->num_rows == 0) {
        $pass = password_hash("admin123", PASSWORD_DEFAULT);
        $conn->query("INSERT INTO admin_users (username, password) VALUES ('admin', '$pass')");
        echo "Default admin user created (admin / admin123).<br>";
    }
}

// 5. Settings Table
$sql = "CREATE TABLE IF NOT EXISTS settings (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Insert default settings
$defaults = [
    'site_title' => 'अंकुर रोपवाटिका',
    'contact_phone' => '+91 98765 43210',
    'contact_email' => 'info@ankurropvatika.com',
    'contact_address' => '123 ग्रीन स्ट्रीट, पुणे, महाराष्ट्र',
    'hero_title' => 'निसर्गाच्या जवळ जा',
    'hero_subtitle' => 'प्रीमियम दर्जाची झाडे आणि बागकामाचे साहित्य आता एका क्लिकवर.'
];

foreach ($defaults as $key => $val) {
    $conn->query("INSERT IGNORE INTO settings (setting_key, setting_value) VALUES ('$key', '$val')");
}

// 6. Orders Table
$sql = "CREATE TABLE IF NOT EXISTS orders (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(100),
    customer_address TEXT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

// 7. Order Items Table
$sql = "CREATE TABLE IF NOT EXISTS order_items (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT(6) UNSIGNED,
    product_id INT(6) UNSIGNED,
    product_name VARCHAR(100),
    quantity INT(4),
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id)
)";
$conn->query($sql);


echo "<br><strong>Database Upgrade Complete! Tables: products, admin_users, settings, orders.</strong>";
$conn->close();
?>