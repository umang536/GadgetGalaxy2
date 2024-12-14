<?php require('top.inc.php'); ?>

<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-between align-items-center" style="padding-left: 1.2rem; padding-right: 1.2rem;">
                            <div>
                                <h4 class="box-title">Best Selling Products</h4>
                            </div>
                            <div>
                                <form method="post">
                                    <label for="limit">Enter limit:</label>
                                    <input type="number" id="limit" name="limit" value="<?php echo isset($_POST['limit']) ? $_POST['limit'] : ''; ?>" min="1">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Apply</button>
                                </form>
                            </div>
                            <div>
                                <form method="post" action="bestselling_pdf.php">
    <input type="hidden" name="limit" value="<?php echo isset($_POST['limit']) ? $_POST['limit'] : ''; ?>">
    <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Generate PDF</button>
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
                                        <th width="90%">Name</th>
                                        <th width="10%">Qty Sold</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['limit']) && $_POST['limit'] > 0) {
                                        $limit = $_POST['limit'];
                                        $sql = "SELECT product_id, SUM(qty) AS total_sold
                                                FROM order_detail
                                                GROUP BY product_id
                                                ORDER BY total_sold DESC
                                                LIMIT $limit"; // Limit to the specified number of best-selling products

                                        $result = mysqli_query($con, $sql);
                                        if ($result) {
                                            $i = 1;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $product_id = $row['product_id'];
                                                $product_sql = "SELECT name FROM product WHERE id = '$product_id'";
                                                $product_result = mysqli_query($con, $product_sql);
                                                $product_row = mysqli_fetch_assoc($product_result);
                                                $product_name = $product_row['name'];

                                                echo "<tr>";
                                                echo "<td class='serial'>$i</td>";
                                                echo "<td>$product_name</td>";
                                                echo "<td>{$row['total_sold']}</td>";
                                                echo "</tr>";
                                                $i++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='3'>Error fetching data: " . mysqli_error($con) . "</td></tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='3'>Please enter a valid limit.</td></tr>";
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