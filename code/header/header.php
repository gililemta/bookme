<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/header/header.css" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Header Styles</title>
</head>
<body>
<div class="header-container">
    <div class="menu-toggle">☰</div>
    <header>
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center">
                <img src="../bookMeLogo.png" class="logo" />
                <nav>
                    <a href="/index.php">דף הבית</a>
                    <a href="/personal/personal.html">איזור אישי</a>
                    <a href="/books/uploadbook.html">העלאת ספר</a>
                    <a href="#">אודות</a>
                    <div id="loginButton">
                    <?php
                    if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true) {
                        echo '<a href="/profile/UpdateProfile.php">פרופיל שלי</a>';
                    } else {
                        echo '<a href="../login/login.html">התחברות</a>';
                    }
                    ?>
                    </div>
                </nav>
            </div>
        </div>
    </header>
</div>
</body>
</html>
