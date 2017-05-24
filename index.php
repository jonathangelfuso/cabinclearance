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

// $main_sql = "SELECT tiers.level as level, override.period as period, sales.amount as sales, tiers.goal_amount as goal, tiers.min_payout as payout from sales join override on sales.override_id = override.id join tiers on override.id = tiers.tiers_id order by period asc, level desc";
// $result = $conn->query($main_sql);

$sqloverrides = "SELECT override.period as period, vendors.name as name, override.id as id, override.paxorrev as type from override join vendors on override.vendor_id = vendors.id";
$override_result = $conn->query($sqloverrides);

$sql = "SELECT * FROM override";
$master = $conn->query($sql);

$earnings = array();

foreach($master as $row)
{
	$sql2 = "SELECT tiers.goal_amount as goal, tiers.min_payout as payout, tiers.level as level, sales.amount as sales, sales.override_id as id from tiers join sales on tiers.tiers_id = sales.override_id where tiers_id = :u order by level desc";
	$result = $conn->prepare($sql2);
	$result->bindValue(':u', $row['id']);
	$result->execute();
	foreach($result as $row2)
	{
		if($row2['sales'] >= $row2['goal'])
		{
			$earnings[$row2['id']] = array($row2['payout']*$row2['goal'], $row2['level']);
			$earnings["id_tier"] = $row2['level'];
			break;
		}

	}
}

// foreach($master as $row)
// {
// 	$sqlearnings = "SELECT tiers.level as level, override.period as period, sales.amount as sales, tiers.goal_amount as goal, tiers.min_payout as payout from sales join override on sales.override_id = override.id join tiers on override.id = tiers.tiers_id where tiers.tiers_id = :u order by period asc, level desc";
// 	$sqlresult = $conn->prepare($sqlearnings);
// 	$sqlresult->bindValue(':u', $row['id']);
// 	$sqlresult->execute();
// 	echo $row['id'];

// 	foreach($result as $rowTwo)
// 	{
// 		if ($rowTwo['sales'] >= $rowTwo['goal'])
// 		{
// 			$earnings[$rowTwo['period']] = $rowTwo['payout'];
// 			echo $rowTwo['payout'];
// 			break;
// 		}
// 	}
// }
// foreach($result as $rowTwo)
// {
// 	if ($rowTwo['sales'] >= $rowTwo['goal'])
// 	{
// 		$earnings[$rowTwo['period']] = $rowTwo['payout'];
// 		break;
// 	}
// 	else
// 	{
// 		$earnings[$rowTwo['period']] = 0;
// 	}
// }



?>

<html>

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<title>Main Page</title>
</head>

<body>
	<div class="container">
		<div class="table">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Vendor</th>
						<th>Override Period</th>
						<th>Override Type</th>
						<th>Earnings</th>
						<th>Needed for Next Tier</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($override_result as $row) { ?>
					<tr>
						<td><?php echo $row['name']?></td>
						<td><?php echo $row['period']?></td>
						<td><?php echo $row['type']?></td>
						<td><?php if(isset($earnings[$row['id']]))
						{
							echo sprintf('$%01.2f', $earnings[$row['id']][0]);
						} else {
							echo 0;
						}?></td>
						<td>
							<?php if(isset($earnings[$row['id']]))
							{
								echo $earnings[$row['id']][1];
							} else {
								echo 0;
							}?></td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td>
							<?php 
							$sum = 0;
							foreach($earnings as $item)
							{
								$sum += $item[0];
							}
							echo $sum;
							?>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</body>

</html>
