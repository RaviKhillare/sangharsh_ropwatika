<?php
include 'api/db_connect.php';

// Handle Add
if (isset($_POST['add_notification'])) {
    $msg = $_POST['message'];
    $conn->query("INSERT INTO notifications (message) VALUES ('$msg')");
    $success = "Notification Added!";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM notifications WHERE id=$id");
    header("Location: admin_notification.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Notifications | Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .container { max-width: 800px; margin: 40px auto; padding: 20px; }
        .form-box { background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .notif-list { background: white; border-radius: 8px; overflow: hidden; }
        .notif-item { padding: 15px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .notif-item:last-child { border-bottom: none; }
        .btn-delete { color: #e74c3c; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="btn btn-outline" style="margin-bottom: 20px; display:inline-block;">&larr; Back to Dashboard</a>
        <h2>Notifications Ticker</h2>

        <div class="form-box">
            <h4>Add New Notice</h4>
            <form method="POST">
                <div style="display:flex; gap:10px;">
                    <input type="text" name="message" placeholder="Type notification..." required style="flex:1; padding:10px; border:1px solid #ddd; border-radius:4px;">
                    <button type="submit" name="add_notification" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>

        <div class="notif-list">
            <h4 style="padding:15px; background:#eee; margin:0;">Active Notifications</h4>
            <?php
            $result = $conn->query("SELECT * FROM notifications ORDER BY id DESC");
            while($row = $result->fetch_assoc()):
            ?>
            <div class="notif-item">
                <span><?php echo htmlspecialchars($row['message']); ?></span>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete"><i class="fas fa-trash"></i></a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>