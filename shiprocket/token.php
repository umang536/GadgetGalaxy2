<?php
// Initialize cURL
$curl = curl_init();

// Shiprocket API URL
$url = "https://apiv2.shiprocket.in/v1/external/auth/login";

// Set your email and password here
$email = "skpmcadarshan@gmail.com";
$password = "Sih@@kdu1";

// Configure cURL options
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode([
        "email" => $email,
        "password" => $password
    ]),
]);

// Execute the cURL request
$response = curl_exec($curl);

// Check for cURL errors
if (curl_errno($curl)) {
    echo "cURL Error: " . curl_error($curl);
    curl_close($curl);
    exit();
}

// Close the cURL session
curl_close($curl);

// Decode the JSON response
$responseData = json_decode($response, true);

// Check if the token exists in the response
if (isset($responseData['token'])) {
    echo "Token: " . $responseData['token'];
} else {
    echo "Failed to get token. Response: " . $response;
}
?>
