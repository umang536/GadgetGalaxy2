<?php
date_default_timezone_set('Asia/Kolkata');

function pr($arr){
	echo '<pre>';
	print_r($arr);
}

function prx($arr){
	echo '<pre>';
	print_r($arr);
	die();
}

function get_safe_value($con,$str){
	if($str!=''){
		$str=trim($str);
		return mysqli_real_escape_string($con,$str);
	}
}

function get_product($con, $limit = '', $cat_id = '', $product_id = '', $search_str = '', $sort_order = '', $is_best_seller = '', $sub_categories = '') {
    $sql = "SELECT product.*, categories.categories 
            FROM product 
            INNER JOIN categories ON product.categories_id = categories.id 
            INNER JOIN sub_categories ON sub_categories.categories_id = sub_categories.id 
            WHERE product.status = 1";
    
    if (!empty($cat_id)) {
        $sql .= " AND product.categories_id = $cat_id";
    }
    if (!empty($product_id)) {
        $sql .= " AND product.id = $product_id";
    }
    if (!empty($sub_categories)) {
        $sql .= " AND product.sub_categories_id = $sub_categories";
    }
    if (!empty($is_best_seller)) {
        $sql .= " AND product.best_seller = 1";
    }
    
    if (!empty($search_str)) {
        $sql .= " AND (product.name LIKE '%$search_str%' OR product.description LIKE '%$search_str%' OR categories LIKE '%$search_str%' OR sub_categories LIKE '%$search_str%')";
    }
    
    $sql .= " GROUP BY product.id";
    
    if (!empty($sort_order)) {
        $sql .= " $sort_order";
    } else {
        $sql .= " ORDER BY product.id DESC";
    }
    
    if (!empty($limit)) {
        $sql .= " LIMIT $limit";
    }
    
    //  echo "SQL Query: $sql"; // Print the SQL query for debugging
    
    $res = mysqli_query($con, $sql);
    $data = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}



function wishlist_add($con,$uid,$pid){
	$added_on=date('Y-m-d H:i:s');
	mysqli_query($con,"insert into wishlist(user_id,product_id,added_on) values('$uid','$pid','$added_on')");
}

function productSoldQtyByProductId($con, $pid){
    $sql = "SELECT SUM(order_detail.qty) AS qty 
            FROM order_detail 
            INNER JOIN `order` ON `order`.id = order_detail.order_id 
            WHERE order_detail.product_id = $pid 
            AND `order`.order_status != 4 
            AND ((`order`.payment_type = 'razorpay' AND `order`.payment_status = 'Success') 
            OR (`order`.payment_type = 'COD' AND `order`.payment_status != ''))";
    $res = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($res);
    return $row['qty'];
}

function productQty($con,$pid,$attr_id){
	$sql="select qty from product_attributes where id='$attr_id'";
	$res=mysqli_query($con,$sql);
	$row=mysqli_fetch_assoc($res);
	return $row['qty'];
}

function sentInvoice($con,$order_id){
	$res=mysqli_query($con,"select distinct(order_detail.id) ,order_detail.*,product.name,product.image from order_detail,product ,`order` where order_detail.order_id='$order_id' and order_detail.product_id=product.id");

	$user_order=mysqli_fetch_assoc(mysqli_query($con,"select `order`.*, users.name,users.email  from `order`,users where users.id=`order`.user_id and `order`.id='$order_id'"));

// 	$coupon_details=mysqli_fetch_assoc(mysqli_query($con,"select coupon_value from `order` where id='$order_id'"));
// 	$coupon_value=$coupon_details['coupon_value'];
// 	if($coupon_value==''){
// 		$coupon_value=0;	
// 	}

	$total_price=0;

	$html='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	  <head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="x-apple-disable-message-reformatting" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title></title>
		<style type="text/css" rel="stylesheet" media="all">
		/* Base ------------------------------ */
		
		@import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&display=swap");
		body {
		  width: 100% !important;
		  height: 100%;
		  margin: 0;
		  -webkit-text-size-adjust: none;
		}
		
		a {
		  color: #3869D4;
		}
		
		a img {
		  border: none;
		}
		
		td {
		  word-break: break-word;
		}
		
		.preheader {
		  display: none !important;
		  visibility: hidden;
		  mso-hide: all;
		  font-size: 1px;
		  line-height: 1px;
		  max-height: 0;
		  max-width: 0;
		  opacity: 0;
		  overflow: hidden;
		}
		/* Type ------------------------------ */
		
		body,
		td,
		th {
		  font-family: "Nunito Sans", Helvetica, Arial, sans-serif;
		}
		
		h1 {
		  margin-top: 0;
		  color: #333333;
		  font-size: 22px;
		  font-weight: bold;
		  text-align: left;
		}
		
		h2 {
		  margin-top: 0;
		  color: #333333;
		  font-size: 16px;
		  font-weight: bold;
		  text-align: left;
		}
		
		h3 {
		  margin-top: 0;
		  color: #333333;
		  font-size: 14px;
		  font-weight: bold;
		  text-align: left;
		}
		
		td,
		th {
		  font-size: 16px;
		}
		
		p,
		ul,
		ol,
		blockquote {
		  margin: .4em 0 1.1875em;
		  font-size: 16px;
		  line-height: 1.625;
		}
		
		p.sub {
		  font-size: 13px;
		}
		/* Utilities ------------------------------ */
		
		.align-right {
		  text-align: right;
		}
		
		.align-left {
		  text-align: left;
		}
		
		.align-center {
		  text-align: center;
		}
		/* Buttons ------------------------------ */
		
		.button {
		  background-color: #3869D4;
		  border-top: 10px solid #3869D4;
		  border-right: 18px solid #3869D4;
		  border-bottom: 10px solid #3869D4;
		  border-left: 18px solid #3869D4;
		  display: inline-block;
		  color: #FFF;
		  text-decoration: none;
		  border-radius: 3px;
		  box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
		  -webkit-text-size-adjust: none;
		  box-sizing: border-box;
		}
		
		.button--green {
		  background-color: #22BC66;
		  border-top: 10px solid #22BC66;
		  border-right: 18px solid #22BC66;
		  border-bottom: 10px solid #22BC66;
		  border-left: 18px solid #22BC66;
		}
		
		.button--red {
		  background-color: #FF6136;
		  border-top: 10px solid #FF6136;
		  border-right: 18px solid #FF6136;
		  border-bottom: 10px solid #FF6136;
		  border-left: 18px solid #FF6136;
		}
		
		@media only screen and (max-width: 500px) {
		  .button {
			width: 100% !important;
			text-align: center !important;
		  }
		}
		/* Attribute list ------------------------------ */
		
		.attributes {
		  margin: 0 0 21px;
		}
		
		.attributes_content {
		  background-color: #F4F4F7;
		  padding: 16px;
		}
		
		.attributes_item {
		  padding: 0;
		}
		/* Related Items ------------------------------ */
		
		.related {
		  width: 100%;
		  margin: 0;
		  padding: 25px 0 0 0;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		}
		
		.related_item {
		  padding: 10px 0;
		  color: #CBCCCF;
		  font-size: 15px;
		  line-height: 18px;
		}
		
		.related_item-title {
		  display: block;
		  margin: .5em 0 0;
		}
		
		.related_item-thumb {
		  display: block;
		  padding-bottom: 10px;
		}
		
		.related_heading {
		  border-top: 1px solid #CBCCCF;
		  text-align: center;
		  padding: 25px 0 10px;
		}
		/* Discount Code ------------------------------ */
		
		.discount {
		  width: 100%;
		  margin: 0;
		  padding: 24px;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		  background-color: #F4F4F7;
		  border: 2px dashed #CBCCCF;
		}
		
		.discount_heading {
		  text-align: center;
		}
		
		.discount_body {
		  text-align: center;
		  font-size: 15px;
		}
		/* Social Icons ------------------------------ */
		
		.social {
		  width: auto;
		}
		
		.social td {
		  padding: 0;
		  width: auto;
		}
		
		.social_icon {
		  height: 20px;
		  margin: 0 8px 10px 8px;
		  padding: 0;
		}
		/* Data table ------------------------------ */
		
		.purchase {
		  width: 100%;
		  margin: 0;
		  padding: 35px 0;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		}
		
		.purchase_content {
		  width: 100%;
		  margin: 0;
		  padding: 25px 0 0 0;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		}
		
		.purchase_item {
		  padding: 10px 0;
		  color: #51545E;
		  font-size: 15px;
		  line-height: 18px;
		}
		
		.purchase_heading {
		  padding-bottom: 8px;
		  border-bottom: 1px solid #EAEAEC;
		}
		
		.purchase_heading p {
		  margin: 0;
		  color: #85878E;
		  font-size: 12px;
		}
		
		.purchase_footer {
		  padding-top: 15px;
		  border-top: 1px solid #EAEAEC;
		}
		
		.purchase_total {
		  margin: 0;
		  text-align: right;
		  font-weight: bold;
		  color: #333333;
		}
		
		.purchase_total--label {
		  padding: 0 15px 0 0;
		}
		
		body {
		  background-color: #F4F4F7;
		  color: #51545E;
		}
		
		p {
		  color: #51545E;
		}
		
		p.sub {
		  color: #6B6E76;
		}
		
		.email-wrapper {
		  width: 100%;
		  margin: 0;
		  padding: 0;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		  background-color: #F4F4F7;
		}
		
		.email-content {
		  width: 100%;
		  margin: 0;
		  padding: 0;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		}
		/* Masthead ----------------------- */
		
		.email-masthead {
		  padding: 25px 0;
		  text-align: center;
		}
		
		.email-masthead_logo {
		  width: 94px;
		}
		
		.email-masthead_name {
		  font-size: 16px;
		  font-weight: bold;
		  color: #A8AAAF;
		  text-decoration: none;
		  text-shadow: 0 1px 0 white;
		}
		/* Body ------------------------------ */
		
		.email-body {
		  width: 100%;
		  margin: 0;
		  padding: 0;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		  background-color: #FFFFFF;
		}
		
		.email-body_inner {
		  width: 570px;
		  margin: 0 auto;
		  padding: 0;
		  -premailer-width: 570px;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		  background-color: #FFFFFF;
		}
		
		.email-footer {
		  width: 570px;
		  margin: 0 auto;
		  padding: 0;
		  -premailer-width: 570px;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		  text-align: center;
		}
		
		.email-footer p {
		  color: #6B6E76;
		}
		
		.body-action {
		  width: 100%;
		  margin: 30px auto;
		  padding: 0;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		  text-align: center;
		}
		
		.body-sub {
		  margin-top: 25px;
		  padding-top: 25px;
		  border-top: 1px solid #EAEAEC;
		}
		
		.content-cell {
		  padding: 35px;
		}
		/*Media Queries ------------------------------ */
		
		@media only screen and (max-width: 600px) {
		  .email-body_inner,
		  .email-footer {
			width: 100% !important;
		  }
		}
		
		@media (prefers-color-scheme: dark) {
		  body,
		  .email-body,
		  .email-body_inner,
		  .email-content,
		  .email-wrapper,
		  .email-masthead,
		  .email-footer {
			background-color: #333333 !important;
			color: #FFF !important;
		  }
		  p,
		  ul,
		  ol,
		  blockquote,
		  h1,
		  h2,
		  h3 {
			color: #FFF !important;
		  }
		  .attributes_content,
		  .discount {
			background-color: #222 !important;
		  }
		  .email-masthead_name {
			text-shadow: none !important;
		  }
		}
		</style>
		<!--[if mso]>
		<style type="text/css">
		  .f-fallback  {
			font-family: Arial, sans-serif;
		  }
		</style>
	  <![endif]-->
	  </head>
	  <body>
		<span class="preheader">This is an invoice for your purchase on '.$user_order['added_on'].'. Please submit payment by {{ due_date }}</span>
		<table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
		  <tr>
			<td align="center">
			  <table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
				<tr>
				  <td class="email-masthead">
					<a href="https://example.com" class="f-fallback email-masthead_name">
					[INVOICE]
				  </a>
				  </td>
				</tr>
				<!-- Email Body -->
				<tr>
				  <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
					<table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
					  <!-- Body content -->
					  <tr>
						<td class="content-cell">
						  <div class="f-fallback">
							<h1>Hi '.$user_order['name'].',</h1>
							<p>Thanks for using ourwebsite. This is an invoice for your recent purchase.</p>
							<table class="attributes" width="100%" cellpadding="0" cellspacing="0" role="presentation">
							  <tr>
								<td class="attributes_content">
								  <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
									<tr>
									  <td class="attributes_item">
										<span class="f-fallback">
				  <strong>Amount Due:</strong> '.$user_order['total_price'].'.
				</span>
									  </td>
									</tr>
								   
								  </table>
								</td>
							  </tr>
							</table>
							<!-- Action -->
							
							<table class="purchase" width="100%" cellpadding="0" cellspacing="0">
							  <tr>
								<td>
								  <h3>'.$user_order['id'].'.</h3>
								</td>
								<td>
								  <h3 class="align-right">'.$user_order['added_on'].'.</h3>
								</td>
							  </tr>
							  <tr>
								<td colspan="2">
								  <table class="purchase_content" width="100%" cellpadding="0" cellspacing="0">
									<tr>
									  <th class="purchase_heading" align="left">
										<p class="f-fallback">Description</p>
									  </th>
									  <th class="purchase_heading" align="right">
										<p class="f-fallback">Amount</p>
									  </th>
									</tr>
									Invoice Details';
									while($row=mysqli_fetch_assoc($res)){
										$total_price=$total_price+($row['qty']*$row['price']);
										$pp=$row['qty']*$row['price'];
										$html.='<tr>
										  <td width="80%" class="purchase_item"><span class="f-fallback">'.$row['name'].'</span></td>
										  <td class="align-right" width="20%" class="purchase_item"><span class="f-fallback">'.$pp.'</span></td>
										</tr>';
									}
									
								// 	if($coupon_value!=''){								
								// 		$html.=' <td width="80%" class="purchase_footer" valign="middle">
								// 		<p class="f-fallback purchase_total purchase_total--label">Coupon Value</p>
								// 	  </td>
								// 	  <td width="20%" class="purchase_footer" valign="middle">
								// 		<p class="f-fallback purchase_total">'.$coupon_value.'</p>
								// 	  </td>
								// 	</tr>';
								// 	}
								// 	$total_price=$total_price-$coupon_value;
									$html.='<tr>
									  <td width="80%" class="purchase_footer" valign="middle">
										<p class="f-fallback purchase_total purchase_total--label">Total</p>
									  </td>
									  <td width="20%" class="purchase_footer" valign="middle">
										<p class="f-fallback purchase_total">'.$total_price.'</p>
									  </td>
									</tr>
								  </table>
								</td>
							  </tr>
							</table>
							<p><a href="https://gadget-galaxy.000webhostapp.com/order_pdf.php?id='.$user_order["id"].'"> PDF</a></p>
							<p>If you have any questions about this invoice, simply reply to this email or reach out to our <a href="https://gadget-galaxy.000webhostapp.com/contact.php">support team</a> for help.</p>
							<p>Cheers,
							  <br>The GadgetGalaxy Team</p>
							<!-- Sub copy -->
							
						  </div>
						</td>
					  </tr>
					</table>
				  </td>
				</tr>
				<tr>
				  <td>
					<table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
					  <tr>
						<td class="content-cell" align="center">
						  <p class="f-fallback sub align-center">© GadgetGalaxy 2024. All right reserved.</p>
						  <p class="f-fallback sub align-center">
							GadgetGgalaxy
							<br>KSV Campus
							<br>Sec. 23, Gandhinagar
						  </p>
						</td>
					  </tr>
					</table>
				  </td>
				</tr>
			  </table>
			</td>
		  </tr>
		</table>
	  </body>
	</html>';
	
	include('smtp/PHPMailerAutoload.php');
	$mail=new PHPMailer(true);
	$mail->isSMTP();
	$mail->Host="smtp.gmail.com";
	$mail->Port=587;
	$mail->SMTPSecure="tls";
	$mail->SMTPAuth=true;
	$mail->Username=SMTP_EMAIL;
	$mail->Password=SMTP_PASSWORD;
	$mail->SetFrom(SMTP_EMAIL);
	$mail->addAddress($user_order['email']);
	$mail->IsHTML(true);
	$mail->Subject="Invoice Details";
	$mail->Body=$html;
	$mail->SMTPOptions=array('ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false,
		'allow_self_signed'=>false
	));
	if($mail->send()){
		//echo "Please check your email id for password";
	}else{
		//echo "Error occur";
	}
}


// function getProductAttr($con,$pid){
// 	$sql="select id from product_attributes where product_id='$pid'";
// 	$res=mysqli_query($con,$sql);
// 	$row=mysqli_fetch_assoc($res);
// 	return $row['id'];
// }
?>