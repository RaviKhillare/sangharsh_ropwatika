<?php
// get_products.php
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'username', 'password', 'database_name');
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
$result = $conn->query("SELECT name, icon FROM products");
$products = [];
while($row = $result->fetch_assoc()) {
    $products[] = $row;
}
echo json_encode($products);
$conn->close();
?>
