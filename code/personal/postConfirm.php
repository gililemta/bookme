<?php
session_start();

if (isset($_SESSION['user_email']) && isset($_POST['id']) && isset($_POST['status'])) {

    $user_email = $_SESSION['user_email'];

    $server_name = "localhost";
    $user_name = "shachafpe_liorz";
    $password = "nAhIp+7hEGE";
    $database_name = "shachafpe_bookme";

    // Create connection
    $conn = new mysqli($server_name, $user_name, $password, $database_name);
    $conn->set_charset("utf8");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL to update deals data
    $sql = "UPDATE deals SET deal_status = ? WHERE deal_id = ?";

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);

    $id = intval($_POST['id']);
    $status = $_POST['status'];

    // Bind parameters
    $stmt->bind_param("si", $status, $id);

    // Execute the statement
    $stmt->execute();

    // Close the statement
    $stmt->close();

    // Close the database connection
    $conn->close();

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode(["success" => true]); // You might want to return some response
}
?>
