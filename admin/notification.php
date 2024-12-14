<?php
require('top.inc.php');
date_default_timezone_set('Asia/Kolkata');
// Check if the user is admin or seller
$condition='';
if($_SESSION['ADMIN_ROLE']==1){
	$condition=" AND added_by='".$_SESSION['ADMIN_ID']."'";
}

if(isset($_GET['type']) && $_GET['type']!=''){
	$type = get_safe_value($con,$_GET['type']);
	if($type == 'delete'){
		$id = get_safe_value($con,$_GET['id']);
		$delete_sql = "DELETE FROM notifications WHERE id='$id'$condition";
		mysqli_query($con,$delete_sql);
	}
}

// Fetch notifications with related seller, product, and quantity information
$notification_sql = "SELECT n.*, s.username AS seller_name, p.name AS product_name, p.qty AS product_quantity
                     FROM notifications n
                     LEFT JOIN admin_users s ON n.seller_id = s.id
                     LEFT JOIN product p ON n.product_id = p.id
                     WHERE 1 $condition
                     ORDER BY n.created_at DESC";
$notification_res = mysqli_query($con, $notification_sql);
?>
<div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
			 <div class="card">
				<div class="card-body">
				   <h4 class="box-title">Notifications </h4>
				</div>
				<div class="card-body--">
				   <div class="table-stats order-table ov-h">
					  <table class="table ">
						 <thead>
							<tr>
							   <th class="serial">#</th>
							   <th>Message</th>
							   <th>Seller</th>
							   <th>Product</th>
							   <th>Quantity</th>
							   <th>Date</th>
							   <th></th>
							</tr>
						 </thead>
						 <tbody>
							<?php 
							$i=1;
							while($notification_row = mysqli_fetch_assoc($notification_res)){ ?>
							<tr>
							   <td class="serial"><?php echo $i?></td>
							   <td><?php echo $notification_row['message']?></td>
							   <td><?php echo $notification_row['seller_name']?></td>
							   <td><?php echo $notification_row['product_name']?></td>
							   <td><?php echo $notification_row['product_quantity']?></td>
							   <td><?php echo $notification_row['created_at']; ?></td>
							   <td>
								<?php
								echo "<span class='badge badge-delete'><a href='?type=delete&id=".$notification_row['id']."'>Delete</a></span>";
								?>
							   </td>
							</tr>
							<?php 
							$i++;
							} ?>
						 </tbody>
					  </table>
				   </div>
				</div>
			 </div>
		  </div>
	   </div>
	</div>
</div>
<?php
require('footer.inc.php');
?>
