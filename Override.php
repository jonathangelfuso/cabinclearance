

<?php

include "includes/db.php";
include "includes/functions-old.php";
include "includes/header.php";
$vendor = $_GET['vend'];
$vendorID = getVendorID($vendor);
$year = $_GET['year'];
$type = $_GET['type'];
$period = $_GET['period'];

if (isset($_POST['delete'])){ // delete an override and its tiers
	$id = $_GET['id'];
	$sql1 = "DELETE from tiers WHERE overrideid = '$id'";
	mysqli_query($connection,$sql1);
	$sql2 = "DELETE from overrides WHERE id = '$id'";
	mysqli_query($connection,$sql2);
	
}

$sql = "SELECT * FROM Overrides Where vendorid = '$vendorID' AND year ='$year' AND type = '$type' AND period = '$period'";
$overrideList = mysqli_query($connection,$sql);
?>
<div class="panel panel-primary panel-large">
	<div class="panel-heading">
		<h3 class="panel-title">Overrides: Vendor: <?php echo $vendor;?> Year: <?php echo $year;?> Type: <?php echo $type;?> Period: <?php echo $period;?></h3>
	</div>
	<table class="table table-striped">
		<thead>
			<th>Name</th>
			<th>Payout Type</th>
			<th>Number of Tiers</th>
			<th>Add/Delete</th>


			</thead>
			<tbody> 
			<?php
			foreach($overrideList as $row){
				$tiers = 1;
				$overID = $row['id'];
				$sql2 = "SELECT MAX(level) as maxLevel FROM tiers WHERE overrideid = '$overID'";
				$tierList = mysqli_query($connection,$sql2);
				$tierList->fetch_assoc();
				foreach($tierList as $row2){
					$tiers = $row2['maxLevel'];
				}
				?>

				<tr>
					<td><a href="/cabinclearance/overridedetail.php/?et=<?php echo $row['id']?>"> <?php echo $row['name'];?></a></td>
					<td><?php echo $row['payout_type']; ?></td>
					<td><?php echo $tiers;?></td>
					<td><form action="/cabinclearance/Override.php/?id=<?php echo $row['id'];?>&vend=<?php echo $vendor;?>&year=<?php echo $year;?>&type=<?php echo $type;?>&period=<?php echo $period;?>" method="post">
					<input type = "submit" class="btn btn-primary" value="Delete" name="delete">
					</form>
					</td>
					
					</td>
					<?php
			}
			?>
			<tr>
			<form action="/cabinclearance/overrideInput.php/?vend=<?php echo $vendor;?>&year=<?php echo $year;?>&period=<?php echo $period;?>&type=<?php echo $type;?>" method="post">

				<td><input type="text"   name="name"></td>
				<td><select name="payoutType" class="form-control">
					<option value = "base">Base</option>
					<option value="percentage">Percentage</option>
					</select>
				</td>
				<td><input type="number"   name="tiers"></td>
				<td>
					<input type="submit" class="btn btn-primary" value="Add" name="Add">
				</td>
			</tr>


