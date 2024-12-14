<?php
require('../vendor/autoload.php');
require('connection.inc.php');
require('functions.inc.php');

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

// HTML content for the PDF
$html = '<h2>Sales Report for ' . date('F Y', strtotime($selectedMonth)) . '</h2>';
$html .= '<table border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Total Sales</th>
                </tr>
            </thead>
            <tbody>';
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= '<tr>
                    <td>' . $row['day'] . '</td>
                    <td>' . $row['total_sales'] . '</td>
                  </tr>';
    }
} else {
    $html .= '<tr><td colspan="2">Error fetching data: ' . mysqli_error($con) . '</td></tr>';
}
$html .= '</tbody></table>';

// Generate PDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('Sales_Report_' . date('F_Y', strtotime($selectedMonth)) . '.pdf', 'D'); // Download PDF
?>
