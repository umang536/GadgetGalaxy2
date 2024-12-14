<?php
include('top.inc.php');

// Query to get sales by category
$sql ="SELECT categories.categories, SUM(order_detail.price) AS total_sales
FROM order_detail
JOIN product ON order_detail.product_id = product.id
JOIN categories ON product.categories_id = categories.id
GROUP BY categories.categories";


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
                                <h4 class="box-title">Sales by Category</h4>
                            </div>
                            <div>
                                <form action="2_generate_sales_by_cat_pdf.php" method="post" target="_blank">
                                    <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Generate PDF</button>
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
                                        <th width="90%">Category</th>
                                        <th width="10%">Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result) {
                                        $i = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td class='serial'>$i</td>";
                                            echo "<td>{$row['categories']}</td>";
                                            echo "<td>{$row['total_sales']}</td>";
                                            echo "</tr>";
                                            $i++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='3'>Error fetching data: " . mysqli_error($con) . "</td></tr>";
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

<?php include('footer.inc.php'); ?>
