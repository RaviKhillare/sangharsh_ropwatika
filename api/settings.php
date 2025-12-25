<?php
include 'db_connect.php';

$settings = [];
$result = $conn->query("SELECT * FROM settings");

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}

echo json_encode($settings);
$conn->close();
?>
