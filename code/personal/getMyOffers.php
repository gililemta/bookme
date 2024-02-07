<?php
session_start();

if (isset($_SESSION['user_email'])) {
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

    $response = [];

    // SQL to fetch deals data
    $sql = "SELECT * FROM deals WHERE buyer_mail = ?";

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);

    // Bind buyer_mail parameter
    $stmt->bind_param("s", $user_email);

    // Execute the statement
    $stmt->execute();

    // Get result set
    $result = $stmt->get_result();

    // Build response array
    while ($row = $result->fetch_assoc()) {
        $deal = [
            'deal_id' => $row['deal_id'],
            'deal_type' => $row['deal_type'],
            'seller_mail' => $row['seller_mail'],
            'buyer_mail' => $row['buyer_mail'],
            'book_user_id' => $row['book_user_id'],
            'deal_status' => $row['deal_status'],
            'payment_status' => $row['payment_status'],
            'book_required_price' => $row['book_required_price'],
            'book_suggested_price' => $row['book_suggested_price'],
            'suggested_books' => $row['suggested_books']
        ];
        $response[] = $deal;
    }

    // Close the statement
    $stmt->close();

    // Close the database connection
    $conn->close();

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
