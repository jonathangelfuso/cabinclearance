
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 0);


session_start();
$username = $_SESSION['$username'] ;


if ($username != 'joe'){
	die("Sorry, only Joe can add sales");

}




$db_host = "localhost";
$db_name = "cabinclearance";
$db_username = "root";
$db_password = "";

$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_username, $db_password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$vendor = $_POST['vendor'];
$sql = "SELECT id FROM vendors WHERE name = '$vendor'";
$result = $conn->prepare($sql);
$result ->execute();

foreach($result as $row) {//for some reason this is the only way I can get a single value out
	$vendorID= $row['id'];

}

if ($_POST['type'] == 'Revenue'){
	$type = 'Revenue';
}
else{
	$type = 'Passengers';
}
function updateDatabase($conn,$type,$amount,$vendorID,$period) {//if it is already in the database, we update the database, otherwise add it to the database
	$saleinDatabase = false;
	$overrideID = -1; // there is not index -1 so if we try to pull that we will return nothing and won't enter the foreach loop
	$sql5 = "SELECT sales_id from sales WHERE type = '$type' AND vendors_id = '$vendorID' AND period = '$period'";// this will only return one value
	$res = $conn->prepare($sql5);
	$res->execute();
	foreach($res as $row5){
		$saleinDatabase = true; //it will not enter this foreach unless the value exists in the database
		$saleID = $row5['sales_id']; // we will use this to quickly update the value sales value if it already exists 
	}
	$sql8 = "SELECT id as overrideID from override WHERE vendor_id = '$vendorID' AND Period = '$period' AND paxorrev = 'Revenue'"; // returns only one row
	$overrideQuery = $conn->prepare($sql8);
	$overrideQuery->execute();
	foreach($overrideQuery as $overrideRow){

		$overrideID = $overrideRow['overrideID']; // update $override id if the override exists
		$overrideinDatabase = true;
	}
	$sql6 = "SELECT Percentage as percent, id as primaryTierID from tiers WHERE tiers_id = '$overrideID' AND payout_type = 'Percentage'";// use override ID to find the tiers underneath it.but only if the type is percentage
	$tiersQuery = $conn->prepare($sql6);
	$tiersQuery->execute();

	foreach($tiersQuery as $tierRow){// iterate through tiers to update all of the minpayouts that will be incorrect otherwise
		if ($type == 'Revenue'){//only update the tier if we are entering revenue, entering passengers will not affect the database... I think....
			$minPayout = $amount*$tierRow['percent']/100;//amount is revenue in this case, tierRow[percent] is the percentage stored in the database.
			$tierID = $tierRow['primaryTierID'];
			$sql7 = "UPDATE tiers SET min_payout = '$minPayout' WHERE id = '$tierID'";
			$updateTier = $conn->prepare($sql7);
			$updateTier->execute();
		}

	}
	// if the type of override is percentage (which is based on revenue) then we must update the minimum payout in the tiers table.


	if($saleinDatabase == true){// if it is already in the database, simply update the amount
		$sqlUpdate = "UPDATE sales SET amount = :b WHERE sales_id = '$saleID'";// update using the primary key.
		$update = $conn->prepare($sqlUpdate);
		$update->bindValue(':b', $amount);
		$update->execute();
	}

	else{// if it is not in the database, insert all the information we have
		$sql1 = "INSERT into sales(type,amount,vendors_id,period) VALUES (:u,:v,:w,:y)";
		$insert = $conn->prepare($sql1);
		$insert->bindValue(':u', $type);
		$insert->bindValue(':v', $amount);
		$insert->bindValue(':w', $vendorID);
		$insert->bindValue(':y', $period);
		$insert->execute();
	}

}
if(!empty($_POST['Q1'])) {


	$amount = $_POST['Q1'];
	$period = 'Q1';

	updateDatabase($conn,$type,$amount,$vendorID,$period);
}

if(!empty($_POST['Q2']) ){

	$amount = $_POST['Q2'];
	$period = 'Q2';
	updateDatabase($conn,$type,$amount,$vendorID,$period);

}
if(!empty($_POST['Q3'])){

	$amount = $_POST['Q3'];
	$period = 'Q3';
	updateDatabase($conn,$type,$amount,$vendorID,$period);

}
if(!empty($_POST['Q4'])){

	$amount = $_POST['Q4'];
	$period = 'Q4';
	updateDatabase($conn,$type,$amount,$vendorID,$period);

}

if(!empty($_POST['Annual'])){

	$amount = $_POST['Annual'];
	$period = 'Annual';
	updateDatabase($conn,$type,$amount,$vendorID,$period);

}
if(!empty($_POST['custom'] )){

	$amount = $_POST['custom'];
	$period = $_POST['customPeriod'];
	updateDatabase($conn,$type,$amount,$vendorID,$period);

}
header("Location: index2.php");
?>