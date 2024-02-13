<?php
session_start();

include '../db/db.php';
//get info from html
$mail=$_POST['mail'];
$password=$_POST['password'];


$sql = "SELECT * FROM users WHERE mail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $mail);
$stmt->execute();

// Get the result
$result = $stmt->get_result();
if ($result->num_rows == 1) {
    // Fetch the row
    $row = $result->fetch_assoc();

    // Compare passwords
    $storedPassword = $row['password'];
    // Assuming $password is the user-input password
    if ($_POST['password'] === $storedPassword) {
        // Passwords match, store email in session
        $_SESSION['user_email'] = $row['mail'];
        
        // Set flag for successful login
        $_SESSION['login_success'] = true;

    
        header("Location: /index.php");
        exit();
    } else {
        // Passwords do not match
        header("Location: ./loginFailedPage.html");
        exit();
    }
} else {
    // No rows found
    header("Location: ./loginFailedPage.html");
    exit();
}

// Close the statement and connection
$stmt->close();

?>