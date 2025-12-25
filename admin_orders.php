<?php
include 'api/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders | Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .admin-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: var(--shadow-sm); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #34495e; color: white; }
        tr:hover { background: #f9f9f9; }
        
        .badge-pending { background: #f39c12; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; }
        .badge-completed { background: #27ae60; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; }
    </style>
</head>
<body>

<div class="admin-container">
    <a href="admin.php" class="btn btn-outline" style="margin-bottom: 20px; display:inline-block;">&larr; Back to Dashboard</a>
    
    <h2>Customer Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Items (Summary)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT o.*, GROUP_CONCAT(oi.product_name, ' (', oi.quantity, ')') SEPARATOR ', ') as items 
                    FROM orders o 
                    LEFT JOIN order_items oi ON o.id = oi.order_id 
                    GROUP BY o.id 
                    ORDER BY o.id DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td>#<?php echo $row['id']; ?></td>
                <td>
                    <strong><?php echo $row['customer_name']; ?></strong><br>
                    <small><?php echo $row['customer_phone']; ?></small>
                </td>
                <td>₹<?php echo $row['total_amount']; ?></td>
                <td><span class="badge-pending"><?php echo ucfirst($row['status']); ?></span></td>
                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                <td style="max-width:300px;"><?php echo $row['items']; ?></td>
            </tr>
            <?php 
                endwhile; 
            } else {
                echo "<tr><td colspan='6' style='text-align:center'>No orders found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
