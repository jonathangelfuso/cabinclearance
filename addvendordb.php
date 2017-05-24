<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 0);

session_start();

$db_host = "localhost";
$db_name = "cabinclearance";
$db_username = "root";
$db_password = "root";

$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_username, $db_password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$vendorName = $_POST['name'];
$sql = "INSERT into vendors(name) VALUES (:u)";
$insert = $conn->prepare($sql);
$insert->bindValue(':u', $vendorName);
$insert->execute();
header("Location: index2.php");