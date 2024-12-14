<?php 
require('top.php');
?>

 <div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/2.jpg) no-repeat scroll center center / cover ;">
            <div class="ht__bradcaump__wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="bradcaump__inner">
                                <nav class="bradcaump-inner">
                                  <a class="breadcrumb-item" href="index.php">Home</a>
                                  <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                                  <span class="breadcrumb-item active">shopping cart</span>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Bradcaump area -->
        <!-- cart-main-area start -->
        <div class="cart-main-area ptb--100 bg__white">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <form action="#">               
                            <div class="table-content table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="product-thumbnail">products</th>
                                            <th class="product-name">name of products</th>
                                            <th class="product-price">Price</th>
                                            <th class="product-quantity">Quantity</th>
                                            <th class="product-subtotal">Total</th>
                                            <th class="product-remove">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Fetch cart items from the database and display them here
                                        // Replace this with your database query to fetch cart items
                                        $uid = $_SESSION['USER_ID'];
                                        $cart_items_query = mysqli_query($con, "SELECT * FROM cart_items WHERE user_id='$uid'");
                                        while ($row = mysqli_fetch_assoc($cart_items_query)) {
                                            // Retrieve product details for the current cart item
                                            $product_id = $row['product_id'];
                                            $product = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM product WHERE id = '$product_id'"));
                                            $product_name = $product['name'];
                                            $product_price = $product['price'];
                                            $product_image = $product['image'];
                                            $qty = $row['quantity'];
                                            ?>
                                            <tr>
                                                <td class="product-thumbnail"><a href="product.php?id=<?php echo $product_id?>"><img src="<?php echo PRODUCT_IMAGE_SITE_PATH . $product_image ?>" /></a></td>
                                                <td class="product-name"><a href="product.php?id=<?php echo $product_id?>"><?php echo $product_name ?></a></td>
                                                <td class="product-price"><span class="amount">&#8377; <?php echo $product_price ?></span></td>
<td class="tdy" data-label="quantity">
    <a style="color:black;margin-right:12px;" href="manage_qty.php?action=decrement&id=<?php echo $product_id; ?>">
        <label class="add ladd"><i style="padding: 4px; cursor: pointer;" class=" icon left  fa fa-minus"></i></label>
    </a>
    <input type="number" oninput="this.value = Math.abs(this.value)" id="<?php echo $product_id; ?>qty" min="1" value='<?php echo $qty; ?>' name="qty" style="width:100px;">
    <a style="color:black;margin-left:4px; cursor: pointer;" href="manage_qty.php?action=increment&id=<?php echo $product_id; ?>" >
        <label class="add radd"><i style="padding: 4px; cursor: pointer;" class="icon right  fa fa-plus"></i></label>
    </a><br>
    <a href="javascript:void(0);" onclick="updateQty(<?php echo $product_id; ?>)">Update</a>
</td>
<script>
function updateQty(productId) {
    var newQty = document.getElementById(productId + 'qty').value; // Get the new quantity from the input field
    window.location.href = 'manage_qty.php?action=update&id=' + productId + '&qty=' + newQty; // Redirect to the manage_qty.php script with the updated quantity
}
</script>


                                                <td class="product-subtotal">&#8377; <?php echo (int)$qty * (int)$product_price; ?></td>
                                                <td class="product-remove"><a href="javascript:void(0)" onclick="manage_cart_update('<?php echo $product_id ?>','remove')"><i class="icon-trash icons"></i></a></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="buttons-cart--inner">
                                        <div class="buttons-cart">
                                            <a href="<?php echo SITE_PATH?>">Continue Shopping</a>
                                        </div>
                                        <div class="buttons-cart checkout--btn">
                                            <a href="<?php echo SITE_PATH?>checkout.php">checkout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="sid">
        <input type="hidden" id="cid">
        
<div class="alert1 hide">
        <span class="fa fa-thumbs-up" style="font-size: 24px; color: #270;"></span>
        <span class="msg"></span> <!-- Leave it empty initially -->
        <div class="close-btn">
            <span class="fa fa-times"></span>
        </div>
    </div>
<?php require('footer.php')?>  
