<?php
require('top.inc.php');

// echo "ADMIN_ROLE: " . $_SESSION['ADMIN_ROLE'] . "<br>";
// echo "ADMIN_ID: " . $_SESSION['ADMIN_ID'] . "<br>";
?>
<div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
			 <div class="card">
				<div class="card-body">
				   <h4 class="box-title">Dashboard </h4>
				</div>
			</div>
		  </div>
	   </div>
	</div>
	<?php if($_SESSION['ADMIN_ROLE'] == 0) { ?>
	<div class="row">
    	<div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body py-5">Total Users</div>
                <a href="users.php" class="custom-link">
                    <div class="card-footer d-flex">
                        View Details
                        <span class="pull-right">
                            <i class="glyphicon glyphicon-chevron-right"></i>
                        </span>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body py-5">Product</div>
                <a href="product.php" class="custom-link">
                    <div class="card-footer d-flex">
                        View Details
                        <span class="pull-right">
                            <i class="glyphicon glyphicon-chevron-right"></i>
                        </span>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body py-5">Order</div>
                <a href="order_master.php" class="custom-link">
                    <div class="card-footer d-flex">
                        View Details
                        <span class="pull-right">
                            <i class="glyphicon glyphicon-chevron-right"></i>
                        </span>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body py-5">Categories</div>
                <a href="categories.php" class="custom-link">
                    <div class="card-footer d-flex">
                        View Details
                        <span class="pull-right">
                            <i class="glyphicon glyphicon-chevron-right"></i>
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <?php if($_SESSION['ADMIN_ROLE'] == 1) { ?>
	<div class="row">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body py-5">Product</div>
                <a href="product.php" class="custom-link">
                    <div class="card-footer d-flex">
                        View Details
                        <span class="pull-right">
                            <i class="glyphicon glyphicon-chevron-right"></i>
                        </span>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body py-5">Order</div>
                <a href="order_master_vendor.php" class="custom-link">
                    <div class="card-footer d-flex">
                        View Details
                        <span class="pull-right">
                            <i class="glyphicon glyphicon-chevron-right"></i>
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<?php
require('footer.inc.php');
?>