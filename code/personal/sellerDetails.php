<?php
session_start();
include '../db/db.php'; // Make sure the path to db.php is correct

if (!isset($_GET['deal_id'])) {
    echo "No deal selected.";
    exit;
}

$deal_id = $_GET['deal_id'];

// Fetch book details
$sql = "SELECT * FROM users WHERE mail = (SELECT seller_mail FROM deals WHERE deal_id = ? LIMIT 1)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $deal_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Deal not found.";
    exit;
}

$sellerDetails = $result->fetch_assoc();
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
    <div id="pageData" class="book-detail-page">
        <div class="book-item-detail">
            <div class="book-info">
                <h2>לתיאום מסירה צור קשר עם <?php echo $sellerDetails["first_name"]; ?></h2>
                <p>דוא״ל: <?php echo $sellerDetails["mail"]; ?></p>
                <p>טלפון: <?php echo $sellerDetails["phone_number"]; ?></p>
            </div>
        </div>
    </div>

    <div id="footer-container"></div>
</body>
</html>
