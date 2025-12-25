<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $name = $data['name'];
    $phone = $data['phone'];
    $address = $data['address'];
    $total = $data['total'];
    $cart = $data['cart'];
    
    // Insert Order
    $sql = "INSERT INTO orders (customer_name, customer_phone, customer_address, total_amount) 
            VALUES ('$name', '$phone', '$address', '$total')";
            
    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id;
        
        // Insert Items
        foreach ($cart as $item) {
            $prod_id = $item['id'];
            $prod_name = $item['name'];
            $qty = $item['quantity'];
            $price = $item['price'];
            
            $item_sql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price) 
                         VALUES ('$order_id', '$prod_id', '$prod_name', '$qty', '$price')";
            $conn->query($item_sql);
        }
        
        echo json_encode(["success" => true, "message" => "Order placed successfully!", "order_id" => $order_id]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
    }
}
$conn->close();
?>
