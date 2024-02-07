<?php
$server_name = "localhost";
$user_name = "shachafpe_liorz";
$password = "nAhIp+7hEGE";
$database_name = "shachafpe_bookme";

// Create connection
$conn = new mysqli($server_name, $user_name, $password, $database_name);
$conn->set_charset("utf8");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a user is logged in and retrieve the user's email from the session
session_start();

if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
    // $user_cities = $_SESSION['user_cities'];
}

// Get info from HTML form
$book_name = $_POST['book_name'];
$book_author_name = $_POST['book_author_name'];
$book_genre = $_POST['book_genre'];
$book_quantity = $_POST['book_quantity'];
$deal_type = $_POST['deal_type'];
$book_required_price = empty($_POST['book_required_price']) ? 'NULL' : $_POST['book_required_price'];

// File upload handling
if ($_FILES['book_picture']['size'] > 0) {
    $upload_dir = "../uploads/"; // Directory to store uploaded images
    $target_file = $upload_dir . basename($_FILES["book_picture"]["name"]);

    if (move_uploaded_file($_FILES["book_picture"]["tmp_name"], $target_file)) {
        $book_picture = $target_file;
    } else {
        echo "Sorry, there was an error uploading your file.";
        exit();
    }
} else {
    $book_picture = null; // If no file uploaded, set to NULL or any default value
}

// Add book_user
$sql = "INSERT INTO `books_users` 
        (`book_name`, `book_author_name`, `user_mail`, `deal_type`, `book_quantity`, `book_genre`, `book_required_price`, `book_picture`)
        VALUES 
        ('$book_name', '$book_author_name', '$user_email', $deal_type, $book_quantity, '$book_genre', $book_required_price, '$book_picture')";

if ($conn->query($sql) === FALSE) {
    // Error occurred, redirect to error page
    header("Location: ./duplicateBook.html");
    exit();
} else {
    // Successful query, redirect to another page
    header("Location: ../index.php");
    exit();
}
?>
