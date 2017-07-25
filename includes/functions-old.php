<?php
include "db.php";
session_start();
//$payouttype,$paxorrev,$sold,$payout,$vendorname,$period
function buildCurrentArray($row) {
	$tmpArray = [];
	$payout = evaluatePayout($row['payouttype'],$row['paxorrev'],$row['sold'],$row['payout'],$row['vendorname'],$row['period']);
	$tmpArray['id'] = $row['primarykey'];
	$tmpArray['vendor'] = $row['vendorname'];
	$tmpArray['override'] = $row['overridename'];
	$tmpArray['period'] = $row['period'];
	$tmpArray['sold'] = $row['sold'];
	$tmpArray['payout'] = $payout;
	$tmpArray['level'] = $row['level'];
	$tmpArray['paxorrev'] = $row['paxorrev'];
	return $tmpArray;
}

function buildCurrentArrayNoOver($row) {
	$tmpArray = [];
	$tmpArray = buildCurrentArray($row);
	$tmpArray['payout'] = 0;
	$tmpArray['level'] = 0;
	$tmpArray['nextlevel'] = 1;
	$tmpArray['paxorrev'] = $row['paxorrev'];
	$payout = evaluatePayout($row['payouttype'],$row['paxorrev'],$row['sold'],$row['payout'],$row['vendorname'],$row['period']);
	$tmpArray['nextpayout'] = $payout;
	$tmpArray['nextgoal'] = $row['goal'];
	$tmpArray['togo'] = $row['goal'] - $row['sold']; 
	return $tmpArray;

}

function buildNextArray($array, $nextpayout, $nextlevel, $nextgoal, $sold) {
	$array['nextpayout'] = $nextpayout;
	$array['nextlevel'] = $nextlevel;
	$array['nextgoal'] = $nextgoal;
	$array['togo'] = $nextgoal - $sold;
	return $array;
}

function buildNextArrayMaxed($array) {
	$array['nextpayout'] = "maxed!";
	$array['nextlevel'] = "maxed!";
	$array['nextgoal'] = "maxed!";
	$array['togo'] = "maxed!";
	return $array;
}

function getOverrideList() { //makes an array of unique override ids with tier hit and next tier data
	global $connection;
	$sql = "SELECT * from overridesview3";
	$result = mysqli_query($connection, $sql);
	$earnings = [];
	$checked = []; //This array will contain the key id for each override once the loop below has run through.
		           //This prevents us from having tiers evaluated after we've discovered one's been hit
	$index = 0;

	foreach($result as $row) {
		if(!in_array($row['primarykey'], $checked)) {
			$tmpArray = []; //Each override will become an array appended to earnings array, making a faux cached database
			if($row['sold'] >= $row['goal']) { //if this tier was hit, build the earnings array
				$tmpArray = buildCurrentArray($row);
				$checked[] = $row['primarykey'];
				if($index == 0) { //This determines that the first tier was hit, thus max override acheived. Thanks Jon!
					$tmpArray = buildNextArrayMaxed($tmpArray);
				} else { //If max override not hit, we need to get the actual numbers for the next override, which is really the data from the last row run
					$tmpArray = buildNextArray($tmpArray, $nextpayout, $nextlevel, $nextgoal, $row['sold']);
				}
				$tmpArray[] = $row['payouttype'];
				$earnings[] = $tmpArray;
			} elseif($row['level'] == 1) { //This executes when no overrides have been hit to show data for the first override that can be earned
				$tmpArray = buildCurrentArrayNoOver($row);
				$checked[] = $row['primarykey'];
				$earnings[] = $tmpArray;
			} else { //If the loop is still running, then we need to store this iteration's values in to be used as "next" values in the next iteration
				$nextlevel = $row['level'];
				$nextgoal = $row['goal'];
				$index++;
				$nextpayout = evaluatePayout($row['payouttype'],$row['paxorrev'],$row['sold'],$row['payout'],$row['vendorname'],$row['period']);
			}

		} else {
			$index = 0; // This needs to be here to reset index to 0 when the next override is evaluated on all of it's tiers
		}
	}
	return $earnings;
}

function makeMoney($number) {
	 return $number = "$" . number_format($number, 2);
}

function createVendor() {
	global $connection;
	if(isset($_POST['submit'])){
		$vendor = $_POST['name'];
		$sqlCheck = "SELECT name from vendors where name = '$vendor'";
		$resultCheck = mysqli_query($connection, $sqlCheck);
		if(count($resultCheck) !== 0) {
			$sql = "INSERT into vendors (name) values ('$vendor')";
			$result = mysqli_query($connection, $sql);
			if(!$result){
				die("The vendor could not be saved" . mysqli_error($connection));
			} else {
				$_SESSION['message'] = "Vendor succesffully created";
			}
		} else {
			$_SESSION['message'] = "This vendor already exists";
		}
	}
}

function evaluatePayout($payouttype,$paxorrev,$sold,$payout,$vendorname,$period) {

	if($payouttype == "percentage" && $paxorrev == 'revenue') {
		$payoutIs = $sold * $payout;
	} 
	elseif($payouttype == "percentage" && $paxorrev == 'passengers') {
		global $connection;
		$payoutIs = 0;
		$vendID = getVendorID($vendorname);
		$sql = "SELECT amount FROM sales WHERE type = 'revenue' AND vendorid = '$vendID' AND period = '$period'";
		$revenue = mysqli_query($connection, $sql);
		if ($revenue != false){
			foreach($revenue as $row2){
				$payoutIs = $row2['amount'] * $payout;

			}
		}
	}
	else {
		$payoutIs = $payout;
	}
	return $payoutIs;

}

function getTiers() {
	global $connection;
	if(isset($_GET['et'])) {
		$override = $_GET['et'];
		$sql = "SELECT * from overridesview3 where primarykey = $override order by level asc";
		$result = mysqli_query($connection, $sql);
		return $result;
	}
}

function editTiers() {
	global $connection;
	if(isset($_POST['save'])) {
		$payout = $_POST['payout'];
		$goal = $_POST['goal'];
		$payouttype = $_POST['payouttype'];
		$override = $_GET['et'];

		$sql = "INSERT into tiers (payout_amount, goal_amount, payout_type)";
		$sql.= " values ($payout, $goal, $payouttype)";
		$sql.= " where overrideid=$override";

		$result = mysqli_query($connection, $sql);

		header("Location: overridedetail.php/?et=" . $override);

	}
}

function addTiers() {
	global $connection;
	if(isset($_POST['addtier'])) {
		
	}
}

function getVendorList(){
	global $connection;
	$sql = "SELECT * FROM vendors";
	$vendorList = mysqli_query($connection,$sql);
	return $vendorList;
}
function getVendorID($vendor){
	global $connection;
	$sql = "SELECT id from vendors Where name = '$vendor'";
	$vendorList = mysqli_query($connection,$sql);
	$vendorID = 0 ;
	foreach ($vendorList as $row){
		$vendorID = $row['id'];
	}
	return $vendorID;
}
function getVendorSalesList($vendorID,$year){
	global $connection;

	$sql = "SELECT * FROM sales WHERE year = '$year' AND vendorid = '$vendorID'";
	$salesList = mysqli_query($connection,$sql);
	return $salesList;

}
function getVendorOverrideList($vendorID,$year){
	global $connection;
	$sql = "SELECT * FROM overrides WHERE vendorid = '$vendorID' AND year = 'year'";
	$overrideList = mysqli_query($connection,$sql);
	return $overrideList;
}

function mergeLists($sales,$overrides){
	
}
