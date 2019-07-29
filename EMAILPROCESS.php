<?php

	$datetime = date('U');


	$body 		= clean_body($body);
	$type		= get_notification_type($body);

	switch($type)
	{
	case "deviceoffline": 
		$info = do_deviceoffline($body);
		break;
	case "battery":
		$info = do_battery($body);
		//log_battery($info);
		break;
	case "inputwet":
		$info = do_inputwet($body);
		break;
	case "valvestack":
		$info = do_valvestack($body);
		break;
	case "wet":
		$info = do_wet($body);
		print_r($info);
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
		$info['message_id'] = $id; //$id is from quickstart.php
		$info['received'] = $received; //$received is from quickstart.php
		echo $info['notification']."\n";

		$string	= "=====\n";

		$count = count($info);
		$counted = 0;
		foreach($info as $k => $v) { 
			$counted++;
			$info[$k] = mysqli_real_escape_string($connect,$v);
			if($counted < $count) { 
				$string .= "$k=>$v,";
			} else { 
				$string	.= "$k=>$v";
			}
		}
		$string .= "======\n";
		$log[] = $string;
		// No ID == Unknown... Stuff it into the uknown universe and wait for someone 
		// to put it into the devices category. (still need to build that interface)
		if (!isset($info['id']) || $info['id'] == '') { 
			$query 	= "SELECT * FROM unknown WHERE unit LIKE '%$info[unit]%'";
			$result	= mysqli_query($connect,$query);
			if (mysqli_num_rows($result) == 0) { 
				if (!isset($info['status'])) { 
					$info['status'] = 0;
				}
				$query = "INSERT INTO unknown (received,body,device_type,serial,
							message_id,notification,unit,status)
				VALUES ('$info[received]','$info[body]','$info[device_type]',
					'$info[serial]','$info[message_id]',
					'$info[notification]','$info[unit]','$info[status]')";
				$result = mysqli_query($connect,$query) or die (mysqli_error($connect));
				if ($result) { 
					$log[] = "$query";
				}
			}
		} else {  

			if ($info['unit_id'] == '') { $info['unit_id'] = 0; }
			$query 	= "INSERT INTO email_log 
				(datetime,received,message_id,body,
				notification,serial,device_type,
				unit,unit_id,floor,status)
				VALUES
				('$datetime','$info[received]','$info[message_id]','$info[body]',
				'$info[notification]','$info[serial]','$info[device_type]',
				'$info[unit]','$info[unit_id]','$info[floor]','$info[status]')";
			$result = mysqli_query($connect,$query) or die (mysqli_error($connect));		
			if ($result) {
			       	$log[] = preg_replace('/\s+/',' ',$query)."\n";	

				// Update the Devices Table for Status.. 
				$q = "UPDATE devices SET 
					status='$info[status]',
					statdate='$info[received]'
					WHERE id='$info[id]'";
				$r	= mysqli_query($connect,$q) or die(mysqli_error($connect));

				/// Email User


				if ($r) { 
					$log[] = preg_replace('/\s+/',' ',$q)."\n";
				}
			}
		}
		file_put_contents("/var/www/html/log.log",$log, FILE_APPEND);
		unset($log);
	}
	
	unset($info);
