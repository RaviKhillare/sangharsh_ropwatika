<?php
include 'api/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages | Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .container { max-width: 1000px; margin: 40px auto; padding: 20px; }
        .message-card { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 5px solid var(--secondary); }
        .msg-header { display: flex; justify-content: space-between; margin-bottom: 10px; color: #666; font-size: 0.9rem; }
        .msg-title { font-size: 1.1rem; font-weight: bold; margin-bottom: 5px; }
        .msg-body { color: #333; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="btn btn-outline" style="margin-bottom: 20px; display:inline-block;">&larr; Back to Dashboard</a>
        <h2>Inbox (Messages)</h2>

        <?php
        $result = $conn->query("SELECT * FROM messages ORDER BY id DESC");
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
        ?>
        <div class="message-card">
            <div class="msg-header">
                <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['name']); ?> &nbsp;|&nbsp; <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></span>
                <span><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></span>
            </div>
            <div class="msg-body">
                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
            </div>
        </div>
        <?php 
            }
        } else {
            echo "<p style='text-align:center; color:#777;'>No messages found.</p>";
        }
        ?>
    </div>
</body>
</html>
