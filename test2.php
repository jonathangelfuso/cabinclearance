<?php
include "includes/db.php";
include "includes/functions-old.php";
include "includes/header.php";

$vendors = getVendorList();
$currentYear = date("Y");
$earnings = getOverrideList();

?>
<html>
	<body><br/>
		<form method = 'post' action = "/cabinclearance/sales.php">
		<table class="table form-group">
			<td>View Sales for Vendor:</td><br/>
				<td><select name="vendor" class="form-control">
					<?php 
					foreach($vendors as $row)
					{ ?>
						<option value=<?php echo $row['name']?>><?php echo $row['name']?></option>
					<?php } ?><br/>
					</select>
					<select name = "year" class = "form-control">
					<?php 
					for($i = 2017; $i<2031;$i++){?>
					<option value = <?php echo "$i";?>><?php echo "$i";?></option>
					<?php
					}?>
				</select>
				
				</td>
			<td><button type="submit" class="btn btn-primary" name="vendorSubmit" value = "1">View</button></td>
			
		</table>
		</form>
	<br/>	


<div class="panel panel-primary panel-large">
	<div class="panel-heading">
		<h3 class="panel-title">Results</h3>
	</div>
	<table class="table table-striped">
		<thead>
			<th>Vendor Name</th>
			<th>Override Name</th>
			<th>Amount Sold</th>
			<th>Period</th>
			<th>Earned</th>
			<th>Level Earned</th>
			<th>Next Level</th>
			<th>Amount needed for next goal</th>
			<th>Amount to go to next goal</th>
			<th>Amount next goal is worth</th>
			<th>Increase in value from goal to goal</th>

		</thead>
		<tbody> <!--Just needs filters for period, vendor dynamically created-->
			<?php
		 	foreach($earnings as $row) { ?>
			<tr>
				<td><?php echo $row['vendor'] ?></td>
				<td><a href="/cabinclearance/overridedetail.php/?et=<?php echo $row['id']?>"><?php echo $row['override'] ?></a></td>
				<td><?php 
					if($row['paxorrev'] == 'revenue') { //Would be nice to have this as a function, though I think it's too many parameters to pass
						echo makeMoney($row['sold']);
					} else {
						echo $row['sold'];
					}?></td>
				<td><?php echo $row['period'] ?></td>
				<td><?php echo makeMoney($row['payout']) ?></td>
				<td><?php echo $row['level'] ?></td>
				<td><?php echo $row['nextlevel'] ?></td>
				<td><?php 
					if($row['paxorrev'] == 'revenue') {
						echo makeMoney($row['nextgoal']);
						} else {
							echo $row['nextgoal'];
						} ?></td>
				<td><?php
						if($row['paxorrev'] == 'revenue') {
							echo makeMoney($row['togo']);
						} else {
							echo $row['togo'];
						} ?></td>
				<td><?php echo makeMoney(floatval($row['nextpayout'])); ?></td>
				<td>
					<?php
					if($row['nextlevel'] == 'maxed!') {
						echo "maxed!";
					} else {
						$difference = $row['nextpayout'] - $row['payout'];
						echo makeMoney($difference);
					}
					?>
				
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?php include "includes/footer.php" ?>