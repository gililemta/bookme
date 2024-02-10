<?php
session_start();
require_once '../db/db.php'; // Adjust the path as necessary

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    // Redirect to the login page if not
    header('Location: ../login/login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input from the form
    $password = $_POST['password'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $cities = $_POST['cities'];
    $phoneNumber = $_POST['phone_number'];

    // Prepare SQL statement to update user info
    $sql = "UPDATE users SET password = ?, first_name = ?, last_name = ?, cities = ?, phone_number = ? WHERE mail = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssss", $password, $firstName, $lastName, $cities, $phoneNumber, $_SESSION['user_email']);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            // Update was successful
            echo "<script>alert('פרופיל עודכן בהצלחה'); window.location.href='/profile/UpdateProfile.php';</script>";
        } else {
            // Handle error or no changes made
            echo "<script>alert('לא נעשו שינויים או שהתרחשה שגיאה'); window.location.href='/profile/UpdateProfile.php';</script>";
        }
        $stmt->close();
    } else {
        // Handle error in preparation of statement
        echo "Error preparing statement";
    }
} else {
    // Redirect back to the update profile page if the method is not POST
    header('Location: UpdateProfile.php');
    exit();
}

$conn->close();
?>
