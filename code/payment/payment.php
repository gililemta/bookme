<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to generate access token
function generateAccessToken($clientId, $clientSecret) {
    $base = 'https://api-m.sandbox.paypal.com';
    $auth = base64_encode($clientId . ":" . $clientSecret);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $base . '/v1/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Basic ' . $auth,
        'Content-Type: application/x-www-form-urlencoded',
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpStatusCode != 200) {
        echo "Error: Failed to generate access token. HTTP Status Code: " . $httpStatusCode;
        return false;
    }

    $responseData = json_decode($response, true);
    return $responseData['access_token'];
}

// Set your PayPal API credentials
$clientId = 'AdJhKrXbifhPh2GphWPootAAy6fYUM_Dp-5XzmiAqGppkUCoL0BAWnu9JC9bhad1sgkd-JzOohr93lOd';
$clientSecret = 'EH1jwKicOC_PhGDtEdg23l1lxLcwOkHEXbb20j4h4tKJ7F8TYJ0wE-P_Jjn8B-A4HebBwPj0VoUFEtRx';

// Generate access token
$accessToken = generateAccessToken($clientId, $clientSecret);
if (!$accessToken) {
    // Handle error, if access token generation failed
    exit;
}

// Set up your request data
$requestData = array(
    'intent' => 'CAPTURE',
    'purchase_units' => array(
        array(
            'reference_id' => '3X',
            'amount' => array(
                'currency_code' => 'ILS',
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
