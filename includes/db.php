<?php

$username = "root";
$password = "";
$database = "cabinclearance";

$connection = mysqli_connect('localhost', $username, $password, $database);

if(!$connection){
	die("connection to db failed " . mysqli_error());
}
?>