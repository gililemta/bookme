<?php
$server_name="localhost";
$user_name="shachafpe_bookme";
$password="123456";
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
$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];
$cities=$_POST['cities'];
$phone_number=$_POST['phone_number'];


//add use
$sql="insert into `users`
(`mail`,
`password`,
`first_name`,
`last_name`,
`cities`,
`phone_number`)
values ('$mail',
'$password',
'$first_name',
'$last_name',
'$cities',
'$phone_number')";

echo $sql;
if ($conn->query($sql)==FALSE){
    echo "Can not add new user.  Error is: ".$conn->error;
    exit();
}

?>