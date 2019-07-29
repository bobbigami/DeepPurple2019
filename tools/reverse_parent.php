<?php
include ('/var/www/sql.php');

$query = "SELECT * FROM devices WHERE device_type='3'";
$result = mysqli_query($conn,$query)or die (mysqli_error($conn));
while ($row = mysqli_fetch_assoc($result)) { 
	$array[$row['id']] = $row['unit'];
}


print_r($array);

