<?php
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

// Check if a user is logged in and retrieve the user's email from the session
if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email']; 
    $user_cities = $_SESSION['user_cities'];
}

//get info from html
$book_name=$_POST['book_name'];
$book_author_name=$_POST['book_author_name'];
$book_genre=$_POST['book_genre'];
$book_quantity=$_POST['book_quantity'];
$book_picture=$_POST['book_picture'];
$deal_type=$_POST['deal_type'];
$book_required_price=$_POST['book_required_price'];


//add book_user
$sql="insert into `books_users`
(`book_name`,
`book_author_name`,
`book_genre`,
`book_quantity`,
`book_picture`,
`deal_type`,
`book_required_price`,
`user_mail`,
`user_cities`)
values 
('$book_name',
'$book_author_name',
'$book_genre',
'$book_quantity',
'$deal_type',
'$book_required_price',
'$user_mail',
'$user_cities'
)";

if ($conn->query($sql) === FALSE) {
    // Error occurred, redirect to error page
    // header("Location: ./loginFailedPage.html");
    exit();
} else {
    // Successful query, redirect to another page
    header("Location: ./homePage.html");
    exit();
}

?>