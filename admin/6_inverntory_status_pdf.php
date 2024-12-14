<?php
require('../vendor/autoload.php');
require('connection.inc.php');
require('functions.inc.php');

$condition = '';
if ($_SESSION['ADMIN_ROLE'] == 1) {
    $condition = " AND product.added_by='" . $_SESSION['ADMIN_ID'] . "'";
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_pdf'])) {
    // Query to get current inventory levels for each product
    $sql = "SELECT id, name, qty FROM product WHERE 1 $condition";

    // Execute the query and fetch results
    $result = mysqli_query($con, $sql);

    // HTML content for the PDF
    $html = '<h2>Inventory Status Report</h2>';
    $html .= '<table border="1" cellspacing="0" cellpadding="5">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Quantity in Stock</th>
                    </tr>
                </thead>
                <tbody>';
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productSoldQtyByProductId = productSoldQtyByProductId($con, $row['id']);
                                            $available_qty = $row['qty'] - $productSoldQtyByProductId;
            $html .= '<tr>
                        <td>' . $row['id'] . '</td>
                        <td>' . $row['name'] . '</td>
                        <td>' .$available_qty . '</td>
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