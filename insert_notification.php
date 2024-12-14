<?php
// insert_notification.php

// Include your database connection file
require('connection.inc.php'); // Adjust this based on your actual file
date_default_timezone_set('Asia/Kolkata');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sellerId = $_POST['seller_id'];
    $productId = $_POST['product_id'];
    $message = $_POST['message'];
    // Get current date and time
    $currentdate = date('Y-m-d H:i:s');
    // Insert into the notifications table
    $query = "INSERT INTO notifications (seller_id, product_id, message, created_at) VALUES ('$sellerId', '$productId', '$message', '$currentdate')";
    if (mysqli_query($con, $query)) {
        echo "Notification inserted successfully";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
}
?>
