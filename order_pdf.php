<?php
include('vendor/autoload.php');
require('connection.inc.php');
require('functions.inc.php');

if(!$_SESSION['ADMIN_LOGIN']){
    if(!isset($_SESSION['USER_ID'])){
        die();
    }
}

$order_id = get_safe_value($con, $_GET['id']);

// Fetch order details
$html = '<style>
    .wishlist-table {
        width: 100%;
        border-collapse: collapse;
    }
    .wishlist-table th, .wishlist-table td {
        border: 1px solid #000;
        padding: 8px;
    }
</style>
<div class="wishlist-table table-responsive">
   <table class="wishlist-table">
      <thead>
         <tr>
            <th class="product-thumbnail">Product Name</th>
            <th class="product-thumbnail">Product Image</th>
            <th class="product-name">Qty</th>
            <th class="product-price">Price</th>
            <th class="product-price">Total Price</th>
         </tr>
      </thead>
      <tbody>';
        
if(isset($_SESSION['ADMIN_LOGIN'])){
    $res = mysqli_query($con, "SELECT DISTINCT(order_detail.id), order_detail.*, product.name, product.image FROM order_detail, product, `order` WHERE order_detail.order_id='$order_id' AND order_detail.product_id=product.id");
}else{
    $uid = $_SESSION['USER_ID'];
    $res = mysqli_query($con, "SELECT DISTINCT(order_detail.id), order_detail.*, product.name, product.image FROM order_detail, product, `order` WHERE order_detail.order_id='$order_id' AND `order`.user_id='$uid' AND order_detail.product_id=product.id");
}

$total_price = 0;
if(mysqli_num_rows($res) == 0){
    die();
}
while($row = mysqli_fetch_assoc($res)){
    $total_price = $total_price + ($row['qty'] * $row['price']);
    $pp = $row['qty'] * $row['price'];
    $html .= '<tr>
            <td class="product-name">'.$row['name'].'</td>
            <td class="product-name"> <img src="'.PRODUCT_IMAGE_SITE_PATH.$row['image'].'" style="height: 200px; width: auto;"></td>
            <td class="product-name">'.$row['qty'].'</td>
            <td class="product-name">'.$row['price'].'</td>
            <td class="product-name">'.$pp.'</td>
         </tr>';
}

// Calculate total price
$html .= '<tr>
                <td colspan="3"></td>
                <td class="product-name">Total Price</td>
                <td class="product-name">'.$total_price.'</td>
            </tr>';

$html .= '</tbody>
   </table>
</div>';

// Generate PDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$file = time().'.pdf';
$mpdf->Output($file, 'D');
?>
