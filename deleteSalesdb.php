<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 0);


session_start();
$username = $_SESSION['$username'] ;


if ($username != 'joe'){
	die("Sorry, only Joe can delete sales data");

}




$db_host = "localhost";
$db_name = "cabinclearance";
$db_username = "root";
$db_password = "";

$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_username, $db_password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['check'])){
	$vendor = $_POST['vendor'];
	$type = $_POST['type'];
	$sql1 = "SELECT id from vendors WHERE name = '$vendor'";// we need the vendor ID....
	$resultID = $conn->query($sql1);
	foreach($resultID as $row){
		$vendID = $row['id'];
	}
	$sql = "DELETE from sales WHERE vendors_id = '$vendID' AND type = '$type'";
	$deleteSale = $conn->prepare($sql);
	$deleteSale->execute();
	

}	
else{
	echo "you did not check the 'Are you sure?' confirmation box";
	?>
	<meta http-equiv="refresh" content="5; url=/cabinclearance/index2.php">
	<?php
}
header("Location: index2.php");
