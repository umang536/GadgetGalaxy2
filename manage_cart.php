<?php
require('connection.inc.php');
require('functions.inc.php');
require('add_to_cart.inc.php');

$pid = get_safe_value($con, $_POST['pid']);
$qty = get_safe_value($con, $_POST['qty']);
$type = get_safe_value($con, $_POST['type']);
$uid = $_SESSION['USER_ID'];

if ($type == 'add') {
    add_product_to_cart($pid, $qty, $con);
}

if ($type == 'remove') {
    remove_product_from_cart($pid, $con);
}

if ($type == 'update') {
    update_product_in_cart($pid, $qty, $con);
}

echo total_products_in_cart($con);
?>
