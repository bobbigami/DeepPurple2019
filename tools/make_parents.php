<?php

include ('../library.php');

$query = "SELECT * FROM devices WHERE device_type='3'";
$result	= mysqli_query($connect,$query);

while ($row = mysqli_fetch_assoc($result))
{
	$q = "UPDATE devices SET parent_id='$row[id]' WHERE zone LIKE '%$row[unit]%'";
	$r = mysqli_query($connect,$q);
	if ($r) { 
		echo "$row[unit] updated records\n";
	}
	unset($r);
}
