<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 0);

session_start();

$db_host = "localhost";
$db_name = "cabinclearance";
$db_username = "root";
$db_password = "";

$conn = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_username, $db_password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT * from vendors";
$results = $conn->query($sql);

$sql2 = "SELECT DISTINCT period from sales";
$periodResults = $conn->query($sql2);


?>
<html>
<body>


<?php
$tierNumber = $_POST['tierNumber'];
$paxorev = $_POST['goal_type'] ;
$type = $_POST['payout_type'];
$_SESSION['$tierNumber'] = $tierNumber;
$_SESSION['$paxorev'] = $paxorev;
$_SESSION['$type'] = $type;
?>

</body>
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
					<td>Vendor Name:</td>
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
					<td>Period:</td>
					<td><select name="period" class="form-control">
						<?php 
						foreach($periodResults as $row1)
						{echo $row1['period'];?>

							<option value=<?php echo $row1['period'];?>><?php echo $row1['period'];?></option>
						<?php } ?>
						</select>
					</td>					

				</tr>

				<?php
				for($x = 1;$x <= $tierNumber;$x++){?><br></br>
					</tr>

					<?php
					if($paxorev == 'Passenger'):{
					?>
						<td>Passenger Goal(<?php echo "Level: ",$x;?>):<td>
					<?php
					}
					endif;
					?>

					<?php
					if($paxorev == 'Revenue'):{
					?>
						<td>Revenue Goal(<?php echo "Level: ",$x;?>):<td>
					<?php
					}
					endif;
					?>



					<input type="number" name="goal<?php echo $x;?>"><!-- the echo x is to differentiate all of the goals and payouts on the next page -->
					
					<?php 
					if($type == 'Base'){
					?>	<td>Payout($):<td>
						<input type="number" name="payout<?php echo $x;?>"><!-- ex. 11 = goal amount for level 1 (retrieved  on next page) -->
					<?<?php  									//<!-- ex. 21 = payout amount for level 1 (retrieved  on next page) -->
					}
					else{
					?><td>Payout(%):<td>
						<input type="number" name="percentPayout<?php echo $x;?>">
					<?php
					}
					if(isset($_POST['addLevel'])){
						?><td>Level:<td>
						<input type="number" name="Level<?php echo $x;?>">
						<?php
					}
					
					?>
				<?php }?>
				</tr>

				
				<tr>

					<td><button type="submit" class="btn btn-primary" name="addover">Next</button></td>
				</tr>
			</table>
		</form>
	</div>
</body>

</html>