<?php 
require('top.php');

// Check if the user is logged in
if(!isset($_SESSION['USER_LOGIN'])){
    ?>
    <script>
    window.location.href='index.php';
    </script>
    <?php
}

// Retrieve the user ID from the session
$uid = $_SESSION['USER_ID'];

// Construct SQL query with the user ID
$sql = "SELECT product.name, product.image, product.price, product.id AS pid, wishlist.id 
        FROM product 
        INNER JOIN wishlist ON wishlist.product_id = product.id 
        WHERE wishlist.user_id = '$uid' 
        GROUP BY product.id";
echo "SQL Query: $sql<br>"; // Print the SQL query
$res = mysqli_query($con, $sql);
?>

<div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/2.jpg) no-repeat scroll center center / cover ;">
    <!-- Bradcaump area content -->
</div>

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
                                    <th class="product-name">Remove</th>
                                    <th class="product-name"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while($row = mysqli_fetch_assoc($res)){
                                ?>
                                    <tr>
                                        <td class="product-thumbnail"><a href="#"><img src="<?php echo PRODUCT_IMAGE_SITE_PATH.$row['image']?>"  /></a></td>
                                        <td class="product-name"><a href="#"><?php echo $row['name']?></a>
                                            <ul  class="pro__prize">
                                                <li><?php echo $row['price']?></li>
                                            </ul>
                                        </td>
                                        
                                        <td class="product-remove"><a href="wishlist.php?wishlist_id=<?php echo $row['id']?>"><i class="icon-trash icons"></i></a></td>
                                        <td class="product-remove"><a href="javascript:void(0)" onclick="manage_cart('<?php echo $row['pid']?>','add')">Add to Cart</a></td>
                                    </tr>
                                <?php } ?>
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
        
<input type="hidden" id="qty" value="1"/>						
<?php require('footer.php')?>        
