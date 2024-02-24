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
    $sql = "SELECT deals.*, books_users.book_name, users.first_name AS seller_first_name, users.last_name AS seller_last_name 
            FROM deals
            INNER JOIN books_users ON deals.book_user_id = books_users.book_user_id
            INNER JOIN users ON deals.seller_mail = users.mail
            WHERE deals.buyer_mail = ?";

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
            'suggested_books' => $row['suggested_books'],
            'book_name' => $row['book_name'],
            'seller_name' => $row['seller_first_name'] . " " . $row['seller_last_name']
        ];

        // Conditionally fetch suggested books if deal_type is 1
        if ($row['deal_type'] == 1) {
            // Fetch suggested books based on IDs
            $suggestedBooks = [];
            $suggestedBookIds = explode(',', $row['suggested_books']);
            foreach ($suggestedBookIds as $bookId) {
                $bookQuery = "SELECT book_name FROM books_users WHERE book_user_id = ?";
                $bookStmt = $conn->prepare($bookQuery);
                $bookStmt->bind_param("i", $bookId);
                $bookStmt->execute();
                $bookResult = $bookStmt->get_result();
                if ($bookRow = $bookResult->fetch_assoc()) {
                    $suggestedBooks[] = $bookRow['book_name'];
                }
                $bookStmt->close();
            }
            $deal['suggested_books'] = $suggestedBooks;
        }

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
