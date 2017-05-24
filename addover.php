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

$sql = "SELECT * from vendors";
$results = $conn->query($sql);

?>
<html>

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<title>Main Page</title>
</head>
<body>

	<div class="container">
		<form action="addoverdb.php" method="post">
			<table class="table form-group">
				<tr>
					<td>Vendor Name</td>
					<td><select name="vendor" class="form-control">
						<?php 
						foreach($results as $row)
						{ ?>
							<option value=<?php echo $row['name']?>><?php echo $row['name']?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Type</td>
					<td><select name="period" class="form-control">
							<option value="Q1">Q1</option>
							<option value="Q2">Q2</option>
							<option value="Q3">Q3</option>
							<option value="Q4">Q4</option>
							<option value="Annual">Annual</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Goal Type</td>
					<td><select name="goal_type" class="form-control">
							<option value="Revenue">Revenue</option>
							<option value="passenger">Passenger</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><button type="submit" class="btn btn-primary" name="addover">Next</button></td>
				</tr>
			</table>
		</form>
	</div>
</body>

</html>