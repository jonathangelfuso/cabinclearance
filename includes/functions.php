<?php
include "db.php";
session_start();

function buildCurrentArray($row, $payout) {
	$tmpArray = [];
	$payout = evaluatePayout($row['payouttype'], $row['sold'], $row['payout']);
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

function buildNextArray($array, $nextpayout, $nextlevel, $nextgoal, $sold) {
	$array['nextpayout'] = $nextpayout;
	$array['nextlevel'] = $nextlevel;
	$array['nextgoal'] = $nextgoal;
	$array['togo'] = $nextgoal - $sold;
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
				if($row['payouttype'] == 'percentage') { //this should probably be made into it's own function, it's used frequently
					$payout = $row['sold'] * $row['payout'];
				} else {
					$payout = $row['payout'];
				}
				/*$tmpArray['id'] = $row['primarykey'];
				$tmpArray['vendor'] = $row['vendorname'];
				$tmpArray['override'] = $row['overridename'];
				$tmpArray['period'] = $row['period'];
				$tmpArray['sold'] = $row['sold'];
				$tmpArray['payout'] = $payout;
				$tmpArray['level'] = $row['level'];
				$tmpArray['paxorrev'] = $row['paxorrev'];*/
				$tmpArray = buildCurrentArray($row, $payout);
				$checked[] = $row['primarykey'];
				if($index == 0) { //This determines that the first tier was hit, thus max override acheived. Thanks Jon!
					$tmpArray['nextpayout'] = "maxed!";
					$tmpArray['nextlevel'] = "maxed!";
					$tmpArray['nextgoal'] = "maxed!";
					$tmpArray['togo'] = 'maxed!';
				} else { //If max override not hit, we need to get the actual numbers for the next override, which is really the data from the last row run
					/*$tmpArray['nextpayout'] = $nextpayout;
					$tmpArray['nextlevel'] = $nextlevel;
					$tmpArray['nextgoal'] = $nextgoal;
					$tmpArray['togo'] = $nextgoal - $row['sold'];*/
					$tmpArray = buildNextArray($tmpArray, $nextpayout, $nextlevel, $nextgoal, $row['sold']);
				}
				$earnings[] = $tmpArray;
			} elseif($row['level'] == 1) { //This executes when no overrides have been hit to show data for the first override that can be earned
				$tmpArray['id'] = $row['primarykey'];
				$tmpArray['vendor'] = $row['vendorname'];
				$tmpArray['override'] = $row['overridename'];
				$tmpArray['period'] = $row['period'];
				$tmpArray['sold'] = $row['sold'];
				$tmpArray['payout'] = 0;
				$tmpArray['level'] = 0;
				$tmpArray['nextlevel'] = 1;
				$tmpArray['paxorrev'] = $row['paxorrev'];
				if($row['payouttype'] == 'percentage') {
					$payout = $row['sold'] * $row['payout'];
				} else {
					$payout = $row['payout'];
				}
				$tmpArray['nextpayout'] = $payout;
				$tmpArray['nextgoal'] = $row['goal'];
				$tmpArray['togo'] = $row['goal'] - $row['sold']; 	
				$checked[] = $row['primarykey'];
				$earnings[] = $tmpArray;
			} else { //If the loop is still running, then we need to store this iteration's values in to be used as "next" values in the next iteration
				$nextlevel = $row['level'];
				$nextgoal = $row['goal'];
				$index++;
				if($row['payouttype'] == "percentage") {
					$nextpayout = $row['sold'] * $row['payout'];
				} else {
					$nextpayout = $row['payout'];
				}
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

function evaluatePayout($payouttype, $sold, $payout) {
	if($payouttype == "percentage") {
		$payoutIs = $sold * $payout;
	} else {
		$payoutIs = $payout;
	}
	return $payoutIs;

}