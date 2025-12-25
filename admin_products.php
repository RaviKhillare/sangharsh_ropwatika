<?php
include 'api/db_connect.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: admin_products.php");
}

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $cat = $_POST['category'];
    $desc = $_POST['description'];
    $img = $_POST['image_url']; // In real app, handle file upload here

    $sql = "INSERT INTO products (name, price, category, description, image_url) VALUES ('$name', '$price', '$cat', '$desc', '$img')";
    if ($conn->query($sql) === TRUE) {
        $msg = "Product Added Successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products | Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .admin-container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .form-card { background: white; padding: 25px; border-radius: 10px; margin-bottom: 30px; box-shadow: var(--shadow-sm); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: var(--shadow-sm); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: var(--secondary); color: white; }
        tr:hover { background: #f9f9f9; }
        .btn-delete { color: red; cursor: pointer; }
    </style>
</head>
<body>

<div class="admin-container">
    <a href="admin.php" class="btn btn-outline" style="margin-bottom: 20px; display:inline-block;">&larr; Back to Dashboard</a>
    
    <div class="form-card">
        <h3>Add New Product</h3>
        <?php if(isset($msg)) echo "<p style='color:green'>$msg</p>"; ?>
        <form method="POST">
            <div class="dashboard-grid">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Price (₹)</label>
                    <input type="number" name="price" required>
                </div>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    <option value="indoor">Indoor</option>
                    <option value="outdoor">Outdoor</option>
                    <option value="succulents">Succulents/Cactus</option>
                </select>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="image_url" placeholder="https://..." required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>

    <h3>Product List</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
            while($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><img src="<?php echo $row['image_url']; ?>" width="40" height="40" style="border-radius:5px; object-fit:cover;"></td>
                <td><?php echo $row['name']; ?></td>
                <td>₹<?php echo $row['price']; ?></td>
                <td><?php echo ucfirst($row['category']); ?></td>
                <td>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>