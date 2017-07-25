<?php 
$count = count($tiers);
echo $count;
$newArray = [];
while($row = mysqli_fetch_assoc($tiers)) {
	$newArray[] = $row['level'];
}
print_r($newArray);
$max = max($newArray);
$top = $max + 10;
$possibleLevels = [];

foreach($tiers as $row) { ?>
<tr>
	<form action="overridedetail.php/?et=<?php echo $_GET['et']; ?>" method="post">
		<td><?php echo $row['level'] ?></td>
		<td><input type="number" step=".01" value="<?php echo $row['payout'] ?>" name="payout"></td>
		<td><select name="payouttype">
			<option value='base'<?php if($row['payout'] > 1){echo "selected";}?>>base</option>
			<option value='percentage'<?php if($row['payout'] < 1){echo "selected";}?>>base</option>
		<td><?php echo $row['sold'] ?></td>
		<td><input type="text" value="<?php echo $row['goal'] ?>" name="goal"></td>
		<td><?php echo $row['goal'] - $row['sold']; ?></td>
		<td>
			<input type="submit" class="btn btn-primary" value="SAVE" name="save">
		</td>
	</form>
</tr>
<?php $count--; ?>
<?php if($count == 1) { ?>
<form action="overridedetail.php/?et=<?php echo $row['primarykey'] ?>" method="post">
	<tr>
		<td><select name="newtierlevel">
				<?php for ($max; $max < $top; $max++) { ?>
				<option value="<?php echo $max ?>"><?php echo $max ?></option>
				<?php } ?>
			</select>
		</td>
		<td>
			<input type="number" step=".001" name="payout">
		</td>
		<td>
			<select name="payouttype">
				<option value="base">base</option>
				<option value="percentage">percentage</option>
			</select>
		</td>
		<td>
			N/A
		</td>
		<td>
			<input type="number" step=".01" name="goal">
		</td>
		<td>
			N/A
		</td>
		<td>
			<input type="submit" name="addtier" value="ADD" class="btn btn-danger">
		</td>
<?php } ?>

<?php } ?>