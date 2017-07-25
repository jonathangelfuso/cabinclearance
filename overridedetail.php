<?php
include "includes/db.php";
include "includes/functions-old.php";
include "includes/header.php";


if (isset($_POST['SAVE'])){// edit a tier
	echo "set";
	$overID = $_GET['et'];
	$level = $_GET['tl'];
	$payout = $_POST['payout'];
	$goal = $_POST['goal'];
	$goal = number_format($goal, 2, '.', '');
	$payout = number_format($payout, 3, '.', '');
	$sql = "UPDATE tiers Set payout_amount = '$payout', goal_amount = '$goal' WHERE overrideid = '$overID' AND level = '$level'";
	mysqli_query($connection,$sql) or die(mysqli_error($connection));
}
if(isset($_POST['ADD'])){
	$overID = $_GET['et'];
	$level = $_GET['tl'];
	$payout = $_POST['payout'];
	$goal = $_POST['goal'];
	$payoutType = $_GET['payoutType'];
	$goal = number_format($goal, 2, '.', '');
	$payout = number_format($payout, 3, '.', '');
	$sql = "INSERT INTO tiers (overrideid,level,payout_type,payout_amount,goal_amount) VALUES ('$overID','$level','$payoutType','$payout','$goal')";
	mysqli_query($connection,$sql) or die(mysqli_error($connection));
}
$tiers = getTiers();
editTiers();
?>

<table class="table table-bordered table-striped">
	<thead>
		<th>Tier Level</th>
		<th>Payout</th>
		<th>Payout Type</th>
		<th>Sold</th>
		<th>Goal Amount</th>
		<th>To go</th>
	</thead>
	<tbody>
		<?php 
		if(isset($_GET['tl'])) {
			include "includes/overridedetailseditnew.php";
		} else {
			include "includes/overridedetailview.php";
		} ?>
		<form action="/cabinclearance/overridedetail.php?et=<?php echo $_GET['et']; ?>&tl=<?php echo $max; ?>&payoutType=<?php echo $row['payouttype']; ?>" method="post">
		<tr>
			<input type="hidden" name="level" value="<?php echo $max +1;?>">
			<td>
				<?php echo $max +1;?>
			</td>
			<td>
				<input type="number" step=".001" name="payout">
			</td>
			<td><?php echo $row['payouttype']; ?></td>
			<td>
				N/A
			</td>
			<td>
				<input type="number" step=".01" name="goal">
			</td>
			<td>
				N/A
			</td>
			<td>
				<input type="submit" name="ADD" value="ADD" class="btn btn-danger">
			</td>
			</tr>
	</tbody>
</table>

<?php include "includes/footer.php"; ?>