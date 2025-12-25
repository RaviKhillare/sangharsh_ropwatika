<?php
include 'api/db_connect.php';

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $key => $value) {
        if ($key != 'submit') {
            $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
            $stmt->bind_param("sss", $key, $value, $value);
            $stmt->execute();
        }
    }
    $success = "Settings updated successfully!";
}

// Fetch Settings
$settings = [];
$result = $conn->query("SELECT * FROM settings");
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Site Settings | Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .container { max-width: 800px; margin: 40px auto; padding: 20px; }
        .form-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; }
        .section-header { margin: 30px 0 15px; border-bottom: 2px solid #eee; padding-bottom: 10px; color: var(--primary-color); }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="btn btn-outline" style="margin-bottom: 20px; display:inline-block;">&larr; Back to Dashboard</a>
        
        <h2>Site Settings</h2>
        <?php if(isset($success)) echo "<p style='color:green; font-weight:bold;'>$success</p>"; ?>

        <form method="POST" class="form-box">
            <h3 class="section-header"><i class="fas fa-globe"></i> General Info</h3>
            <div class="form-group">
                <label>Website Title</label>
                <input type="text" name="site_title" value="<?php echo $settings['site_title'] ?? ''; ?>">
            </div>

            <h3 class="section-header"><i class="fas fa-home"></i> Hero Section</h3>
            <div class="form-group">
                <label>Hero Title</label>
                <input type="text" name="hero_title" value="<?php echo $settings['hero_title'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label>Hero Subtitle</label>
                <textarea name="hero_subtitle" rows="2"><?php echo $settings['hero_subtitle'] ?? ''; ?></textarea>
            </div>

            <h3 class="section-header"><i class="fas fa-address-book"></i> Contact Details</h3>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="contact_phone" value="<?php echo $settings['contact_phone'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="contact_email" value="<?php echo $settings['contact_email'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="contact_address" rows="2"><?php echo $settings['contact_address'] ?? ''; ?></textarea>
            </div>

            <button type="submit" name="submit" class="btn btn-primary" style="width:100%; margin-top:20px;">Save Changes</button>
        </form>
    </div>
</body>
</html>
