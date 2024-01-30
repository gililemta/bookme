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
?>
