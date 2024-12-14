<?php
require('top.inc.php');

// Initialize variables for date inputs and error message
$start_date = $end_date = $error = '';
$orders = [];

// Check if the user is an admin or a seller
$condition = '';
if ($_SESSION['ADMIN_ROLE'] == 1) {
    $condition = " AND product.added_by='" . $_SESSION['ADMIN_ID'] . "'";
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Retrieve start and end dates from form
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validate start and end dates
    if (!empty($start_date) && !empty($end_date)) {
        // Query to retrieve order details within the specified date range
        $sql = "SELECT `order`.*, product.name AS product_name, users.name AS user_name
        FROM `order`
        INNER JOIN order_detail ON `order`.id = order_detail.order_id
        INNER JOIN product ON order_detail.product_id = product.id
        INNER JOIN users ON `order`.user_id = users.id
        WHERE `order`.added_on BETWEEN '$start_date' AND '$end_date' $condition";

        // Execute the query
        $result = mysqli_query($con, $sql);

        // Check if any orders are found
        if ($result && mysqli_num_rows($result) > 0) {
            // Fetch order details
            $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $error = 'No orders found within the specified date range.';
        }
    } else {
        $error = 'Please enter both start and end dates.';
    }
}
?>

<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-between align-items-center" style="padding-left: 1.2rem; padding-right: 1.2rem;">
                            <div>
                                <h4 class="box-title">Order Details by Date Range</h4>
                            </div>
                            <div>
                                <form method="post">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="start_date">Start Date:</label>
                                            <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="end_date">End Date:</label>
                                            <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" name="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </form>
                            </div>

                            <div>
                                <!-- Button for generating PDF -->
                                <form method="post" action="8_order_history_pdf.php">
                                    <input type="hidden" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                                    <input type="hidden" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                                    <button type="submit" name="generate_pdf" class="btn btn-outline-success my-2 my-sm-0">Generate PDF</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body--">

                        <?php if (!empty($error)) { ?>
                            <p class="text-danger"><?php echo $error; ?></p>
                        <?php } ?>

                        <?php if (!empty($orders)) { ?>
                            <h2>Orders Placed Between <?php echo $start_date; ?> and <?php echo $end_date; ?></h2>
                            <div class="table-stats order-table ov-h">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Product Name</th>
                                            <th>User Name</th>
                                            <th>Order Date</th>
                                            <!-- Add more columns as needed -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order) { ?>
                                            <tr>
                                                <td><?php echo $order['id']; ?></td>
                                                <td><?php echo $order['product_name']; ?></td>
                                                <td><?php echo $order['user_name']; ?></td>
                                                <td><?php echo $order['added_on']; ?></td>
                                                <!-- Add more columns as needed -->
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('footer.inc.php'); ?>
