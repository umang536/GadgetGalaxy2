<?php
require('connection.inc.php');
require('functions.inc.php');
// prx($_SERVER);
if(isset($_SESSION['ADMIN_LOGIN']) && $_SESSION['ADMIN_LOGIN']!=''){

}else{
	header('location:login.php');
	die();
}
?>
<!doctype html>
<html class="no-js" lang="">
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <link rel="icon" href="../images/logo/gg_logo.png" type="image/x-icon">
      <title>Dashboard Page</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="assets/css/normalize.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/font-awesome.min.css">
      <link rel="stylesheet" href="assets/css/themify-icons.css">
      <link rel="stylesheet" href="assets/css/pe-icon-7-filled.css">
      <link rel="stylesheet" href="assets/css/flag-icon.min.css">
      <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
      <link rel="stylesheet" href="assets/css/style.css">
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
   
        <style>
.dropdown-menu {
    margin-left: 20%;
    border: none;
  /*  max-width: 250px;  Set maximum width */
    width: 194px; /* Let the width adjust dynamically */
    max-height: 80vh; /* Set maximum height */
    overflow-y: visible; /* Ensure all content remains visible */
    padding-bottom: 80px;
    white-space: normal; /* Allow wrapping of long text */
}
</style>
   
   </head>
   <body>
      <aside id="left-panel" class="left-panel">
         <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
               <ul class="nav navbar-nav">
                  <li class="menu-title">Menu</li>
                  
				  <li class="menu-item-has-children dropdown">
                     <a href="product.php" > Product Master</a>
                  </li>
				  <li class="menu-item-has-children dropdown">
                     <?php 
					 if($_SESSION['ADMIN_ROLE']==1){
						echo '<a href="order_master_vendor.php" > Order Master</a>';
						echo '<a href="notification.php" > Notifications</a>';
						echo '<li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports</a>
                        <ul class="dropdown-menu">'
                           . // <li><a href="bestselling.php">Best Selling Products</a></li>.
                            '<li><a href="1_sales_by_category.php">Sales by Category</a></li>
                            <li><a href="5_inventory_status.php">Inventory Status</a></li>
                            <li><a href="7_sales_date.php">Oder History</a></li>
                            <!-- Add more dropdown items as needed -->
                        </ul>
                    </li>';
					 }else{
						echo '<a href="order_master.php" > Order Master</a>';
					 }
					 ?>
					 
					 
                  </li>
				  <?php if($_SESSION['ADMIN_ROLE']!=1){?>
				  <li class="menu-item-has-children dropdown">
                     <a href="product_review.php" > Product Review</a>
                  </li>
				  <!-- <li class="menu-item-has-children dropdown">
                     <a href="color.php" > Color Master</a>
                  </li>
				  <li class="menu-item-has-children dropdown">
                     <a href="size.php" > Size Master</a>
                  </li>-->
				  <li class="menu-item-has-children dropdown">
                     <a href="banner.php" > Banner</a>
                  </li> 
				   <li class="menu-item-has-children dropdown">
                     <a href="vendor_management.php" > Vendor Management</a>
                  </li>
				  <li class="menu-item-has-children dropdown">
                     <a href="categories.php" > Categories Master</a>
                  </li>
				  <li class="menu-item-has-children dropdown">
                     <a href="sub_categories.php" > Sub Categories Master</a>
                  </li>
                  
				  <li class="menu-item-has-children dropdown">
                     <a href="users.php" > User Master</a>
                  </li>
				  <!-- <li class="menu-item-has-children dropdown">
                     <a href="coupon_master.php" > Coupon Master</a>
                  </li> -->
				  <li class="menu-item-has-children dropdown">
                     <a href="contact_us.php" > Contact Us</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="notification.php" > Notifications</a>
                  </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports</a>
                        <ul class="dropdown-menu">
                            <li><a href="bestselling.php">Best Selling Products</a></li>
                            <li><a href="1_sales_by_category.php">Sales by Category</a></li>
                            <li><a href="3_sale_time.php">Sales by Time Period</a></li>
                            <li><a href="5_inventory_status.php">Inventory Status</a></li>
                            <li><a href="7_sales_date.php">Oder History</a></li>
                            <!-- Add more dropdown items as needed -->
                        </ul>
                    </li>
                  
				   
				  <?php } ?>
               </ul>
            </div>
         </nav>
      </aside>
      <div id="right-panel" class="right-panel">
         <header id="header" class="header">
            <div class="top-left">
               <div class="navbar-header">
                  <a class="navbar-brand" href="index.php"><img src="../images/logo/gg_logo.png" alt="Logo" style="width: 40px; height: 40px">GadgetGalaxy</a>
                  <!--<a class="navbar-brand" href="index.php"><img src="images/logo2.png" alt="Logo"></a>-->
                  <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
               </div>
            </div>
            <div class="top-right">
               <div class="header-menu">
                  <div class="user-area dropdown float-right">
                     <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welcome <?php echo $_SESSION['ADMIN_USERNAME']?></a>
                     <div class="user-menu dropdown-menu">
                        <a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i>Logout</a>
                     </div>
                  </div>
               </div>
            </div>
         </header>