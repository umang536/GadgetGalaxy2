<?php 
require('top.php');
$errMsg="";
$qty = '';
$address='';
$city='';
$pincode='';

// Ensure user is logged in
if (!isset($_SESSION['USER_ID'])) {
    header("Location: login.php");
    exit();
}

// Fetch cart items from the database
$user_id = $_SESSION['USER_ID'];
$cartQuery = "SELECT * FROM cart_items WHERE user_id = $user_id";
$cartResult = mysqli_query($con, $cartQuery);

if (!$cartResult || mysqli_num_rows($cartResult) == 0) {
    // Redirect to index page if cart is empty?>
    <script>
            window.location.href='index.php';
        </script>
        <?php
}

if(isset($_POST['submit'])){
    $address=get_safe_value($con,$_POST['address']);
    $city=get_safe_value($con,$_POST['city']);
    $pincode=get_safe_value($con,$_POST['pincode']);
    $payment_type=get_safe_value($con,$_POST['payment_type']);
    
    $categoryDefaults = array(
        1 => array('length' => 15, 'breadth' => 10, 'height' => 5, 'weight' => 0.5), // Category ID 1: Headphones
        2 => array('length' => 25, 'breadth' => 20, 'height' => 15, 'weight' => 0.8), // Category ID 1: Headphones
        3 => array('length' => 35, 'breadth' => 25, 'height' => 5, 'weight' => 2), // Category ID 1: Headphones
        4 => array('length' => 20, 'breadth' => 15, 'height' => 10, 'weight' => 0.7), // Category ID 1: Headphones
        // Add more category IDs and their default values as needed
    );
    
    $cart_total = 0;
    while ($cartRow = mysqli_fetch_assoc($cartResult)) {
        $productId = $cartRow['product_id'];
        $qty = $cartRow['quantity'];
        $resProduct = mysqli_fetch_assoc(mysqli_query($con,"select price,categories_id from product where id='$productId'"));
        $price = $resProduct['price'];
        $cart_total += $price * $qty;
        $catId = $resProduct['categories_id'];
        if (isset($categoryDefaults[$catId])) {
            $defaultValues = $categoryDefaults[$catId];
            $length = $defaultValues['length'];
            $breadth = $defaultValues['breadth'];
            $height = $defaultValues['height'];
            $weight = $defaultValues['weight'];
        } else {
            // If no default values are defined for the category, set them to default values
            $length = 0;
            $breadth = 0;
            $height = 0;
            $weight = 0;
        }
    }
    
    $total_price=$cart_total;
    if($payment_type=='razorpay'){
        $payment_status='success';
    }
    if($payment_type=='cod'){
        $payment_status='pending';
    }
    $order_status='1';
    $added_on=date('Y-m-d h:i:s');
    
    $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
    
    mysqli_query($con,"insert into `order`(user_id,address,city,pincode,payment_type,payment_status,order_status,length,breadth,height,weight,added_on,total_price,txnid) values('$user_id','$address','$city','$pincode','$payment_type','$payment_status','$order_status','$length','$breadth','$height','$weight','$added_on','$total_price','$txnid')");
    
    $order_id=mysqli_insert_id($con);
    
    // Insert order details
    mysqli_data_seek($cartResult, 0); // Reset result pointer
    while ($cartRow = mysqli_fetch_assoc($cartResult)) {
        $productId = $cartRow['product_id'];
        $qty = $cartRow['quantity'];
        $resProduct = mysqli_fetch_assoc(mysqli_query($con,"select price from product where id='$productId'"));
        $price = $resProduct['price'];

        mysqli_query($con,"insert into `order_detail`(order_id,product_id,qty,price) values('$order_id','$productId','$qty','$price')");
    }
    
    if($payment_type=='razorpay') {
        
    
        // Handle Razorpay payment processing
        // You can customize this part based on your Razorpay integration
        
        // For now, let's assume the payment is successful and redirect to thank you page
        ?>
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            var options = {
                "key": "rzp_test_gHbd77FsBOTBWc",
                "amount": <?php echo $total_price * 100; ?>, // amount in the smallest currency unit
                "currency": "INR",
                "name": "GadgetGalaxy",
                "description": "Payment for Order",
                "image": "images/logo/gg_logo.png",
                "handler": function (response){
                    alert("Payment successful. Razorpay Payment ID: " + response.razorpay_payment_id);
                    window.location.href = 'thank_you.php';
                },
                "prefill": {
                    "name": "Your Name",
                    "email": "your_email@example.com",
                    "contact": "9999999999"
                },
                "notes": {
                    "address": "Razorpay Corporate Office"
                },
                "theme": {
                    "color": "#F37254"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
            e.preventDefault();
        </script>
        <?php
        sentInvoice($con, $order_id);
    } elseif ($payment_type=='cod') {
        
        ?>
        <script>
            window.location.href='thank_you.php';
        </script>
        <?php
        mysqli_query($con, "DELETE FROM cart_items WHERE user_id = $user_id");
    sentInvoice($con, $order_id);
    }   
        mysqli_query($con, "DELETE FROM cart_items WHERE user_id = $user_id");
}
?>

<!-- Rest of your HTML and PHP code remains unchanged -->


<!-- Rest of your HTML and PHP code remains unchanged -->


<!-- Rest of your HTML and PHP code remains unchanged -->


<div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/2.jpg) no-repeat scroll center center / cover ;">
            <div class="ht__bradcaump__wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="bradcaump__inner">
                                <nav class="bradcaump-inner">
                                  <a class="breadcrumb-item" href="index.php">Home</a>
                                  <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                                  <span class="breadcrumb-item active">checkout</span>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Bradcaump area -->
        <!-- cart-main-area start -->
        <div class="checkout-wrap ptb--100">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <?php echo $errMsg?>
                        <div class="checkout__inner">
                            <div class="accordion-list">
                                <div class="accordion">
                                    <div class="accordion__title">
                                        Address Information
                                    </div>
                                    <form method="post">
                                        <div class="accordion__body">
                                            <div class="bilinfo">
                                                
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="single-input">
                                                                <input type="text" name="address" placeholder="Street Address" required value="<?php echo $address?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="single-input">
                                                                <input type="text" name="city" placeholder="City/State" required value="<?php echo $city?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="single-input">
                                                                <input type="text" name="pincode" placeholder="Post code/ zip" required value="<?php echo $pincode?>">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                
                                            </div>
                                        </div>
                                        <div class="accordion__title">
                                            payment information
                                        </div>
                                        <div class="accordion__body">
                                            <div class="paymentinfo">
                                                <div class="single-method">
                                                    COD <input type="radio" name="payment_type" value="cod" required/>
                                                    &nbsp;&nbsp;RazorPay <input type="radio" name="payment_type" value="razorpay" id="rzp-button" />
                                                </div>
                                                <div class="single-method">
                                                  
                                                </div>
                                            </div>
                                        </div>
                                         <input type="submit" name="submit" class="fv-btn"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="order-details">
                            <h5 class="order-details__title">Your Order</h5>
                            <div class="order-details__item">
<?php
$cart_total = 0;
$user_id = $_SESSION['USER_ID'];
$cartQuery = "SELECT * FROM cart_items WHERE user_id = $user_id";
$cartResult = mysqli_query($con, $cartQuery);

if (!$cartResult || mysqli_num_rows($cartResult) == 0) {
    echo "Your cart is empty.";
} else {
    while ($cartRow = mysqli_fetch_assoc($cartResult)) {
        $productId = $cartRow['product_id'];
        $qty = $cartRow['quantity'];
        $productQuery = "SELECT * FROM product WHERE id = $productId";
        $productResult = mysqli_query($con, $productQuery);
        if ($productResult && mysqli_num_rows($productResult) > 0) {
            $product = mysqli_fetch_assoc($productResult);
            $pname = $product['name'];
            $price = $product['price'];
            $image = $product['image'];
            $catId = $product['categories_id'];
            // Display product image, name, quantity, and price
            echo "<img src='media/product/$image' alt='Product Image' style='max-width: 50px;'> ";
            echo "Product Name: $pname, Quantity: $qty, Price: &#8377; $price<br>";
            // Fetch and display the category name
            $categoryQuery = "SELECT categories FROM categories WHERE id = $catId";
            $categoryResult = mysqli_query($con, $categoryQuery);
            if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
                $categoryRow = mysqli_fetch_assoc($categoryResult);
                $categoryName = $categoryRow['categories'];
                echo "Category: $categoryName<br>";
            } else {
                echo "Category: Not Found<br>";
            }
            // Calculate total price for each product
            $product_total = $price * $qty;
            $cart_total += $product_total;
        }
    }
    // Display total price of all products
    echo "<strong>Total Price: &#8377; $cart_total</strong><br>";
}
?>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php 
require('footer.php');
?>




