
<?php

include "includes/db.php";
include "includes/functions-old.php";
include "includes/header.php";

if(isset($_POST['addvendor'])){
	$vendor = $_POST['name'];


	$sql1 = "SELECT name FROM vendors WHERE name = '$vendor'";
	$result = mysqli_query($connection,$sql1) or die(mysqli_error($connection));
	$vendExplode = str_split($vendor); // you are not allowed to make a vendor with spaces.
	$space = false;
	foreach ($vendExplode as $char){
		if ($char == " "){
			$space = true;
		}
	}	
	if ($space == false) {
  // some white spaces are there.
		if($result->num_rows > 0){
			echo 'That Vendor Already Exists';
		}
		else{
			$sql = " INSERT INTO vendors (name) VALUES ('$vendor')";
			mysqli_query($connection,$sql) or die(mysqli_error($connection));
			
		}
	}
	else{
		echo 'Vendors must not contain spaces.';
	}
}
if(isset($_POST['deletevendor'])){
	$vendor = $_POST['vendor'];
	$sql = "DELETE FROM vendors WHERE name = '$vendor'";
	mysqli_query($connection,$sql) or die('delete all associate overrides first');

}

$vendors = getVendorList();

?>


<br/>
	<div>

	<table class="table form-group">
	<td>
	Do NOT make a vendor with spaces.
	<form action="/cabinclearance/vendors.php" method="post">
		<input type="text" name="name" class="form-control-inline">
		<button type="submit" name="addvendor" class="btn btn-danger">Add Vendor</button>
	</form>
	</td>
	<td>
	<form action="/cabinclearance/vendors.php" method="post">
		<select name="vendor" class="form-control-inline">
		<?php 
		foreach($vendors as $row)
		{ ?>
			<option value=<?php echo $row['name']?>><?php echo $row['name']?></option>
			<?php } ?><br/>
		</select>
		<button type="submit" name="deletevendor" class="btn btn-danger">Delete Vendor</button>
		
	</form>
	</td>
	</table>
</div>
</body>

</html>