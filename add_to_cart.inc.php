<?php
require_once 'connection.inc.php';

$con = new mysqli("localhost","root","","gadgetgalaxy");

function add_product_to_cart($pid, $qty, $con) {
    if(isset($_SESSION['USER_ID'])) {
        $uid = $_SESSION['USER_ID'];
        
        // Check if the product already exists in the user's cart
        $existing_product_query = mysqli_query($con, "SELECT * FROM cart_items WHERE user_id = '$uid' AND product_id = '$pid'");
        if(mysqli_num_rows($existing_product_query) > 0) {
            // Product already exists, update the quantity
            mysqli_query($con, "UPDATE cart_items SET quantity = quantity + '$qty' WHERE user_id = '$uid' AND product_id = '$pid'");
        } else {
            // Product doesn't exist, insert a new record
            mysqli_query($con, "INSERT INTO cart_items (user_id, product_id, quantity) VALUES ('$uid', '$pid', '$qty')");
        }
    }
}


function update_product_in_cart($pid, $con, $action) {
    if(isset($_SESSION['USER_ID'])) {
        $uid = $_SESSION['USER_ID'];
        mysqli_query($con, "UPDATE cart_items SET quantity = quantity $action 1 WHERE user_id = '$uid' AND product_id = '$pid'");
    }
}

function remove_product_from_cart($pid, $con) {
    if(isset($_SESSION['USER_ID'])) {
        $uid = $_SESSION['USER_ID'];
        mysqli_query($con, "DELETE FROM cart_items WHERE user_id = '$uid' AND product_id = '$pid'");
    }
}

function empty_cart($con) {
    if(isset($_SESSION['USER_ID'])) {
        $uid = $_SESSION['USER_ID'];
        mysqli_query($con, "DELETE FROM cart_items WHERE user_id = '$uid'");
    }
}

function total_products_in_cart($con) {
    // session_start(); // Ensure session is started
    if(isset($_SESSION['USER_ID'])) {
         $uid = $_SESSION['USER_ID'];
        $result = mysqli_query($con, "SELECT COUNT(*) FROM cart_items WHERE user_id = '$uid'");
        if ($result) {
            $count = mysqli_fetch_array($result)[0];
            return $count;
        }
    }
    return 0;
}


// Usage example:
// $pid = 456;
// $qty = 1;
// add_product_to_cart($pid, $qty, $con);
// update_product_in_cart($pid, $con, '+');
// remove_product_from_cart($pid, $con);
// empty_cart($con);
$total_products = total_products_in_cart($con);
// echo "Total products in cart: $total_products";
?>
