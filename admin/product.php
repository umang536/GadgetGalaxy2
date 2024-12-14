<?php
require('top.inc.php');

$condition = '';
$condition1 = '';
if ($_SESSION['ADMIN_ROLE'] == 1) {
    $condition = " AND product.added_by='" . $_SESSION['ADMIN_ID'] . "'";
    $condition1 = " AND added_by='" . $_SESSION['ADMIN_ID'] . "'";
}

if (isset($_GET['type']) && $_GET['type'] != '') {
    $type = get_safe_value($con, $_GET['type']);
    if ($type == 'status') {
        $operation = get_safe_value($con, $_GET['operation']);
        $id = get_safe_value($con, $_GET['id']);
        $status = ($operation == 'active') ? '1' : '0';
        $update_status_sql = "UPDATE product SET status='$status' $condition1 WHERE id='$id'";
        mysqli_query($con, $update_status_sql);
    }

    if ($type == 'delete') {
        $id = get_safe_value($con, $_GET['id']);
        $delete_sql = "DELETE FROM product WHERE id='$id' $condition1";
        mysqli_query($con, $delete_sql);   
    }
}

$searchQuery = '';
if (isset($_POST['search'])) {
    $searchQuery = get_safe_value($con, $_POST['search']);
}

$sql = "SELECT product.*, categories.categories, admin_users.username AS seller_name 
        FROM product 
        LEFT JOIN categories ON product.categories_id = categories.id 
        LEFT JOIN admin_users ON product.added_by = admin_users.id 
        WHERE (product.name LIKE '%$searchQuery%' OR 
               product.description LIKE '%$searchQuery%' OR 
               categories.categories LIKE '%$searchQuery%' OR 
               admin_users.username LIKE '%$searchQuery%') $condition
        ORDER BY product.id DESC";

$res = mysqli_query($con, $sql);
?>

<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body" >
                        <div class="row d-flex justify-content-between align-items-center" style="padding-left: 1.2rem; padding-right: 1.2rem;">
                            <div >
                                <h4 class="box-title">Products </h4>
                                <h4 class="box-link"><a href="manage_product.php">Add Product</a> </h4>
                            </div>
                            <div >
                                <form method="post" class="form-inline my-2 my-lg-0">
                                    <input class="form-control mr-sm-2" type="text" name="search" placeholder="Search" aria-label="Search" value="<?php echo $searchQuery; ?>">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body--">
                        <div class="table-stats order-table ov-h">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="serial">#</th>
                                        <th width="2%">ID</th>
                                        <th width="10%">Categories</th>
                                        <th width="30%">Name</th>
                                        <!--<th width="20%">Description</th>-->
                                        <th width="10%">Image</th>
                                        <th width="10%">Seller</th>
                                        <!--<th width="10%">Brand</th>-->
                                        <th width="5%">Price</th>
                                        <th width="15%">Qty</th>
                                        <th width="20%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    while ($row = mysqli_fetch_assoc($res)) {
                                    ?>
                                        <tr>
                                            <td class="serial"><?php echo $i ?></td>
                                            <td><?php echo $row['id'] ?></td>
                                            <td><?php echo $row['categories'] ?></td>
                                            <td><?php echo $row['name'] ?></td>
                                            <td><img src="<?php echo PRODUCT_IMAGE_SITE_PATH . $row['image'] ?>" /></td>
                                            <td><?php echo $row['seller_name'] ?></td>
                                            <td><?php echo $row['price'] ?></td>
                                            <td>Total Qty.:<?php echo $row['qty'] ?>
                                            <br>
                                            <?php 
                                                $productSoldQtyByProductId = productSoldQtyByProductId($con,$row['id']);
                                                $available_qty = $row['qty'] - $productSoldQtyByProductId;
                                            ?>
                                            Available Qty.:<?php echo $available_qty ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['status'] == 1) {
                                                    echo "<span class='badge badge-complete'><a href='?type=status&operation=deactive&id=" . $row['id'] . "'>Active</a></span>&nbsp;";
                                                } else {
                                                    echo "<span class='badge badge-pending'><a href='?type=status&operation=active&id=" . $row['id'] . "'>Deactive</a></span>&nbsp;";
                                                }
                                                echo "<span class='badge badge-edit'><a href='manage_product.php?id=" . $row['id'] . "'>Edit</a></span>&nbsp;";
                                                echo "<span class='badge badge-delete'><a href='?type=delete&id=" . $row['id'] . "'>Delete</a></span>";
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                    ?>
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
echo $sql;
require('footer.inc.php');
?>
