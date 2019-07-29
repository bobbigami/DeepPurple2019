<?php
include ('/var/www/sql.php');

$query = "SELECT DISTINCT(message_id) FROM email_log";
$result	= mysqli_query($connect,$query);

while ($row = mysqli_fetch_assoc($result)) { 


	$q = "SELECT id FROM email_log WHERE message_id='$row[message_id]'";
	$r = mysqli_query($connect,$q);
	while ($rr1 = mysqli_fetch_assoc($r)) { 

		$message_array[$row['message_id']][] = $rr1['id'];
	}
}

$deleted = 0; 
foreach($message_array as $k => $v) { 

	if (count($v) > 1) { 
		foreach($v as $num => $id) { 
			$query = "DELETE FROM email_log WHERE id='$id'";
			$result	= mysqli_query($connect,$query);
			if ($result) { $deleted++; }
		}
	}
}

echo "Deleted $deleted rows \n";

