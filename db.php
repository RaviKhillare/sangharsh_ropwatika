<?php
$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password is empty
$dbname = "ropvatika";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1049) { // Error code 1049 is 'Unknown database'
        die("Database '$dbname' not found. Please run <a href='setup_database.php'>setup_database.php</a> to create it.");
    }
    die("Connection failed: " . $e->getMessage());
}
?>