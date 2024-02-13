<?php
session_start();
include '../db/db.php'; // Make sure the path to db.php is correct

if (!isset($_GET['book_user_id'])) {
    echo "No book selected.";
    exit;
}

$bookUserId = $_GET['book_user_id'];

// Fetch book details
$sql = "SELECT * FROM books_users WHERE book_user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookUserId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Book not found.";
    exit;
}

$bookDetails = $result->fetch_assoc();

// Prevent the owner from bidding/replacing their own book
if ($_SESSION['user_email'] == $bookDetails['user_mail']) {
    header("Location: ../index.php?message=ownbook");
}

// Fetch user's books for exchange option if needed
$userBooks = [];
if ($bookDetails['deal_type'] == 1) {
    $userEmail = $_SESSION['user_email'];
    $sql = "SELECT book_user_id, book_name FROM books_users WHERE user_mail = ? AND book_user_id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $userEmail, $bookUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $userBooks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/primaryPage.css" />
    <script src="../header/loadHeaderAndFooter.js"></script>
    <script src="../authentication/require-login.js"></script>
    <script src="../js/script.js"></script>
    <title>פרטי ספר</title>
</head>
<body>
    <div id="header-container"></div>
    <?php if (isset($_GET['message'])): ?>
        <div id="message-container">
            <?php
            if ($_GET['message'] == 'success') {
                echo '<p style="color: green; text-align: center;">הצעה נשלחה בהצלחה.</p>';
            } elseif ($_GET['message'] == 'fail') {
                echo '<p style="color: red; text-align: center;">שגיאה בשליחת ההצעה. אנא נסה שנית.</p>';
            } elseif ($_GET['message'] == 'duplicate') {
                echo '<p style="color: red; text-align: center;">שלחת כבר הצעה על ספר זה.</p>';
            } elseif ($_GET['message'] == 'error') {
                echo '<p style="color: red; text-align: center;">שגיאה בהכנת השאילתא.</p>';
            }
            ?>
        </div>
    <?php endif; ?>
    <div id="pageData" class="book-detail-page">
        <div class="book-item-detail">
            <?php if (!empty($bookDetails["book_picture"])): ?>
                <img src="../<?php echo $bookDetails["book_picture"]; ?>" alt="<?php echo $bookDetails["book_name"]; ?>" class="book-image-detail"/>
            <?php else: ?>
                <img src="../BookMeLogo.jpeg" alt="Default Image" class="book-image-detail"/>
            <?php endif; ?>
            <div class="book-info">
                <h2><?php echo $bookDetails['book_name']; ?></h2>
                <p>שם הסופר: <?php echo $bookDetails["book_author_name"]; ?></p>
                <p>ז'אנר: <?php echo $bookDetails["book_genre"]; ?></p>
                <?php if (!empty($bookDetails["book_required_price"])): ?>
                    <p>מחיר: <?php echo $bookDetails["book_required_price"]; ?>₪</p>
                <?php endif; ?>
                <p>סוג עסקה: <?php echo $bookDetails["deal_type"] == 2 ? "מכירה" : "החלפה"; ?></p>
                <?php if (!empty($bookDetails["user_cities"])): ?>
                    <p>ערים למכירה: <?php echo $bookDetails["user_cities"]; ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <form action="../books/submitProposal.php" method="post" class="proposal-form">
            <input type="hidden" name="book_user_id" value="<?php echo $bookUserId; ?>">
            <input type="hidden" name="deal_type" value="<?php echo $bookDetails['deal_type']; ?>">
            <input type="hidden" name="book_required_price" value="<?php echo htmlspecialchars($bookDetails['book_required_price']); ?>">
            <?php if ($bookDetails['deal_type'] == 2): ?>
                <div class="form-group">
                    <label for="book_suggested_price">הצעת מחיר:</label>
                    <input type="number" id="book_suggested_price" name="book_suggested_price" required>
                </div>
            <?php else: ?>
                <div class="form-group">
                    <label for="suggested_books">בחר ספרים להחלפה:</label>
                    <select multiple id="suggested_books" name="suggested_books[]" required>
                        <?php foreach ($userBooks as $userBook): ?>
                            <option value="<?php echo $userBook['book_user_id']; ?>"><?php echo $userBook['book_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="submit-button">
                <input type="submit" value="הגש הצעה">
            </div>
        </form>
    </div>

    <div id="footer-container"></div>
</body>
</html>
