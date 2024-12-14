<?php
require('../vendor/autoload.php');
require('connection.inc.php');
require('functions.inc.php');



// Fetch the limit from the form
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10; // Default limit is 10

// Fetch best-selling products with the specified limit
$sql = "SELECT product_id, SUM(qty) AS total_sold
        FROM order_detail
        GROUP BY product_id
        ORDER BY total_sold DESC
        LIMIT $limit";

$result = mysqli_query($con, $sql);
if ($result) {
    $html = '<h1>Best Selling Products</h1>';
    $html .= '<table border="1" cellspacing="0" cellpadding="5">';
    $html .= '<thead><tr><th>#</th><th>Name</th><th>Qty Sold</th></tr></thead>';
    $html .= '<tbody>';

    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['product_id'];
        $product_sql = "SELECT name FROM product WHERE id = '$product_id'";
        $product_result = mysqli_query($con, $product_sql);
        $product_row = mysqli_fetch_assoc($product_result);
        $product_name = $product_row['name'];

        $html .= "<tr><td>$i</td><td>$product_name</td><td>{$row['total_sold']}</td></tr>";
        $i++;
    }
    $html .= '</tbody></table>';

    // Create mPDF object
    $mpdf = new \Mpdf\Mpdf();

    // Write HTML to PDF
    $mpdf->WriteHTML($html);

    // Output PDF
    $file = time() . '_bestselling.pdf';
    $mpdf->Output($file, 'D');
} else {
    echo "Error fetching data: " . mysqli_error($con);
}
?>
