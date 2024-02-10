<?php
session_start();
include '../db/db.php'; // Ensure the path is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookUserId = $_POST['book_user_id'];
    $dealType = $_POST['deal_type'];
    $buyerMail = $_SESSION['user_email']; // Assuming user's email is stored in session

    // Fetch seller email from books_users table
    $sqlSeller = "SELECT user_mail FROM books_users WHERE book_user_id = ?";
    $stmtSeller = $conn->prepare($sqlSeller);
    $stmtSeller->bind_param("i", $bookUserId);
    $stmtSeller->execute();
    $resultSeller = $stmtSeller->get_result();
    $sellerRow = $resultSeller->fetch_assoc();
    $sellerMail = $sellerRow['user_mail'];

    // Initialize variables
    $suggestedBooks = null;
    $bookSuggestedPrice = null;

    // Handle deal type specifics
    if ($dealType == 1) { // Sale
        $bookSuggestedPrice = $_POST['book_suggested_price'];
    } else if ($dealType == 2) { // Exchange
        if (isset($_POST['suggested_books']) && is_array($_POST['suggested_books'])) {
            $suggestedBooks = implode(',', $_POST['suggested_books']); // Convert array to comma-separated string
        }
    }

    // Check if the same proposal already exists
    $checkSql = "SELECT * FROM deals WHERE book_user_id = ? AND buyer_mail = ? AND deal_type = ? AND (suggested_books = ? OR book_suggested_price = ?)";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("isisi", $bookUserId, $buyerMail, $dealType, $suggestedBooks, $bookSuggestedPrice);
    $checkStmt->execute();
    $resultCheck = $checkStmt->get_result();

    if ($resultCheck->num_rows > 0) {
        // A similar proposal already exists
        header("Location: bookDetail.php?book_user_id=$bookUserId&message=duplicate");
        exit();
    }

    // Prepare the SQL statement for a new proposal
    $sql = "INSERT INTO deals (deal_type, seller_mail, buyer_mail, book_user_id, deal_status, payment_status, suggested_books, book_suggested_price) VALUES (?, ?, ?, ?, 'בתהליך', 'בהמתנה', ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters and execute
        $stmt->bind_param("ississ", $dealType, $sellerMail, $buyerMail, $bookUserId, $suggestedBooks, $bookSuggestedPrice);
        if ($stmt->execute()) {
            header("Location: bookDetail.php?book_user_id=$bookUserId&message=success");
        } else {
            header("Location: bookDetail.php?book_user_id=$bookUserId&message=fail");
        }
        $stmt->close();
    } else {
        header("Location: bookDetail.php?book_user_id=$bookUserId&message=error");
    }
    
    $conn->close();
} else {
    echo "בקשה לא חוקית.";
}
?>
