<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    // We insert a new one so we have history, or we could update the latest. 
    // Here we insert new to keep it simple with the existing fetch logic (ORDER BY id DESC LIMIT 1).
    $stmt = $conn->prepare("INSERT INTO notifications (message) VALUES (?)");
    $stmt->bind_param("s", $message);
    $stmt->execute();
    $success = "Notification updated successfully!";
}

// Fetch current
$result = $conn->query("SELECT * FROM notifications ORDER BY id DESC LIMIT 1");
$current_msg = "";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_msg = $row['message'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Notification</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container { max-width: 600px; margin: 50px auto; padding: 20px; }
        .form-box { background: #f9f9f9; padding: 30px; border-radius: 8px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 10px; font-weight: bold; }
        .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .success { color: green; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="btn btn-outline">&larr; Back to Dashboard</a>
        <h2>Update Notification Message</h2>
        <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
        <div class="form-box">
            <form method="POST">
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" rows="4" required><?php echo $current_msg; ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Message</button>
            </form>
        </div>
    </div>
</body>
</html>