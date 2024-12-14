<?php
require('../vendor/autoload.php');
require('connection.inc.php');

$condition = '';
if ($_SESSION['ADMIN_ROLE'] == 1) {
    $condition = " AND product.added_by='" . $_SESSION['ADMIN_ID'] . "'";
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_pdf'])) {
    
    $start_date = $_POST['start_date']; // Changed from $_GET to $_POST
    $end_date = $_POST['end_date']; // Changed from $_GET to $_POST
    
    // Query to get current inventory levels for each product
    $sql = "SELECT `order`.*, product.name AS product_name, users.name AS user_name
        FROM `order`
        INNER JOIN order_detail ON `order`.id = order_detail.order_id
        INNER JOIN product ON order_detail.product_id = product.id
        INNER JOIN users ON `order`.user_id = users.id
        WHERE `order`.added_on BETWEEN '$start_date' AND '$end_date' $condition";

    // Execute the query and fetch results
    $result = mysqli_query($con, $sql);

    // HTML content for the PDF
    $html = '<h2>Inventory Status Report</h2>';
    $html .= '<table border="1" cellspacing="0" cellpadding="5">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product Name</th>
                        <th>User Name</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>';
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $html .= '<tr>
                        <td>' . $row['id'] . '</td>
                        <td>' . $row['product_name'] . '</td>
                        <td>' . $row['user_name'] . '</td>
                        <td>' . $row['added_on'] . '</td>
                      </tr>';
        }
    } else {
        $html .= '<tr><td colspan="3">No inventory data available</td></tr>';
    }
    $html .= '</tbody></table>';

    // Generate PDF
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('Inventory_Status_Report.pdf', 'D'); // Download PDF
    exit; // Stop further execution
}
?>
