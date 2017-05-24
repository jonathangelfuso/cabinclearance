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

$sql1 = "SELECT * from vendors";
$vendorList = $conn->prepare($sql1);
$vendorList->execute();

$sql2 = "SELECT vendors.name as name, override.Period as period, sales.amount as sales, sales.type as type, override.id as id from override join sales on override.Period=sales.period join vendors on vendors.id=override.vendor_id";
$result=$conn->prepare($sql2);
$result->execute();
$results = $result->fetchAll();

$sql3 = "SELECT tiers.level as level, tiers.tiers_id as id, tiers.goal_amount as goal, tiers.payout_type as payout, tiers.min_payout as earnings, vendors.name as name from tiers join vendors on tiers.vendor_id=vendors.id order by tiers.id desc, tiers.level";
$result2=$conn->prepare($sql3);
$result2->execute();
$results2 = $result2->fetchAll();

$overrideEarnings = array();
$tierEarned = array();

foreach($results as $row)
{
	foreach($results2 as $row2)
		if($row['id'] === $row2['id'])
		{
			if($row['sales'] >= $row2['goal'])
			{
				if($row2['payout'] == "percentage")
				{
					$overrideEarnings[$row['id']] = $row['sales']*$row2['earnings'];
				} else {
					$overrideEarnings[$row['id']] = $row2['earnings'];
				}
				$tierEarned[$row['id']] = $row2['level'];
				break;
			} else {
				$overrideEarnings[$row['id']] = 0;
				$tierEarned[$row['id']] = 0;
			}
		}
}

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
						<th># sold</th>
						<th>Earnings</th>
						<th>Needed for Next Tier</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($results as $row)
					{ ?>
						<tr>
							<td><?php echo $row['name']?></td>
							<td><?php echo $row['period']?></td>
							<td><?php echo $row['type']?></td>
							<td>
								<?php if($row['type'] === "Revenue")
								{
									echo sprintf('$%1.2f', $row['sales']);
								} else {
									echo $row['sales'];
								}?>
							</td>
							<td><?php
							if(isset($overrideEarnings[$row['id']]))
								{
									echo sprintf('$%1.2f', $overrideEarnings[$row['id']]);
								} else {
									echo 0;
								} ?>
							</td>
							<td>
								<?php if(isset($tierEarned[$row['id']]))
								{
									$tier = $tierEarned[$row['id']] + 1;
								} else {
									$tier = 1;
								}
								$sql4 = "SELECT tiers.level as level, tiers.goal_amount as goal from tiers where tiers.level=:u and tiers.tiers_id=:v";
								$result4 = $conn->prepare($sql4);
								$result4->bindValue(':u', $tier);
								$result4->bindValue(':v', $row['id']);
								$result4->execute();
								$results4 = $result4->fetch();
								if(isset($results4['level']))
								{
									$amount = $results4['goal'] - $row['sales'];
									if($row['type'] === "Revenue")
									{
										echo sprintf('$%1.2f', $amount);
									} else {
										echo $amount;
									}
								} else {
									echo "Maxed!";
								} ?>
							</td>
						</tr>
						<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td>Grand Total</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>
							<?php 
							$sum = 0;
							foreach($overrideEarnings as $row)
							{
								$sum += $row;
							}
							echo sprintf('$%1.2f', $sum);?>
						</td>
					</tr>
				</tfoot>
			</table>
			<a href="/cabinclearance/addvendor">Add a vendor</a>
		</div>
	</div>
</body>

</html>