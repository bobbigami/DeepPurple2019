<?php

include ('/var/www/html/library.php');

$query	= "SELECT * FROM email_log WHERE body LIKE '%battery%'";
$result	= mysqli_query($connect,$query);
while ($row =  mysqli_fetch_assoc($result)) { 
	log_battery($row['serial']);	
} 
