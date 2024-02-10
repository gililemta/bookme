<?php
include '../db/db.php';
//get info from html
$mail=$_POST['mail'];
$password=$_POST['password'];
$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];
$cities=$_POST['cities'];
$phone_number=$_POST['phone_number'];


//add user
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

if ($conn->query($sql) === FALSE) {
    // Error occurred, redirect to error page
    header("Location: ./registrationFailed.html");
    exit();
} else {
    // Successful query, redirect to another page
    header("Location: ../index.php");
    exit();
}

?>