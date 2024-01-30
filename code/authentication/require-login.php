<?php
session_start();

$response = [];

// Check if a user is logged in
if (isset($_SESSION['user_email'])) {
    $response['user_email'] = $_SESSION['user_email'];
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
