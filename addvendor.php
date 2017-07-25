<?php

include "includes/db.php";
include "includes/functions.php";
include "includes/header.php";

createVendor();

?>

<?php if(isset($_SESSION['message'])) { ?>
<h4 class="text-danger"><?php echo $_SESSION['message']; ?></h5>
<?php } ?>

<form action="addvendor.php" method="post">
	<div class="form-group">
		<label for="name">Vendor's Name</label>
		<input type="text" name="name" class="form-control">
	</div>
	<input type="submit" name="submit" class="btn btn-primary" value="CREATE">
</form>

<?php include "includes/footer.php"; ?>