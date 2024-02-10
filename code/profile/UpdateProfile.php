<?php
session_start();
include '../db/db.php'; // Make sure this path is correct

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    // Redirect to login page if not logged in
    header('Location: ../login/login.html');
    exit();
}

$userEmail = $_SESSION['user_email'];

// Fetch user details
$sql = "SELECT password, first_name, last_name, cities, phone_number FROM users WHERE mail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $userDetails = $result->fetch_assoc();
} else {
    // Handle error - user not found
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
    <link rel="stylesheet" href="../css/uploadBook.css" /> <!-- Use the same CSS file for styling -->
    <script src="../js/script.js"></script>
    <script src="../header/loadHeaderAndFooter.js"></script>
    <script src="../authentication/require-login.js"></script>
    <title>עדכון פרופיל</title>
</head>
<body>
    <div id="header-container"></div>

    <div id="pageData">
        <h2>עדכון פרופיל</h2>
        <form action="processUpdateProfile.php" method="post">
            <div class="form-group">
                <label for="password">סיסמה:</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($userDetails['password']); ?>" required />
            </div>

            <div class="form-group">
                <label for="first_name">שם פרטי:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($userDetails['first_name']); ?>" required />
            </div>

            <div class="form-group">
                <label for="last_name">שם משפחה:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($userDetails['last_name']); ?>" required />
            </div>

            <div class="form-group">
                <label for="cities">עיר:</label>
                <input type="text" id="cities" name="cities" value="<?php echo htmlspecialchars($userDetails['cities']); ?>" required />
            </div>

            <div class="form-group">
                <label for="phone_number">מספר טלפון:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($userDetails['phone_number']); ?>" required />
            </div>

            <div class="submit-button">
                <input type="submit" value="עדכן פרטים" />
            </div>
        </form>
    </div>

    <div id="footer-container"></div>

</body>
</html>
