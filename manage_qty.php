<?php
require('connection.inc.php');
require('functions.inc.php');
require('add_to_cart.inc.php');

$action = $_GET['action'];
$product_id = $_GET['id'];

if ($action == 'increment') {
    // Increase the quantity of the specified product in the database
    mysqli_query($con, "UPDATE cart_items SET quantity = quantity + 1 WHERE product_id = '$product_id'");
    header("Location: {$_SERVER['HTTP_REFERER']}");
} 
if ($action == 'decrement') {
    // Decrease the quantity of the specified product in the database
    mysqli_query($con, "UPDATE cart_items SET quantity = quantity - 1 WHERE product_id = '$product_id' AND quantity > 1");
    header("Location: {$_SERVER['HTTP_REFERER']}");
} 
if ($action == 'update') {
    // Retrieve the new quantity from the URL
    $new_quantity = $_GET['qty'];
    // Update the quantity of the specified product in the database
    mysqli_query($con, "UPDATE cart_items SET quantity = '$new_quantity' WHERE product_id = '$product_id'");
    header("Location: {$_SERVER['HTTP_REFERER']}");
} 

// Function to get the total number of products in the cart
echo total_products_in_cart($uid, $con);
?>
