<?php 
$newArray = [];
while($row = mysqli_fetch_assoc($tiers)) {
	$newArray[] = $row['level'];
}
$max = max($newArray);
$index = $max;

foreach($tiers as $row) { ?>
<tr>
	<?php if($_GET['tl'] == $row['level']) { ?>
	<form action="/cabinclearance/overridedetail.php/?et=<?php echo $_GET['et']; ?>&tl=<?php echo $row['level']; ?>" method="post">
		<td><?php echo $row['level'] ?></td>
		<td><input type="number" step=".01" value="<?php echo $row['payout'] ?>" name="payout"></td>
		<td><?php echo $row['payouttype']; ?>
		<td><?php echo $row['sold'] ?></td>
		<td><input type="number" value="<?php echo $row['goal'] ?>" name="goal"></td>
		<td><?php echo $row['goal'] - $row['sold']; ?></td>
		<td>
			<input type="submit" class="btn btn-primary" value="SAVE" name="SAVE">
		</td>
	</form>
	<?php } else { ?>
	<tr>
		<td><?php echo $row['level'] ?></td>
		<td><?php echo $row['payout'] ?></td>
		<td><?php echo $row['payouttype'] ?></td>
		<td><?php echo $row['sold'] ?></td>
		<td><?php echo $row['goal'] ?></td>
		<td><?php echo $row['goal'] - $row['sold']; ?></td>
		<td><a href="/cabinclearance/overridedetail.php/?et=<?php echo $row['primarykey']?>&tl=<?php echo $row['level'] ?>">Edit</a></td>
	</tr>
	<?php } ?>


<?php } ?>