<?php
$curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/orders/create/adhoc",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>'{"order_id": "224-47821",
  "order_date": "2024-11-07 11:11",
  "pickup_location": "Primary",
  "billing_customer_name": "Umang",
  "billing_last_name": "Suthar",
  "billing_address": "House 111",
  "billing_address_2": "Near Hokage House",
  "billing_city": "New Delhi",
  "billing_pincode": "110076",
  "billing_state": "Delhi",
  "billing_country": "India",
  "billing_email": "skpmcadarshan@gmail.com",
  "billing_phone": "9173076035",
  "shipping_is_billing": true,
  "shipping_customer_name": "",
  "shipping_last_name": "",
  "shipping_address": "",
  "shipping_address_2": "",
  "shipping_city": "",
  "shipping_pincode": "",
  "shipping_country": "",
  "shipping_state": "",
  "shipping_email": "",
  "shipping_phone": "",
  "order_items": [
    {
      "name": "TShirt",
      "sku": "tshirt",
      "units": 10,
      "selling_price": "900",
      "discount": "",
      "tax": "",
      "hsn": 441122
    }
  ],
  "payment_method": "Prepaid",
  "shipping_charges": 0,
  "giftwrap_charges": 0,
  "transaction_charges": 0,
  "total_discount": 0,
  "sub_total": 9000,
  "length": 10,
  "breadth": 15,
  "height": 20,
  "weight": 2.5
	}',
    CURLOPT_HTTPHEADER => array(
      "Content-Type: application/json",
	   "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjU1MzU2NDUsInNvdXJjZSI6InNyLWF1dGgtaW50IiwiZXhwIjoxNzM0OTg4NTgwLCJqdGkiOiJkRUk0c3puWnpIT2hodXhqIiwiaWF0IjoxNzM0MTI0NTgwLCJpc3MiOiJodHRwczovL3NyLWF1dGguc2hpcHJvY2tldC5pbi9hdXRob3JpemUvdXNlciIsIm5iZiI6MTczNDEyNDU4MCwiY2lkIjo1MzMzNjM5LCJ0YyI6MzYwLCJ2ZXJib3NlIjpmYWxzZSwidmVuZG9yX2lkIjowLCJ2ZW5kb3JfY29kZSI6IiJ9.yku9CPLxP0mtnTzyFaURl_iUBBe0aBfXhWTJr5a1uks"
    ),
  ));
  $SR_login_Response = curl_exec($curl);
  curl_close($curl);
  //$SR_login_Response_out = json_decode($SR_login_Response);
  echo '<pre>';
  print_r($SR_login_Response);
?>