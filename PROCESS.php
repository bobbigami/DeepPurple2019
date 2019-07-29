<?php

include ('/var/www/html/library.php');

$datetime = date('U');

$query	= "SELECT * FROM email_log ORDER BY emaildatetime";
$result	=  mysqli_query($connect,$query);
$numrows	= mysqli_num_rows($result); 
// Log
$log[] = date('Y-m-d H:i:s',$datetime)."Processing $numrows records";


//Process Dataset
while ($row = mysqli_fetch_assoc($result)) { 

	$body 		= clean_body($row['body']);
	$type		= get_notification_type($body);

	switch($type)
	{
	case "deviceoffline": 
		$info = do_deviceoffline($body);
		break;
	case "battery":
		$info = do_battery($body);
		break;
	case "inputwet":
		$info = do_inputwet($body);
		break;
	case "valvestack":
		$info = do_valvestack($body);
		break;
	case "wet":
		$info = do_wet($body);
		break;
	case "valvecontrol":
		$info = do_valvecontrol($body);
		break;
	case "inputopened":
		$info = do_inputopened($body);
		break;
	case "inputclosed":
		$info = do_inputclosed($body);
		break;
	default:
		break;
	}
	if (isset($info)) { 
		$info['body'] = $body;
		print_r($info);
	}

	unset($info);
}
