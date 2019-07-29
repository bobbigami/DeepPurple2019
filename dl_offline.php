<?php

session_start();
if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); }

include ('/var/www/html/library.php');

$query	= "SELECT * FROM devices
       	LEFT JOIN unit ON devices.unit_id=unit.unit_id 
	WHERE status='0'";
$result = mysqli_query($connect,$query); 

$out[] = "Whiskey,Tango,Foxtrot,Zulu\r\n";

while ($row = mysqli_fetch_assoc($result)) { 

	$array['serial'] = $row['serial'];
	$array['unit'] = $row['unit_floor'];
	$array['unit_name'] = $row['unit'];
	$array['datetime'] = date('Y-m-d H:i',$row['statdate']);

	$string	= implode(",",$array);
	$out[] = $string."\r\n";
}

file_put_contents("offline.csv",$out);

include ('header.php');

echo '<div align="center">
	<a href="offline.csv" class="reg">Download Offline CSV File.</a>
	</div>
	';

include ('footer.php');

