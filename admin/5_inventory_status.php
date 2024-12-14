<?php
require('top.inc.php');

// Check if the user is an admin or a seller
$condition = '';
if ($_SESSION['ADMIN_ROLE'] == 1) {
    $condition = " AND product.added_by='" . $_SESSION['ADMIN_ID'] . "'";
}

// Query to get current inventory levels for each product
$sql = "SELECT *
        FROM product
        WHERE 1 $condition";

// Execute the query and fetch results
$result = mysqli_query($con, $sql);
?>

<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-between align-items-center" style="padding-left: 1.2rem; padding-right: 1.2rem;">
                            <div>
                                <h4 class="box-title">Inventory Status</h4>
                            </div>
                            <div>
                                <!-- Button for generating PDF -->
                                <form method="post" action="6_inverntory_status_pdf.php">
                                    <button type="submit" name="generate_pdf" class="btn btn-outline-success my-2 my-sm-0">Generate PDF</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body--">
                        <!-- Display inventory status -->
                        <div class="table-stats order-table ov-h">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="serial">#</th>
                                        <th width="15%">Product ID</th>
                                        <th width="65%">Name</th>
                                        <th width="20%">Quantity in Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        $i = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Calculate the available quantity inside the loop
                                            $productSoldQtyByProductId = productSoldQtyByProductId($con, $row['id']);
                                            $available_qty = $row['qty'] - $productSoldQtyByProductId;

                                            echo "<tr>";
                                            echo "<td class='serial'>$i</td>";
                                            echo "<td>{$row['id']}</td>";
                                            echo "<td>{$row['name']}</td>";
                                            echo "<td>{$available_qty}</td>"; // Display the available quantity
                                            echo "</tr>";
                                            $i++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No inventory data available</td></tr>";
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

<?php require('footer.inc.php'); ?>
