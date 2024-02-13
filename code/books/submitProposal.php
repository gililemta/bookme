<?php
session_start();
include '../db/db.php'; // Ensure the path is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract POST data
    $bookUserId = $_POST['book_user_id'];
    $dealType = $_POST['deal_type'];
    $buyerMail = $_SESSION['user_email'];
    $bookRequiredPrice = isset($_POST['book_required_price']) && $dealType == 2 ? $_POST['book_required_price'] : null;
    $bookSuggestedPrice = $dealType == 2 ? $_POST['book_suggested_price'] : null;
    $suggestedBooks = $dealType == 1 && isset($_POST['suggested_books']) && is_array($_POST['suggested_books']) ? implode(',', $_POST['suggested_books']) : null;

    // Prepare and execute seller email fetch
    $sqlSeller = "SELECT user_mail FROM books_users WHERE book_user_id = ?";
    $stmtSeller = $conn->prepare($sqlSeller);
    $stmtSeller->bind_param("i", $bookUserId);
    $stmtSeller->execute();
    $resultSeller = $stmtSeller->get_result();
    if ($resultSeller->num_rows == 0) {
        // Handle book not found or seller email fetch failure
        echo "Book or seller not found.";
        exit;
    }
    $sellerMail = $resultSeller->fetch_assoc()['user_mail'];

    // Before preparing the SQL statement for a new proposal, check for an existing one
    $existsSql = "SELECT COUNT(*) FROM deals WHERE book_user_id = ? AND buyer_mail = ? AND deal_type = ?";
    $existsParams = [$bookUserId, $buyerMail, $dealType];

    if ($dealType == 2) { // Sale
        $existsSql .= " AND book_suggested_price = ?";
        array_push($existsParams, $bookSuggestedPrice);
        if (!is_null($bookRequiredPrice)) { // Include book_required_price in the check if provided
            $existsSql .= " AND book_required_price = ?";
            array_push($existsParams, $bookRequiredPrice);
        }
    } elseif ($dealType == 1) { // Exchange
        $existsSql .= " AND suggested_books = ?";
        array_push($existsParams, $suggestedBooks);
    }

    $existsStmt = $conn->prepare($existsSql);
    $existsStmt->bind_param(str_repeat("s", count($existsParams)), ...$existsParams);
    $existsStmt->execute();
    $existsResult = $existsStmt->get_result();
    $existsCount = $existsResult->fetch_array()[0];

    if ($existsCount > 0) {
        // A similar proposal already exists
        header("Location: bookDetail.php?book_user_id=$bookUserId&message=duplicate");
        exit();
    }

    // SQL statement adjustment based on deal type
    $columns = "deal_type, seller_mail, buyer_mail, book_user_id, deal_status, payment_status, suggested_books";
    $values = "?, ?, ?, ?, 'בתהליך', 'בהמתנה', ?";
    $types = "issis";
    $params = [$dealType, $sellerMail, $buyerMail, $bookUserId, $suggestedBooks];

    if ($dealType == 2) { // Include book_suggested_price and book_required_price for sales
        $columns .= ", book_suggested_price, book_required_price";
        $values .= ", ?, ?";
        $types .= "ss";
        array_push($params, $bookSuggestedPrice, $bookRequiredPrice);
    }

    $sql = "INSERT INTO deals ($columns) VALUES ($values)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        // Handle SQL preparation failure
        echo "SQL preparation failed.";
        exit;
    }

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        header("Location: bookDetail.php?book_user_id=$bookUserId&message=success");
    } else {
        header("Location: bookDetail.php?book_user_id=$bookUserId&message=fail");
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
