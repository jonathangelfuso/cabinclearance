<?php
include "includes/db.php";
include "includes/functions-old.php";
include "includes/header.php";
if (isset($_POST['vendorSubmit'])){
	header("Location: /cabinclearance/sales.php/?vend=" . $_POST['vendor']."&year=". $_POST['year']);

}


$vendor = $_GET['vend'];
$vendorID = getVendorID($vendor);
$year = $_GET['year'];
include "includes/salesbuttons.php";

$sales = getVendorSalesList($vendorID,$year);


//$table = mergeLists($sales,$overrides);
?>
<div class="panel panel-primary panel-large">
	<div class="panel-heading">
		<h3 class="panel-title">Sales: Vendor:<?php echo $vendor;?> Year:<?php echo $year;?></h3>
	</div>
	<table class="table table-striped">
		<thead>
			<th>Period</th>
			<th>Amount Sold</th>
			<th>Type</th>
			<th>Edit Sale</th>
			<th>Options</th>
			</thead>
			<tbody> 
			<?php
			if (isset($_GET['period'])){
				include('editSale.php');
			}
			else{
	
		 		foreach($sales as $row) { ?>
			<tr>
				<td><?php echo $row['period']; ?></td>
				<td><?php echo $row['amount']; ?></td>
				<td><?php echo $row['type']; ?></td>
				<td><a href="/cabinclearance/sales.php/?vend=<?php echo $vendor;?>&year=<?php echo $year;?>&period=<?php echo $row['period'];?>&type=<?php echo $row['type'];?>">Edit</a></td>
				<td><a href="/cabinclearance/Override.php/?vend=<?php echo $vendor;?>&year=<?php echo $year?>&period=<?php echo $row['period'];?>&type=<?php echo $row['type'];?>"> See Overrides </a>
			
				</td>
			</tr>

			<?php } 	
			} 
			?>
			<tr>
			<form action="/cabinclearance/sales.php/?vend=<?php echo $vendor;?>&year=<?php echo $year;?>" method = 'post'>

				<td><input type="text"   name="period"></td>
				<td><input type="number" step = ".01"   name="amount"></td>
				<td><select name="type" class="form-control">
					<option value = "revenue">Revenue</option>
					<option value="passengers">Passengers</option>
					</select>
				</td>
				<td>
					<input type="submit" class="btn btn-primary" value="Add" name="Add">
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?php include "includes/footer.php" ?>