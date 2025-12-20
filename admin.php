<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Ankur Ropvatika</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container { max-width: 1200px; margin: 50px auto; padding: 20px; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; }
        .dashboard-card { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s; }
        .dashboard-card:hover { transform: translateY(-5px); }
        .dashboard-card i { font-size: 3rem; color: #2ecc71; margin-bottom: 20px; }
        .dashboard-card h3 { margin-bottom: 15px; }
        .btn-logout { background: #e74c3c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Admin Dashboard</h1>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <i class="fas fa-leaf"></i>
                <h3>Manage Products</h3>
                <p>Add, Edit, or Delete plants and products.</p>
                <a href="admin_products.php" class="btn btn-primary">Go to Products</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-info-circle"></i>
                <h3>Manage About Us</h3>
                <p>Update features and about section content.</p>
                <a href="admin_about.php" class="btn btn-primary">Go to About Us</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-bullhorn"></i>
                <h3>Notifications</h3>
                <p>Update the scrolling alert message.</p>
                <a href="admin_notification.php" class="btn btn-primary">Update Message</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-images"></i>
                <h3>Slideshow Image</h3>
                <p>Change the main hero background image.</p>
                <a href="admin_hero.php" class="btn btn-primary">Update Image</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-home"></i>
                <h3>View Website</h3>
                <p>Visit the main website home page.</p>
                <a href="index.php" class="btn btn-outline">Visit Home</a>
            </div>
        </div>
    </div>
</body>
</html>