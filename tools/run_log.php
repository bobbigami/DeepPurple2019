<?php 

include ('/var/www/html/library.php');

/*
 * This script parses the email log 
 */

$quak	= "SELECT * from email_log ORDER BY received";
$rez	= mysqli_query($connect,$quak) or die (mysqli_error($connect));

while ($row =  mysqli_fetch_assoc($rez)) { 

	$body = $row['body'];
	include ('EMAILPROCESS.php');

}


