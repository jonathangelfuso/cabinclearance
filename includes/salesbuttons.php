<?php

if (isset($_POST['saveEdit'])){ //edit a sale
	$period = $_GET['period'];
	$type = $_GET['type'];
	$amount = $_POST['amount'] ;
	$amount =  number_format($amount, 2, '.', '');// no commas
	$sqlSaleUpdate = "UPDATE sales SET amount ='$amount' WHERE year = '$year' AND vendorid = '$vendorID' AND period = '$period' AND type = '$type' ";
	mysqli_query($connection,$sqlSaleUpdate) or die(mysqli_error($connection));
	?>
	<meta http-equiv="refresh" content=".0001; url=/cabinclearance/sales.php/?vend=<?php echo $vendor;?>&year=<?php echo $year; ?>">
<?php
}
if (isset($_POST['deleteSale'])){// delete a sale
	$period = $_GET['period'];
	$type = $_GET['type'];
	$sqlOverride = "SELECT id FROM overrides WHERE year = '$year' AND vendorid = '$vendorID' AND period = '$period' AND type = '$type'";
	$OverIDs = mysqli_query($connection,$sqlOverride) or die(mysqli_error($connection));
	foreach($OverIDs as $row){
		$overID = $row['id'];
		$sqlTiers = "DELETE FROM tiers Where overrideid = '$overID'";
		mysqli_query($connection,$sqlTiers) or die(mysqli_error($connection));
		$sqlOverDel = "DELETE FROM overrides WHERE id = '$overID'";
		mysqli_query($connection,$sqlOverDel) or die(mysqli_error($connection));
	}
	$sqlSales = "DELETE FROM sales WHERE year = '$year' AND vendorid = '$vendorID' AND period = '$period' AND type = '$type'";
	mysqli_query($connection,$sqlSales) or die(mysqli_error($connection));
		?>
	<meta http-equiv="refresh" content=".0001; url=/cabinclearance/sales.php/?vend=<?php echo $vendor;?>&year=<?php echo $year; ?>">
<?php
}
if (isset($_POST['Add'])){
	$period = $_POST['period'];
	$amount = $_POST['amount'];
	$amount =  number_format($amount, 2, '.', '');// no commas
	$type = $_POST['type'];
	$sqlSale= "INSERT INTO sales (period,vendorid,amount,type,year) VALUES ('$period','$vendorID','$amount','$type','$year')";
	mysqli_query($connection,$sqlSale) or die(mysqli_error($connection));

}