<?php
include "includes/db.php";
include "includes/functions-old.php";
include "includes/header.php";
$vendor = $_GET['vend'];
$year = $_GET['year'];
$type = $_GET['type'];
$period = $_GET['period'];
if (isset($_POST['Finish'])){
	$payoutType = $_GET['payout'];
	$name = $_GET['name'];
	$vendorID = getVendorID($vendor);
	$tiers = $_GET['tiers'];
	$sqlOverride = "INSERT INTO overrides (vendorid,name,period,type,payout_type,year) VALUES ('$vendorID','$name','$period','$type','$payoutType','$year')";
	mysqli_query($connection,$sqlOverride);
	$sqlOverID = "SELECT MAX(id) as maxID FROM overrides";// the maximum ID is the one we just made
	$maxIDObject = mysqli_query($connection,$sqlOverID);
	foreach ($maxIDObject as $row){
		$maxID = $row['maxID'];
		echo $maxID;
	}
	for($level = 1;$level<=$tiers;$level++){
		
		$amount = $_POST["amount$level"];
		$award = $_POST["award$level"];
		$amount = number_format($amount, 2, '.', '');
		if ($payoutType == 'percentage'){

			$award = $award/100.0;
		}
		else{
			$award = $award;
		}
		$award = number_format($award, 3, '.', '');
		
		$sqlTier = "INSERT INTO tiers (overrideid,level,payout_type,payout_amount,goal_amount) VALUES ('$maxID','$level','$payoutType','$award','$amount')";
		mysqli_query($connection,$sqlTier) or die(mysqli_error($connection));
	
	}
	
	//header(" Location: cabinclearance/Override.php/?vend=$vendor&year=$year&type=$type&period=$period");
	
	?>
	<meta http-equiv="refresh" content=".0001; url=/cabinclearance/Override.php/?vend=<?php echo $vendor;?>&year=<?php echo $year;?>&period=<?php echo $period;?>&type=<?php echo $type;?>">

	
<?php
	
}
else{
$payoutType = $_POST['payoutType'];
$name = $_POST['name'];
$tiers = $_POST['tiers'];
}
?>

<html>
	<div class="panel panel-primary panel-large">
		<div class="panel-heading">
			<h3 class="panel-title">Add Tiers: Vendor:<?php echo $vendor;?> Year:<?php echo $year;?> Period: <?php echo $period;?> Year:<?php echo $year;?> Name: <?php echo $name;?></h3>

		</div>
		<table class="table table-striped">
			<thead>
				<th>Level</th>
				<?php 
				if($type == 'revenue'){?>
					<th>Revenue Goal($)</th>
				<?php
				}
				else{
					?><th>Passenger Goal(#)</th>
				<?php
				}
				if($payoutType == 'percentage'){
					?><th>Percent Award(%)</th>
				<?php
				}
				else{
					?><th>Base Award($)</th>
				<?php
				}
				?>


			</thead>
			<tbody> 
			<form action="/cabinclearance/overrideInput.php/?vend=<?php echo $vendor?>&year=<?php echo $year?>&period=<?php echo $period?>&type=<?php echo $type?>&payout=<?php echo $payoutType;?>&tiers=<?php echo $tiers;?>&name=<?php echo $name;?>" method="post">
			<?php
			for($level = 1;$level<= $tiers; $level++){
				?>
				<tr>
					<td> <?php echo $level;?> </td>
					<td><input type="number"  name="amount<?php echo $level;?>"></td>
					<td><input type="number"  name="award<?php echo $level;?>"></td>
					
				</tr>
			<?php
			}
			?>
			
			</tbody>

		</table>
		<br/>
		<input type="submit" class="btn btn-primary" value="Add" name="Finish">
		</form>
	</div>
</html>
