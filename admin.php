<?php
include 'api/db_connect.php';
session_start();
// In a real app, implement login check here
// if (!isset($_SESSION['admin_logged_in'])) header("Location: login.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Ankur Ropwatika</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .admin-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        
        .admin-header {
            background: white;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: 0.3s;
            cursor: pointer;
        }

        .stat-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary);
        }

        .stat-info h3 { font-size: 2rem; margin: 0; color: var(--text-main); }
        .stat-info p { margin: 0; color: var(--text-light); }

        .action-grid {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .action-card {
            background: white;
            padding: 20px;
            text-align: center;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            border: 1px solid transparent;
            transition: 0.3s;
        }

        .action-card:hover { border-color: var(--secondary); background: #fdfdfd; }
        .action-card i { font-size: 2rem; color: var(--secondary); margin-bottom: 10px; }
        .action-card h4 { margin-bottom: 5px; }

    </style>
</head>
<body>

    <div class="admin-container">
        <div class="admin-header">
            <div>
                <h2>Admin Dashboard</h2>
                <p>Welcome back, Admin</p>
            </div>
            <a href="index.php" class="btn btn-outline" target="_blank">View Website</a>
        </div>

        <!-- Stats Overview -->
        <div class="dashboard-grid">
            <div class="stat-card" onclick="location.href='admin_orders.php'">
                <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
                <div class="stat-info">
                    <h3>
                        <?php 
                        $res = $conn->query("SELECT count(*) as c FROM orders WHERE status='pending'");
                        echo $res->fetch_assoc()['c'];
                        ?>
                    </h3>
                    <p>Pending Orders</p>
                </div>
            </div>
            <div class="stat-card" onclick="location.href='admin_products.php'">
                <div class="stat-icon"><i class="fas fa-leaf"></i></div>
                <div class="stat-info">
                    <h3>
                        <?php 
                        $res = $conn->query("SELECT count(*) as c FROM products");
                        echo $res->fetch_assoc()['c'];
                        ?>
                    </h3>
                    <p>Total Products</p>
                </div>
            </div>
            <div class="stat-card" onclick="location.href='admin_messages.php'">
                <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                <div class="stat-info">
                    <h3>
                        <?php 
                        $res = $conn->query("SELECT count(*) as c FROM messages");
                        echo $res->fetch_assoc()['c'];
                        ?>
                    </h3>
                    <p>Total Messages</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h3 style="margin: 40px 0 20px;">Quick Actions</h3>
        <div class="action-grid">
            <a href="admin_products.php" class="action-card">
                <i class="fas fa-plus-circle"></i>
                <h4>Manage Products</h4>
                <p>Add or Edit items</p>
            </a>
            <a href="admin_orders.php" class="action-card">
                <i class="fas fa-list-alt"></i>
                <h4>Manage Orders</h4>
                <p>View customer orders</p>
            </a>
            <a href="admin_settings.php" class="action-card">
                <i class="fas fa-cog"></i>
                <h4>Site Settings</h4>
                <p>Title, Phone, Info</p>
            </a>
            <a href="admin_messages.php" class="action-card">
                <i class="fas fa-comment-dots"></i>
                <h4>Messages</h4>
                <p>View Inbox</p>
            </a>
            <a href="admin_notification.php" class="action-card">
                <i class="fas fa-bullhorn"></i>
                <h4>Notifications</h4>
                <p>Edit Ticker</p>
            </a>
        </div>
    </div>

</body>
</html>