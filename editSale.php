<?php

foreach($sales as $row){
	?>
	<tr>
	<?php
	if($row['period'] == $_GET['period'] && $row['type'] == $_GET['type']){?>
		<form action="/cabinclearance/sales.php/?vend=<?php echo $vendor?>&year=<?php echo $year?>&period=<?php echo $row['period']?>&type=<?php echo $row['type']?>" method="post">
		<td><?php echo $row['period'] ?></td>
		<td><input type="number" step=".01" value="<?php echo $row['amount'] ?>" name="amount"></td>
		<td><?php echo $row['type']; ?>
		<td>
			<input type="submit" class="btn btn-primary" value="SAVE" name="saveEdit">
		</td>
		</form>
		<td><form action="/cabinclearance/sales.php/?vend=<?php echo $vendor?>&year=<?php echo $year?>&period=<?php echo $row['period']?>&type=<?php echo $row['type']?>" method="post">
		<input type = "submit" class="btn btn-primary" value="Delete" name="deleteSale">
		</form>
		</td>
		
	<?php
	}
	else {?>
		<td><?php echo $row['period']; ?></td>
		<td><?php echo $row['amount']; ?></td>
		<td><?php echo $row['type']; ?></td>
		<td><a href="/cabinclearance/sales.php/?vend=<?php echo $vendor?>&year=<?php echo $year?>&period=<?php echo $row['period']?>&type=<?php echo $row['type']?>">Edit</a></td>
		<td><a href="/cabinclearance/Override.php/?vend=<?php echo $vendor?>&year=<?php echo $year?>&period=<?php echo $row['period']?>&type=<?php echo $row['type']?>"> See Overrides </a>
			</td>
	<?php
	}
	?>
	</tr>
	<?php
}

?>