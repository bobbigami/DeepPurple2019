<?php
include ('../library.php');

$query	= "SELECT * FROM email_log";
$result = mysqli_query($connect,$query);
while ($row = mysqli_fetch_assoc($result)) { 

	$body = preg_replace('/\s+/',' ',$row['body']);
	$q = "UPDATE email_log SET body='$body' WHERE id='$row[id]'";
	$r = mysqli_query($connect,$q);
}
