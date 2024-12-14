<?php 
ob_start();
require('top.php');
if(isset($_GET['id'])){
    $product_id=mysqli_real_escape_string($con,$_GET['id']);
    if($product_id>0){
        $get_product=get_product($con,'','',$product_id);
        $seller_id = $get_product['0']['added_by'];
        // Get the seller's name
        $seller_res = mysqli_query($con, "SELECT username FROM admin_users WHERE id = '$seller_id'");
        $seller_name = "";
        if(mysqli_num_rows($seller_res) > 0) {
            $seller_row = mysqli_fetch_assoc($seller_res);
            $seller_name = $seller_row['username'];
        }
    } else {
        ?>
        <script>
            window.location.href='index.php';
        </script>
        <?php
    }
    
    $resMultipleImages=mysqli_query($con,"select product_images from product_images where product_id='$product_id'");
    $multipleImages=[];
    if(mysqli_num_rows($resMultipleImages)>0){
        while($rowMultipleImages=mysqli_fetch_assoc($resMultipleImages)){
            $multipleImages[]=$rowMultipleImages['product_images'];
        }
    }
    
    
} else {
    ?>
    <script>
        window.location.href='index.php';
    </script>
    <?php
}

if(isset($_POST['review_submit'])){
    $rating=get_safe_value($con,$_POST['rating']);
    $review=get_safe_value($con,$_POST['review']);
    
    $added_on=date('Y-m-d h:i:s');
    mysqli_query($con,"insert into product_review(product_id,user_id,rating,review,status,added_on) values('$product_id','".$_SESSION['USER_ID']."','$rating','$review','1','$added_on')");
    header('location:product.php?id='.$product_id);
    die();
}

$product_review_res=mysqli_query($con,"select users.name,product_review.id,product_review.rating,product_review.review,product_review.added_on from users,product_review where product_review.status=1 and product_review.user_id=users.id and product_review.product_id='$product_id' order by product_review.added_on desc");

$product_qty_res = mysqli_query($con, "SELECT qty FROM product WHERE id = '$product_id'");
if(mysqli_num_rows($product_qty_res) > 0) {
    $product_qty_row = mysqli_fetch_assoc($product_qty_res);
    $max_qty = $product_qty_row['qty']-productSoldQtyByProductId($con,$get_product['0']['id']);
    
    // echo $productSoldQtyByProductId = productSoldQtyByProductId($con,$get_product['0']['id']);
} else {
    // Handle if product not found
    $max_qty = 0;
}

?>

<!-- Start Bradcaump area -->
<div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/2.jpg) no-repeat scroll center center / cover ;">
    <div class="ht__bradcaump__wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="bradcaump__inner">
                        <nav class="bradcaump-inner">
                          <a class="breadcrumb-item" href="index.php">Home</a>
                          <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                          <a class="breadcrumb-item" href="categories.php?id=<?php echo $get_product['0']['categories_id']?>"><?php echo $get_product['0']['categories']?></a>
                          <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                          <span class="breadcrumb-item active"><?php echo $get_product['0']['name']?></span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Bradcaump area -->

<!-- Start Product Details Area -->
<section class="htc__product__details bg__white ptb--100">
    <!-- Start Product Details Top -->
    <div class="htc__product__details__top">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-lg-5 col-sm-12 col-xs-12">
                    <div class="htc__product__details__tab__content">
                        <!-- Start Product Big Images -->
                        <div class="product__big__images">
                            <div class="portfolio-full-image tab-content">
                                <div role="tabpanel" class="tab-pane fade in active imageZoom" id="img-tab-1">
                                    <img  width="" data-origin="<?php echo PRODUCT_IMAGE_SITE_PATH.$get_product['0']['image']?>" src="<?php echo PRODUCT_IMAGE_SITE_PATH.$get_product['0']['image']?>">
                                </div>
                                
                                <?php if(isset($multipleImages[0])){?>
                                <div id="multiple_images">
                                    <?php
                                    foreach($multipleImages as $list){
                                        echo "<img src='".PRODUCT_MULTIPLE_IMAGE_SITE_PATH.$list."' onclick=showMultipleImage('".PRODUCT_MULTIPLE_IMAGE_SITE_PATH.$list."')>";
                                    }
                                    ?>
                                    
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- End Product Big Images -->
                        
                    </div>
                </div>
                <div class="col-md-7 col-lg-7 col-sm-12 col-xs-12 smt-40 xmt-40">
                    <div class="ht__product__dtl">
                        <h2><?php echo $get_product['0']['name']?></h2>
                        <p>Seller: <?php echo $seller_name; ?></p> <!-- Display seller's name -->
                        <ul  class="pro__prize">
                            <li class="new__price">&#8377; <?php echo $get_product['0']['price']?></li>
                        </ul>
                        <div class="ht__pro__desc">
                            <?php 
                            ?>
                        
                            <div class="sin__desc">
                                <?php
                                    // echo $productSoldQtyByProductId = productSoldQtyByProductId($con,$get_product['0']['id']);
                                    
                                ?>
                            </div>
                            
                            
                            
                            <div class="sin__desc align--left">
                                <p><span>Qty:</span> 
                                    <input type="number" id="qty" name="qty" min="1" max="<?php echo $max_qty; ?>" value="1">
                                </p>
                                <!-- Add an error message element below the quantity input field -->
                                <span id="qty-error" class="error-message" style="color: red;"></span>
                            </div>
                            
                            <div id="cart_attr_msg"></div>
                            
                            <div class="sin__desc align--left">
                                <p><span>Categories:</span></p>
                                <ul class="pro__cat__list">
                                    <li><a href="#"><?php echo $get_product['0']['categories']?></a></li>
                                </ul>
                            </div>
                            
                            </div>
                            
                        </div>
                        
                        <div id="is_cart_box_show">
                        
                            <a class="fr__btn" href="<?php echo isset($_SESSION['USER_LOGIN']) ? 'javascript:void(0)' : 'login.php' ?>" onclick="<?php echo isset($_SESSION['USER_LOGIN']) ? "if(validateQuantity()) manage_cart('".$get_product['0']['id']."','add')" : '' ?>">Add to Cart</a>

                            <a class="fr__btn buy_now" href="<?php echo isset($_SESSION['USER_LOGIN']) ? 'javascript:void(0)' : 'login.php' ?>" onclick="<?php echo isset($_SESSION['USER_LOGIN']) ? "if(validateQuantity()) manage_cart('".$get_product['0']['id']."','add','yes')" : '' ?>">Buy Now</a>


                        
                        </div>
                        
                        <div id="social_share_box">
                            <a href="https://facebook.com/share.php?u=<?php echo $meta_url?>"><img src='images/icons/facebook.png'/></a>
                            <a href="https://twitter.com/share?text=<?php echo $get_product['0']['name']?>&url=<?php echo $meta_url?>"><img src='images/icons/twitter.png'/></a>
                            <a href="https://api.whatsapp.com/send?text=<?php echo $get_product['0']['name']?> <?php echo $meta_url?>"><img src='images/icons/whatsapp.png'/></a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- End Product Details Top -->
</section>
<!-- End Product Details Area 
Start Product Description -->
<section class="htc__produc__decription bg__white">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <!-- Start List And Grid View -->
                <ul class="pro__details__tab" role="tablist">
                    <li role="presentation" class="description active"><a href="#description" role="tab" data-toggle="tab">description</a></li>
                    <li role="presentation" class="review"><a href="#review" role="tab" data-toggle="tab" class="active show" aria-selected="true">review</a></li>
                </ul>
                <!-- End List And Grid View -->
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="ht__pro__details__content">
                    <!-- Start Single Content -->
                    <div role="tabpanel" id="description" class="pro__single__content tab-pane fade in active">
                        <div class="pro__tab__content__inner">
                            <?php echo $get_product['0']['description']?>
                        </div>
                    </div>
                    <!-- End Single Content -->
                    
                    <div role="tabpanel" id="review" class="pro__single__content tab-pane fade active show">
                        <div class="pro__tab__content__inner">
                            <?php 
                            if(mysqli_num_rows($product_review_res)>0){
                            
                            while($product_review_row=mysqli_fetch_assoc($product_review_res)){
                            ?>
                            
                            <article class="row">
                                <div class="col-md-12 col-sm-12">
                                  <div class="panel panel-default arrow left">
                                    <div class="panel-body">
                                      <header class="text-left">
                                        <div><span class="comment-rating"> <?php echo $product_review_row['rating']?></span> (<?php echo $product_review_row['name']?>)</div>
                                        <time class="comment-date"> 
                                        <?php
                                        $added_on=strtotime($product_review_row['added_on']);
                                        echo date('d M Y',$added_on);
                                        ?>
                                        
                                        
                                        
                                        </time>
                                      </header>
                                      <div class="comment-post">
                                        <p>
                                          <?php echo $product_review_row['review']?>
                                        </p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </article>
                            <?php } }else { 
                                echo "<h3 class='submit_review_hint'>No review added</h3><br/>";
                            }
                            ?>
                            
                            
                            <h3 class="review_heading">Enter your review</h3><br/>
                            <?php
                            if(isset($_SESSION['USER_LOGIN'])){
                            ?>
                            <div class="row" id="post-review-box" style=>
                               <div class="col-md-12">
                                  <form action="" method="post">
                                     <select class="form-control" name="rating" required>
                                          <option value="">Select Rating</option>
                                          <option>Worst</option>
                                          <option>Bad</option>
                                          <option>Good</option>
                                          <option>Very Good</option>
                                          <option>Fantastic</option>
                                     </select>  <br/>
                                     <textarea class="form-control" cols="50" id="new-review" name="review" placeholder="Enter your review here..." rows="5"></textarea>
                                     <div class="text-right mt10">
                                        <button class="btn btn-success btn-lg" type="submit" name="review_submit">Submit</button>
                                     </div>
                                  </form>
                               </div>
                            </div>
                            <?php } else {
                                echo "<span class='submit_review_hint'>Please <a href='login.php'>login</a> to submit your review</span>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Product Description -->
<?php
        if(isset($_COOKIE['recently_viewed'])){
            $arrRecentView=unserialize($_COOKIE['recently_viewed']);
            $countRecentView=count($arrRecentView);
            $countStartRecentView=$countRecentView-4;
            if($countStartRecentView>4){
                $arrRecentView=array_slice($arrRecentView,$countStartRecentView,4);
            }
            $recentViewId=implode(",",$arrRecentView);
            $res=mysqli_query($con,"select * from product where id IN ($recentViewId) and status=1");
            
        ?>
        <section class="htc__produc__decription bg__white">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h3 style="font-size: 20px;font-weight: bold;">Recently Viewed</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="ht__pro__details__content">
                            <div class="row">
                                <?php while($list=mysqli_fetch_assoc($res)){?>
                                <div class="col-md-4 col-lg-3 col-sm-4 col-xs-12" style="height: 450px;">
                                    <div class="category">
                                                <div class="ht__cat__thumb">
                                                    <a href="product.php?id=<?php echo $list['id']?>">
                                                        <img src="<?php echo PRODUCT_IMAGE_SITE_PATH.$list['image']?>" alt="product images" style="height: 200px; overflow: hidden; width: auto; display: block; margin: 0 auto;">
                                                    </a>
                                                </div>
                                                <div class="fr__hover__info">
                                                    <ul class="product__action">
                                                        <li><a href="javascript:void(0)" onclick="wishlist_manage('<?php echo $list['id']?>','add')"><i class="icon-heart icons"></i></a></li>
											<!-- Updated "Add to Cart" link inside the product loop -->
<li><a href="<?php echo isset($_SESSION['USER_LOGIN']) ? 'javascript:void(0)' : 'login.php' ?>" onclick="<?php echo isset($_SESSION['USER_LOGIN']) ? "manage_cart('".$list['id']."','add')" : '' ?>"><i class="icon-handbag icons"></i></a></li>
                                                    </ul>
                                                </div>
                                                <div class="fr__product__inner">
                                                    <h4><a href="product.php?id=<?php echo $list['id']?>"><?php echo $list['name']?></a></h4>
                                                    <ul class="fr__pro__prize">
                                                        <li class="new__price"><?php echo $list['price']?></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alert1 hide">
        <span class="fa fa-thumbs-up" style="font-size: 24px; color: #270;"></span>
        <span class="msg"></span> <!-- Leave it empty initially -->
        <div class="close-btn">
            <span class="fa fa-times"></span>
        </div>
    </div>
        </section>
        <?php 
            $arrRec=unserialize($_COOKIE['recently_viewed']);
            if(($key=array_search($product_id,$arrRec))!==false){
                unset($arrRec[$key]);
            }
            $arrRec[]=$product_id;
        }else{
            $arrRec[]=$product_id;
        }
        setcookie('recently_viewed',serialize($arrRec),time()+60*60*24*365);
        ?>

<?php 
require('footer.php');
ob_flush();
?>  

<script>
    // Function to validate quantity
    function validateQuantity() {
        console.log('validateQuantity() called');
        var qty = document.getElementById('qty').value;
        var max_qty = <?php echo $max_qty; ?>;
        console.log('Qty:', qty);
        console.log('Max Qty:', max_qty);
        
        // Check if the entered quantity is greater than the available stock
        if (parseInt(qty) > max_qty) {
            document.getElementById('qty-error').innerHTML = "Stock not available";
        
            // Insert notification into the notifications table
            var message = "Stock not available for product <?php echo $get_product['0']['name']?>";
            var sellerId = <?php echo $seller_id; ?>;
            var productId = <?php echo $product_id; ?>;
            insertNotification(sellerId, productId, message);
        
            return false;
        }
        
        // If quantity is within the available stock, clear any previous error messages
        document.getElementById('qty-error').innerHTML = "";
        return true;
    }

    // Function to insert notification via AJAX
    function insertNotification(sellerId, productId, message) {
        console.log('Inserting notification:', message);
        $.ajax({
            type: 'POST',
            url: 'insert_notification.php', // Create a separate PHP file for handling insertion
            data: { seller_id: sellerId, product_id: productId, message: message },
            success: function(response) {
                console.log('Notification inserted successfully');
            },
            error: function(xhr, status, error) {
                console.error('Error inserting notification:', error);
            }
        });
    }
</script> 
