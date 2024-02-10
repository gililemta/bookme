<!-- header.php -->
<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/header/header.css" />
    <title>Header Styles</title>
</head>
<body>
<div class="header-container">
    <header style="margin-right: 15px">
        <div
            style="
            display: flex;
            align-items: center;
            justify-content: space-between;
            "
        >
            <div style="display: flex; align-items: center">
                <img
                    src="../BookMeLogo.jpeg"
                    style="
                    border-radius: 50%;
                    width: 120px;
                    height: 120px;
                    margin-left: 10px;
                    "
                />
                <nav>
                    <div>
                        <script src="../js/script.js"></script>
                    </div>
                    <a href="/index.php">דף הבית</a>
                    <a href="/profile/profile.html">איזור אישי</a>
                    <a href="/books/uploadbook.html">העלאת ספר</a>
                    <a href="#">אודות</a>
                </nav>
            </div>
            <div id="loginButton">
                <?php
                session_start();
                if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true) {
                    // User is logged in, change button to profile and link to UpdateProfile.php
                    echo '<a href="/profile/UpdateProfile.php">פרופיל</a>';
                } else {
                    // User is not logged in, show login link
                    echo '<a href="../login/login.html">התחברות</a>';
                }
                ?>
            </div>
        </div>
    </header>
</div>
</body>
</html>
