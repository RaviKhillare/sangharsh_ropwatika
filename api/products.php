<?php
include 'db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    $category = isset($_GET['category']) ? $_GET['category'] : 'all';
    
    if ($category == 'all') {
        $sql = "SELECT * FROM products ORDER BY id DESC";
    } else {
        $sql = "SELECT * FROM products WHERE category = '$category' ORDER BY id DESC";
    }
    
    $result = $conn->query($sql);
    $products = [];
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    echo json_encode($products);
}

if ($method == 'POST') {
    // Basic Admin protection would go here
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Add logic for adding products via API (Optional, mainly for Admin Panel)
    echo json_encode(["message" => "Method not implemented for public API"]);
}

$conn->close();
?>
