	<html>

	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<title>Sales</title>
	</head>
<?php
include 'functions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 0);

session_start();

$conn = startConnection();
if (validUser($conn) != true){
	die('error please log back in');
}

$sql = "SELECT * from vendors";
$results = $conn->query($sql);
$sql1 = "SELECT * from vendors";
$results1 = $conn->query($sql1);
$sql3 = "SELECT * from vendors";
$results2 = $conn->query($sql3);
// every time  you loop through it goes away... I need this 3 times.
$table = [];




?>
<html>
	<body><br/>
		<form method = 'post' action = 'addsales.php'>
		<table class="table form-group">
			<td>View Sales for Vendor:</td><br/>
				<td><select name="vendor" class="form-control">
					<?php 
					foreach($results as $row)
					{ ?>
						<option value=<?php echo $row['name']?>><?php echo $row['name']?></option>
					<?php } ?><br/>
					</select>
			<select name="type" class="form-control">
				<option value= 'Revenue'>Revenue</option>
				<option value= 'Passengers'>Passengers</option>
				
			</select>
				<select name = "year" class = "form-control">
					<?php 
					for($i = 2017; $i<2040;$i++){
					?>
					<option value = <?php echo "$i";?>><?php echo "$i";?></option>
					<?php
					}

					?>
					
				</select>
						</td>
			</td>
			<td><button type="submit" class="btn btn-primary" name="vendorSubmit">View</button></td>
			
			</table>
			</form>
		<br/>		
	
<?php
if(isset($_POST['vendorSubmit']) || isset($_POST['addUpdateSalesData']) || isset($_POST['addCustom'])){
	$empty = true; //this will check if the query returns anything 
	if (isset($_POST['vendorSubmit'])){
		$vend = $_POST['vendor'];
		$type = $_POST['type'];
		$year = $_POST['year'];
		$_SESSION['saleVendor'] = $vend;// so we can access it on next page
		$_SESSION['saleType'] = $type;
		$_SESSION['saleYear'] = $year;
	}
	else{
		$vend = $_SESSION['saleVendor'];
		$year = $_SESSION['saleYear'];
		$type = $_SESSION['saleType'];

	}

	$vendID = getVendorID($conn,$vend);
	$_SESSION['vendID'] = $vendID;
	$sql1 = "SELECT amount as saleAmount, period as salePeriod, type as saleType from sales WHERE vendors_id = '$vendID' AND type = '$type'";
	$amountQuery = $conn->prepare($sql1);
	$amountQuery->execute();
	$amountQueryCopy = $amountQuery->fetchAll();
	foreach($amountQueryCopy as $row0){
		$temp = [];
		$temp[] = $row0['salePeriod'];
		$temp[] = $row0['saleAmount'];
	

		$table[] = $temp;
		$empty = false;


	}


	if($empty == true){
		echo "There is no sales data for vendor: $vend type: $type";?>
		<br/>
		<br/>
		<br/>

		
		<form method = 'post' action = 'addsales.php'>
		<td><button type="submit" class="btn btn-primary" name="addSalesData">Add</button></td>
		</form>

		<?php
	}
	else{

	
?>
		<html>

		<form method = 'post' action = 'addsales.php'>


			<body>
					<br/>

				<div class="container">
					<div class="table">
						<table class="table table-bordered table-striped">
						Vendor:<?php echo $vend?> 
							<thead>
								<tr>
									<th>Period</th>
									<?php if ($type == 'Revenue'){
										?><th>Revenue ($)</th><?php
									}
									else{
										?><th>Passengers (#)</th><?php
									}
									?>

									<th>Update</th>
									<th>Delete</th>

								</tr>
							</thead>
							<tbody>
								<?php foreach($table as $row)
								{ ?>
									<tr>
										<td><?php echo $row[0];?></td>
										<td><?php echo $row[1];?></td>


										<td><input type="checkbox" name = "update<?php echo $row[0];?>" value = "<?php echo $row[1];?> " margin = 0 ></td>
										<td><input type="checkbox" name = delete<?php echo $row[0]; ?> margin = 0 ></td>
									</tr>
									<?php
									}
									?>
							</tfoot>
						</table>
					<td><button type="submit" class="btn btn-primary" name="addUpdateSalesData">Update Selected</button></td>
					<td><button type="submit" class="btn btn-primary" name="deleteSalesData">Delete Selected</button></td>
					<td><button type="submit" class="btn btn-primary" name="addCustom">Add Custom Period</button></td>
					</form><br/>
					<?php
					if (isset($_POST['addUpdateSalesData'])){ //this is where we generate the input boxes underneath to update the sales
						$flag = false;


						?><br/>
						<form method = 'post' class = "form-inline" action = 'addsalesdb.php'>
						<?php 
						foreach ($amountQueryCopy as $row){
							$period = $row['salePeriod'];

							if (isset($_POST["update$period"])){
								$flag = true;
								echo "$period:";?><br/>
								
								<input type = 'number' name = "<?php echo $row['salePeriod']?>" class = "form-control"><br/>	
								<?php
							}

						}
						
						?>

						<?php 
						if ($flag == true){
						?>
							<td><button type="submit" class="btn btn-primary" name="update">Update</button></td>
						<?php
						}
						else{
							echo "No data selected for update.";
						}
						?>
						</form>
						<?php
					}
					elseif(isset($_POST['addCustom'])){
						?>
						<form method = 'post' class = "form-inline" action = 'addsalesdb.php'>
						Custom Period Name:<br/>
						<input type = 'text' name = 'customPeriod' class = "form-control"><br/>
						Custom Period Amount:<br/>
						<input type = 'number' name = 'custom' class = "form-control"><br/>
						<td><button type="submit" class="btn btn-primary" name="addCustom">Update</button></td>
						</form>
						<?php
					}
					elseif(isset($_POST['deleteSaleData'])){
					
						$flag = false;

						foreach ($amountQueryCopy as $row){
							$period = $row['salePeriod'];

							if (isset($_POST["delete$period"])){
								$flag = true;
								$sql = "DELETE from sales WHERE period = '$period' AND type = '$type'"

								
								?>
								
								<input type = 'number' name = "<?php echo $row['salePeriod']?>" class = "form-control"><br/>	
								<?php

							}
							if ($flag == false){
								echo "No data selected for deletion.";
							}
	

					
						}?>
					</div>
					<?php
					}?>

				</div>
			</body>

			</html>


<?php



	}
}// below is the delet sales form
if(isset($_POST['deleteSalesData'])){

}

if(isset($_POST['addSalesData'])){
		$type = $_SESSION['saleType'];
		$vendor = $_SESSION['saleVendor'];
		$year = $_SESSION['saleYear'];
				?>

		<form method = 'post' class = "form-inline" action = 'addsalesdb.php'>
		Add Sales for Vendor:<?php echo "$vendor";?> of Type:<?php echo "$type";?> in the year:<?php echo "$year";?><br/>
		Note: if you leave a field blank it will be entered as a 0.
		

		<br/>
		Q1:<br/>
		<input type = 'number' name = 'Q1' class = "form-control"><br/>		
		Q2:<br/>
		<input type = 'number' name = 'Q2' class = "form-control"><br/>
		Q3:<br/>
		<input type = 'number' name = 'Q3' class = "form-control"><br/>
		Q4:<br/>
		<input type = 'number' name = 'Q4' class = "form-control"><br/>
		Annual:<br/>
		<input type = 'number' name = 'Annual' class = "form-control"><br/>

		<td><button type="submit" class="btn btn-primary" name="addSale">Add</button></td>

		</form>

	</body>
</html>

<?php 
}


