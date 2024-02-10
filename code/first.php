<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Set your PayPal API credentials
$accessToken = 'A21AAJVT7hqspqtajTyjmu5Hg1fAzK_gVLhKEk1s6Y8aRFf5Uc1vqeDQEzKwB5qcMBXeOUw1Nf81oaIbBOL6B9lmUwIauq4jw';

// Set up your request data
$requestData = array(
    'intent' => 'CAPTURE',
    'purchase_units' => array(
        array(
            'reference_id' => '3X',
            'amount' => array(
                'currency_code' => 'USD',
                'value' => '100.00'
            )
        )
    )
);

// Convert request data to JSON
$requestDataJSON = json_encode($requestData);

// Set up cURL to make API call
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api-m.sandbox.paypal.com/v2/checkout/orders');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $accessToken
));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestDataJSON);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request
$response = curl_exec($ch);
$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for errors
if ($httpStatusCode != 201) {
    echo "Error: " . curl_error($ch);
} else {
    header('Content-Type: application/json');
    // Request was successful, handle response as needed
    echo "$response";
}

// Close cURL
curl_close($ch);
?>
