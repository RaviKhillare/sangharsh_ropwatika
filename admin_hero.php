<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image_url = $_POST['image_url'];
    // Insert new record to be fetched as latest
    $stmt = $conn->prepare("INSERT INTO hero_images (image_url) VALUES (?)");
    $stmt->bind_param("s", $image_url);
    $stmt->execute();
    $success = "Slideshow image updated successfully!";
}

// Fetch current
$result = $conn->query("SELECT * FROM hero_images ORDER BY id DESC LIMIT 1");
$current_img = "";
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_img = $row['image_url'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Slideshow Image</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container { max-width: 600px; margin: 50px auto; padding: 20px; }
        .form-box { background: #f9f9f9; padding: 30px; border-radius: 8px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 10px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .success { color: green; margin-bottom: 15px; }
        .preview { margin-top: 15px; max-width: 100%; height: auto; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="btn btn-outline">&larr; Back to Dashboard</a>
        <h2>Update Slideshow Image</h2>
        <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
        <div class="form-box">
            <form method="POST">
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image_url" value="<?php echo $current_img; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Image</button>
            </form>
            <?php if($current_img): ?>
                <p><strong>Current Image Preview:</strong></p>
                <img src="<?php echo $current_img; ?>" class="preview" alt="Current Hero">
            <?php endif; ?>
        </div>
    </div>
</body>
</html>