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

$sqlvendors = "SELECT name from vendors";
$vendors = $conn->query($sqlvendors);
$vendor_result = $vendors->fetchAll();

$sql2 = "SELECT override.Period as period from override";
$get2 = $conn->query($sql2);
$override_result = $get2->fetchAll();

if(isset($_POST['selector']))
{
	$vendor = $_REQUEST['vendor'];
	$period = $_REQUEST['period'];

	$sql = "SELECT sales.id as sales_id, tiers.goal_amount as goal, tiers.level as level, tiers.tiers_id as id, tiers.payout_type as payouttype, tiers.min_payout as payout, sales.amount as sales, vendors.name as name, override.Period as period, override.paxorrev as type from tiers join sales on tiers.tiers_id=sales.override_id join vendors on tiers.vendor_id=vendors.id join override on tiers.tiers_id=override.id where vendors.name=:u and override.period=:v order by vendors.name, override.Period, tiers.level"; 
	$get = $conn->prepare($sql);
	$get->bindValue(':u', $vendor);
	$get->bindValue(':v', $period);
	$get->execute();
} ?>

<html>

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<title>Main Page</title>
</head>

<body>
	<div class="container">
		<form class="form-group" method="post">
			<select class="form-control" name="vendor">
				<?php foreach($vendor_result as $row)
				{ ?>
				<option value="<?php echo $row['name']?>"><?php echo $row['name']?></option>
				<?php } ?>
			</select>
			<select class="form-control" name="period">
				<?php foreach($override_result as $row)
				{ ?>
				<option value="<?php echo $row['period']?>"><?php echo $row['period']?></option>
				<?php } ?>
			</select>
			<input type="submit" name="selector" class="form-control">
		</form>
		<table class="table table-bordered table-striped" border="1">
			<thead>
				<tr>
					<th>Tier Level</th>
					<th>Period</th>
					<th>Vendor</th>
					<th>Type</th>
					<th>Sales</th>
					<th>Goal</th>
					<th>To go</th>
					<th>Potential Earnings</th>
					<th>Amount earned</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
			<?php if(isset($_REQUEST['selector']))
			{ ?>
				<?php
				foreach($get as $row)
				{ ?>
					<tr>
						<td><?php echo $row['level'] ?></td>
						<td><?php echo $row['period'] ?></td>
						<td><?php echo $row['name'] ?></td>
						<td><?php echo $row['type'] ?></td>
						<td><?php if($row['type'] == 'Passengers')
						{ ?>
							 <a href='<?php echo $row[sales_id]?>'><?php echo $row['sales'] ?></a>;
						<?php } else
						{ ?>
							<a href='<?php echo $row[sales_id]?>'><?php echo sprintf('$%1.2f', $row['sales']);
						} ?>
						<td><?php if($row['type'] == 'Passengers')
						{
							echo $row['goal'];
						} else {
							echo sprintf('$%1.2f', $row['goal']);
						} ?>
						<td><?php
								$figure = $row['goal'] - $row['sales'];
								if($figure < 0)
								{
									echo "Earned!";
								} else {
									if($row['type'] == 'Passengers')
									{
										echo $figure;
									} else {
										echo sprintf('$%1.2f', $figure);
									}
								} ?></td>
						<td><?php
							if($row['payouttype'] == "base")
							{
								echo sprintf('$%1.2f', $row['payout']);
							} else {
								echo $row['payout'] . "%";
							} ?></td>
						<td><?php
								if($figure < 0)
								{
									if($row['payouttype'] == "base")
									{
										echo sprintf('$%1.2f', $row['payout']);
									} else {
										echo sprintf('$%1.2f', $row['sales'] * $row['payout']);
									}
								} else {
									echo "$0.00";
								} ?>
						</td>
						<td><form action="edit_tier.php" class="form-group">
								<button type="submit" name="edit" class="btn btn-primary">Edit</button>
							</form></td>
						<td><form action="delete_tier.php" class="form-group">
								<button type="submit" name="delete" class="btn btn-danger">Delete</button>
							</form></td>
					</tr>
				<?php } ?>
			<?php } ?>
			</tbody>
			<tfoot>
				<td>
					<a href="add_tier.php">Add a tier</a>
				</td>
			</tfoot>
		</table>
	</div>
</body>

</html>