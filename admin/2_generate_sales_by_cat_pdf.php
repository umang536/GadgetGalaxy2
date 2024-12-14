<?php
// Include necessary files
require('../vendor/autoload.php');
require('connection.inc.php'); // Include your database connection file here

// Query to get sales by category
$sql ="SELECT categories.categories, SUM(order_detail.price) AS total_sales
FROM order_detail
JOIN product ON order_detail.product_id = product.id
JOIN categories ON product.categories_id = categories.id
GROUP BY categories.categories";

// Execute the query
$result = mysqli_query($con, $sql);

// Initialize PDF
$mpdf = new \Mpdf\Mpdf();

// Start PDF content
$html = '<h1>Sales by Category</h1>';

$html .= '<table border="1">
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Total Sales</th>
            </tr>';

if ($result) {
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= '<tr>
                    <td>'.$i.'</td>
                    <td>'.$row['categories'].'</td>
                    <td>'.$row['total_sales'].'</td>
                  </tr>';
        $i++;
    }
} else {
    $html .= '<tr><td colspan="3">Error fetching data: ' . mysqli_error($con) . '</td></tr>';
}

$html .= '</table>';

// Write HTML content to PDF
$mpdf->WriteHTML($html);

// Output PDF
$mpdf->Output('sales_by_category.pdf', 'D'); // 'D' indicates downloading the PDF
?>
