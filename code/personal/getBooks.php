<?php

$server_name = "localhost";
$user_name = "shachafpe_liorz";
$password = "nAhIp+7hEGE";
$database_name = "shachafpe_bookme";

// Create connection
$conn = new mysqli($server_name, $user_name, $password, $database_name);
$conn->set_charset("utf8");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

$response = [];

if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];

    // SQL to fetch books data
    $sql = "SELECT * FROM books_users WHERE book_quantity >= 1 AND deal_type IN (1, 2) AND user_mail = ?";

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);

    // Bind user_mail parameter
    $stmt->bind_param("s", $user_email);

    // Execute the statement
    $stmt->execute();

    // Get result set
    $result = $stmt->get_result();

    // Build response array
    while ($row = $result->fetch_assoc()) {
        $book = [
            'book_name' => $row['book_name'],
            'book_author_name' => $row['book_author_name'],
            'book_genre' => $row['book_genre'],
            'book_required_price' => $row['book_required_price'],
            'deal_type' => $row['deal_type'],
            'book_picture' => $row['book_picture'],
            'book_quantity' => $row['book_quantity'],
        ];
        $response['books'][] = $book;
    }

    $stmt->close();
}

// Close the database connection
$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
