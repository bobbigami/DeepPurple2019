<?php

include ('../library.php');

$query = "SELECT * FROM email_log";
$result	= mysqli_query($connect,$query);

function get_serial($body)
{
	if (preg_match('/\(\w+\)/',$body,$match)) { 
		return ($match[0]);	
	} else { 
		return 1; 
	}
}

function get_RP($serial) 
{
	if (preg_match('/RP/',$serial)) 
	{
		return 1;
	} else { 
		return 0; 
	}
}

function get_clean_serial($serial)
{
	$pattern[0] = "/^RP/";
	$pattern[1] = "/^WT/";
	$replace = "";

	$serial = preg_replace($pattern,$replace,$serial);
	return $serial;
}




while ($row = mysqli_fetch_assoc($result)) { 

	//--
	$serialrp = get_serial($row['body']);
	if ($serialrp !== 1) { 
		if (get_RP($serialrp) == 1) { 
			$serial = get_clean_serial($serialrp);
			$q = "UPDATE devices SET device_type='2' WHERE id='$row[id]'";
			$r = mysqli_query($connect,$q);
			if ($r) { 
				echo "Updated Device Type for serial: $serial \n";
			} unset ($r); 

		}
	}
}	
