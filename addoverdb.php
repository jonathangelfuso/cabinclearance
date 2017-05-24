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

$vendorname = $_POST['vendor'];
$period = $_POST['period'];
$goal_type = $_POST['goal_type'];

$sqlGetVendorId = "SELECT vendors.id from vendors where vendors.name = :vendorname";
$get = $conn->prepare($sqlGetVendorId);
$get->bindValue(':vendorname', $vendorname);
$get->execute();
$get->fetchall();

$sqlInsert = "INSERT into override(vendor_id, Period, paxorrev) values (:vendorid, :period, :goal_type)";
$insert = $conn->prepare($sqlInsert);
$insert->bindValue(':vendorid', $vendorname);
$insert->bindValue(':period', $period);
$insert->bindValue(':goal_type', $goal_type);
$insert->execute();
header("Location: index2.php");
?>