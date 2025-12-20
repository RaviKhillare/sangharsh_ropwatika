<?php
$servername = "localhost";
$username = "root";
$password = "";

// 1. Create connection to MySQL (without selecting a database yet)
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Create database
$sql = "CREATE DATABASE IF NOT EXISTS ropvatika";
if ($conn->query($sql) === TRUE) {
    echo "Database 'ropvatika' created successfully.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// 3. Select the database
$conn->select_db("ropvatika");

// 4. Create products table
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'products' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Ensure description column exists (for existing databases)
$check_col = $conn->query("SHOW COLUMNS FROM products LIKE 'description'");
if ($check_col->num_rows == 0) {
    $conn->query("ALTER TABLE products ADD COLUMN description TEXT AFTER price");
}

// 4.5 Create messages table
$sql = "CREATE TABLE IF NOT EXISTS messages (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'messages' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// 4.6 Create features (About Us) table
$sql = "CREATE TABLE IF NOT EXISTS features (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    icon VARCHAR(50) NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'features' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Insert default features if empty
$check_features = $conn->query("SELECT count(*) as count FROM features");
$feat_row = $check_features->fetch_assoc();
if ($feat_row['count'] == 0) {
    $conn->query("INSERT INTO features (icon, title, description) VALUES ('fas fa-seedling', 'सेंद्रिय वाढ', 'आमची सर्व रोपे १००% सेंद्रिय खतांचा वापर करून वाढवली जातात.'), ('fas fa-truck', 'जलद वितरण', 'तुमच्या दारात सुरक्षित आणि जलद वितरण.'), ('fas fa-hand-holding-heart', 'तज्ञांचे मार्गदर्शन', 'आमच्या तज्ञ वनस्पतिशास्त्रज्ञांकडून बागकामाच्या टिप्स मिळवा.')");
}

// 4.7 Create notifications table
$sql = "CREATE TABLE IF NOT EXISTS notifications (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'notifications' created successfully.<br>";
    // Insert default notification if empty
    $check_notif = $conn->query("SELECT count(*) as count FROM notifications");
    $notif_row = $check_notif->fetch_assoc();
    if ($notif_row['count'] == 0) {
        $conn->query("INSERT INTO notifications (message) VALUES ('महत्वाची सूचना: पावसाळ्यानिमित्त सर्व रोपांवर २०% सूट!')");
    }
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// 4.8 Create hero_images table
$sql = "CREATE TABLE IF NOT EXISTS hero_images (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image_url VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'hero_images' created successfully.<br>";
    $check_hero = $conn->query("SELECT count(*) as count FROM hero_images");
    $hero_row = $check_hero->fetch_assoc();
    if ($hero_row['count'] == 0) {
        $conn->query("INSERT INTO hero_images (image_url) VALUES ('https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&w=1920&q=80')");
    }
}

// 5. Insert dummy data (only if table is empty)
$check = $conn->query("SELECT count(*) as count FROM products");
$row = $check->fetch_assoc();

if ($row['count'] == 0) {
    $sql_insert = "INSERT INTO products (name, price, description, image_url) VALUES 
    ('स्नेक प्लांट', 450.00, 'हवा शुद्ध करणारे उत्कृष्ट इनडोअर रोप.', 'https://images.unsplash.com/photo-1512428813838-6591185a31b1?auto=format&fit=crop&w=500&q=60'),
    ('कोरफड', 250.00, 'त्वचेसाठी आणि आरोग्यासाठी गुणकारी औषधी वनस्पती.', 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?auto=format&fit=crop&w=500&q=60'),
    ('गोल्डन कॅक्टस', 300.00, 'कमी देखभाल लागणारे सुंदर सजावटीचे रोप.', 'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?auto=format&fit=crop&w=500&q=60'),
    ('मॉन्स्टेरा', 850.00, 'मोठ्या पानांचे आकर्षक इनडोअर प्लांट.', 'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=500&q=60')";
    
    if ($conn->query($sql_insert) === TRUE) {
        echo "Sample data inserted successfully.<br>";
    } else {
        echo "Error inserting data: " . $conn->error;
    }
} else {
    echo "Table already contains data.<br>";
}

$conn->close();
echo "<br><strong>Setup Complete! <a href='index.php'>Go to Home Page</a></strong>";
?>