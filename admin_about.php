<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Handle Update
$edit_mode = false;
$title = ''; $description = ''; $icon = ''; $id = '';

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM features WHERE id=$id");
    $row = $result->fetch_assoc();
    $title = $row['title'];
    $description = $row['description'];
    $icon = $row['icon'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $icon = $_POST['icon'];

    $stmt = $conn->prepare("UPDATE features SET title=?, description=?, icon=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $description, $icon, $id);
    $stmt->execute();
    header("Location: admin_about.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage About Us</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container { max-width: 1000px; margin: 40px auto; padding: 20px; }
        .form-box { background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn-edit { background-color: #3498db; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="btn btn-outline">&larr; Back to Dashboard</a>
        <h2>Manage About Us (Features)</h2>
        
        <?php if($edit_mode): ?>
        <div class="form-box">
            <h3>Edit Feature</h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="form-group">
                    <label>Icon Class (FontAwesome)</label>
                    <input type="text" name="icon" value="<?php echo $icon; ?>" required>
                </div>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" value="<?php echo $title; ?>" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" required><?php echo $description; ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Feature</button>
                <a href="admin_about.php" class="btn btn-outline">Cancel</a>
            </form>
        </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM features");
                while($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo $row['icon']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>