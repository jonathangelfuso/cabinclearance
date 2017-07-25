<?php
$username = "root";
$password = "";
$database = "cabinclearance";

$connection = mysqli_connect('localhost', $username, $password, $database);

if(!$connection){
	die("connection to db failed " . mysqli_error());
}

//Get all overrides and how much sales go to each
$sqlOver = "SELECT overrides.name as name, overrides.id as id, vendors.name as vendor_name, sales.amount as sold, overrides.period as period, sales.type as type ";
$sqlOver.= "FROM overrides JOIN sales on overrides.type = sales.type AND overrides.period = sales.period AND sales.vendorid = overrides.vendorid ";
$sqlOver.= "join vendors on overrides.vendorid = vendors.id";
$overrides = mysqli_query($connection, $sqlOver);

// Now get all the tiers
$sqlTiers = "SELECT * FROM tiers order by overrideid desc, level desc";
$tiers = mysqli_query($connection, $sqlTiers);


function checkNextTier($array) {
	global $connection;
	foreach($array as $row) {
		$sql = "SELECT tiers.level as level, tiers.payout_amount as payout, override.id as primaryKey, tiers.vendorid as foreignKey, sales.amount as sold, "
	}
}
// Couple of arrays to store, for each override, the tier earned and that tier's level and the next tier there is
$earnedOverride = array();
$tierEarned = array();
$tierNext = array();
/* Now, for each override, match the override id as a foreign key to the tiers table,
then iterate through with the sales figures from overrides variable to see if one was hit */
foreach($overrides as $row) {
	foreach($tiers as $row2) {
		if($row['id'] === $row2['overrideid']) {
			if($row['sold'] >= $row2['goal_amount']) {
				if($row2['payout_type'] == "percentage"){
					$earnedOverride[$row['id']] = $row2['payout_amount']*$row['sold'];
					$tierEarned[$row['id']] = $row2['level'];
				} else {
					$earnedOverride[$row['id']] = $row2['payout_amount'];
					$tierEarned[$row['id']] = $row2['level'];
				}
				break;
			} else {
				$earnedOverride[$row['id']] = 0;
				$tierEarned[$row['id']] = 0;
			}
		}
	}
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
	integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<title>Overrides at a glance</title>
</head>

<body>
	<div class="container">
		<div class="col-xs-8">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Q1</h3>
				</div>
				<table class="table table-striped table-hover table-bordered">
					<thead>
						<tr>
							<th>Vendor</th>
							<th>Override name</th>
							<th>Tier level reached</th>
							<th>Approximate amount earned</th>
							<th>Amount to go to next tier</th>
							<th>Value of next tier</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($overrides as $item) { ?>
						<tr>
							<td><?php echo $item['vendor_name'] ?></td>
							<td><a href="vieroverride.php/<?php echo $item['id']?>"><?php echo $item['name'] ?></a></td>
							<td><?php echo $tierEarned[$item['id']] ?></td>
							<td><?php echo $earnedOverride[$item['id']] ?></td>
							<td><?php //Need to pull the tier that was earned and see what the next one's goal amount is, if there insn't a next one just echo maxed
										//Can't proceed on this because bind values not explained on the internet. Just what comes back? How do you work with it?
									//$nextTier = $tierEarned[$item['id']] + 1;
									//$toNextSql = "SELECT goal_amount, payout_type, payout_amount from tiers where overrideid=? and level =?";
									//if($nextGoal){
									//	echo $nextGoal['goal_amount'] - $item['sold'];
									//} else {
									//	echo "MAXED!";
									//} ?>
							</td>
							<td><?php //Need to calculate what earnings would be at sold amount
									//if($nextGoalResult){
									//	if($nextGoalResult['payout_type'] == "percentage"){
									//		echo ($nextGoalResult['goal_amount']*$nextGoalResult['payout_amount']) - ($earnedOverride[$item['id']]);
									//	} else {
									//		echo ($nextGoalResult['goal_amount']) - ($earnedOverride[$item['id']]);
									//	}
									//} else {
									//	echo "MAXED!";
									//} ?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

</body>

</html>