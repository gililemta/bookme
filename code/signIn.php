<?php
session_start();

$server_name="localhost";
$user_name="shachafpe_liorz";
$password="nAhIp+7hEGE";
$database_name="shachafpe_bookme";

//create connection
$conn=new mysqli($server_name,$user_name,$password,$database_name);
$conn->set_charset("utf8");

//check the connection
if ($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}

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

        header("Location: ./homePage.html");
        exit();
    } else {
        // Passwords do not match
        header("Location: ./loginFailedPage.html");
    }
} else {
    // No rows found
    echo "Email not found";
}

// Close the statement and connection
$stmt->close();

?>