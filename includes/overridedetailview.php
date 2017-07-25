<?php 
$newArray = [];

while($row = mysqli_fetch_assoc($tiers)) {
	$newArray[] = $row['level'];
}

$index = max($newArray);
$max = $index + 1;
if (isset($_POST['addtier'])){
	echo "sub";

}
foreach($tiers as $row) { ?>
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
