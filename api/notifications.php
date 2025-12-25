<?php
include 'db_connect.php';

$sql = "SELECT message FROM notifications ORDER BY id DESC";
$result = $conn->query($sql);

$notifications = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $notifications[] = $row['message'];
    }
}

echo json_encode($notifications);
$conn->close();
?>
