<?php

include ('/var/www/html/library.php');

$query	= "SELECT * FROM devices ORDER BY floor, unit";
$result	= mysqli_query($connect,$query);
while ($row = mysqli_fetch_assoc($result)) { 
	print_r($row);
}
