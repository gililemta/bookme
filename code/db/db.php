<?php
$server_name="localhost";
$database_name="shachafpe_bookme"; 

// Production

$user_name="shachafpe_liorz";
$password="nAhIp+7hEGE";
$conn=new mysqli($server_name,$user_name,$password,$database_name);
$conn->set_charset("utf8");
if ($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}

// Dev

/*$server_name = "mysql";
$user_name = "root";
$password = "root";
$database_name="shachafpe_bookme"; 

// Create connection
$conn = new mysqli($server_name, $user_name, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $database_name";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();

// Connect to the newly created database
$conn = new mysqli($server_name, $user_name, $password, $database_name);
$conn->set_charset("utf8");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}
*/
?>
