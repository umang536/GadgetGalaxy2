<?php
include('top.inc.php');

// Default month (optional)
$selectedMonth = isset($_POST['selected_month']) ? $_POST['selected_month'] : date('Y-m');

// Extract year and month from the selected month
list($year, $month) = explode('-', $selectedMonth);

// Query to get sales trends for the selected month
$sql = "SELECT DAY(`order`.added_on) AS day, SUM(order_detail.price) AS total_sales
        FROM `order`
        INNER JOIN order_detail ON `order`.id = order_detail.order_id
        WHERE YEAR(`order`.added_on) = '$year' AND MONTH(`order`.added_on) = '$month'
        GROUP BY DAY(`order`.added_on)";

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
                                <h4 class="box-title">Sales by Month</h4>
                            </div>
                            <div style="width: 50%;">
                                <form method="post" action="">
                                    <div class="form-group">
                                        <label for="selected_month">Select Month:</label>
                                        <input type="month" id="selected_month" name="selected_month" value="<?php echo $selectedMonth; ?>" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                            <div>
                                <form action="4_sale_time_pdf.php" method="post" target="_blank">
                                    <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Generate PDF</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body--">
                       

                        <!-- Display sales report -->
                        <div class="table-stats order-table ov-h">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="serial">#</th>
                                        <th>Day</th>
                                        <th>Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result) {
                                        $i = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td class='serial'>$i</td>";
                                            echo "<td>{$row['day']}</td>";
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
