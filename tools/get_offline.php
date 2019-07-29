<?php

include ('/var/www/html/library.php');

$query	= "SELECT * FROM devices
       	LEFT JOIN unit ON devices.unit_id=unit.unit_id 
	WHERE status='0'";
$result = mysqli_query($connect,$query); 

while ($row = mysqli_fetch_assoc($result)) { 

	$array['serial'] = $row['serial'];
	$array['unit'] = $row['unit_floor'];
	$array['unit_name'] = $row['unit'];
	$array['datetime'] = date('Y-m-d H:i',$row['statdate']);

	$out[] = $array;
}

print_r($out);

