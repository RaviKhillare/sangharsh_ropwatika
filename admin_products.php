<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: admin_products.php");
}

// Handle Add / Update
$edit_mode = false;
$name = ''; $price = ''; $description = ''; $image_url = ''; $id = '';

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM products WHERE id=$id");
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $price = $row['price'];
    $description = $row['description'];
    $image_url = $row['image_url'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, description=?, image_url=? WHERE id=?");
        $stmt->bind_param("sdssi", $name, $price, $description, $image_url, $id);
        $stmt->execute();
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO products (name, price, description, image_url) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $name, $price, $description, $image_url);
        $stmt->execute();
    }
    header("Location: admin_products.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container { max-width: 1000px; margin: 40px auto; padding: 20px; }
        .form-box { background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn-sm { padding: 5px 10px; font-size: 0.9rem; margin-right: 5px; }
        .btn-danger { background-color: #e74c3c; color: white; }
        .btn-edit { background-color: #3498db; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="btn btn-outline">&larr; Back to Dashboard</a>
        <h2><?php echo $edit_mode ? 'Edit Product' : 'Add New Product'; ?></h2>
        
        <div class="form-box">
            <form method="POST">
                <?php if($edit_mode): ?><input type="hidden" name="id" value="<?php echo $id; ?>"><?php endif; ?>
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" value="<?php echo $name; ?>" required>
                </div>
                <div class="form-group">
                    <label>Price (₹)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $price; ?>" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" required><?php echo $description; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image_url" value="<?php echo $image_url; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Update Product' : 'Add Product'; ?></button>
                <?php if($edit_mode): ?> <a href="admin_products.php" class="btn btn-outline">Cancel</a> <?php endif; ?>
            </form>
        </div>

        <h3>Existing Products</h3>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
                while($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><img src="<?php echo $row['image_url']; ?>" width="50" alt=""></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>₹<?php echo $row['price']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-edit">Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>