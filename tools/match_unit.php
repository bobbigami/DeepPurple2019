<?php

include ('../library.php');

$query = "SELECT * FROM unit";
$result = mysqli_query($connect,$query);

while ($row = mysqli_fetch_assoc($result)) { 

	$q = "UPDATE devices SET unit_id='$row[unit_id]' WHERE unit LIKE '$row[unit_name]%'";
	$r	= mysqli_query($connect,$q);
	if ($r) { 
		echo "Updated Unit ($row[unit_name]) \n";
	} 
	unset($r);
}
