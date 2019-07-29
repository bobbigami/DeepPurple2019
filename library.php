<?php
include ('/var/www/sql.php');

$devicearray	= array('0'=>'None',
			'1'=>'Water',
			'2'=>'Repeater',
			'3'=>'Valve');

$statusarray	= array('0' => 'Offline',
			'1' => 'Online',
			'2' => 'Low Battery',
			'3' => 'Wet',
			'4' => 'Valve Change',
			'5' => 'Valve Open',
			'6' => 'Valve Closed'
			);

function offline($value)
{
	if ($value == 0) { $string = "Disabled"; } 
	if ($value == 1) { $string = "Enabled"; }

	return $string; 
}




function display_enabled($status)
{

	if ($status == 0)
	{
		$string = 'Disabled';
	} else { 
		$string = 'Enabled';
	}

	return $string;
}

function display_status($status)
{
	
	
	switch($status)
	{
	case 0:
		$string	= 'Offline';
		break;
	case 1:
		$string	= 'Online';
		break;
	default:
		break;
	}

	return $string;
}

function select_viaarray($status,$array)
{
	$string	 = '<option value="">Select One</option>';
	foreach($array as $k => $v) { 
		if ($status == $k) { 
			$string	.= '<option value="'.$k.'" selected>'.$v.'</option>';
		} else { 
			$string	.= '<option value="'.$k.'">'.$v.'</option>';
		}
	}
	return $string;
}

function select_this($name,$selected)
{

	include ('/var/www/sql.php');
	$query = "SELECT DISTINCT $name FROM devices ORDER BY $name";
	$result = mysqli_query($connect,$query);

	$string = '';

	while ($row = mysqli_fetch_assoc($result))
	{
		if ($row[$name] == $selected) { 
		$string .= "
		<option value=".$row[$name]." selected=true>".$row[$name]."</option>
		";
		} else { 
		$string .= "
		<option value=".$row[$name].">".$row[$name]."</option>
		";
		}
	}

	return $string;
}

function display_time2($datetime, $full = false)
{
	$datetime = "@$datetime";
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function display_time($seconds)
{
	$now = date('U');
  $dt1 = new DateTime("@$now");
  $dt2 = new DateTime("@$seconds");
  return $dt1->diff($dt2)->format('%a Days, %h Hrs, %i Mins');
}

function select_userlevel($level)
{
	$array = array(50=>'User',
			100=>'Power User',
			200=>'Administrator',
			255=>'Super User');

	$MyUserLevel = $_SESSION['user']['userlevel'];
	$string = '';
	foreach($array as $k => $v)
	{
		if ($k <= $MyUserLevel) { 
		if ($level == $k) { 
			$string .= '<option value="'.$k.'" selected>'.$v.'</option>';
		} else { 
			$string	.= '<option value="'.$k.'">'.$v.'</option>';
		}
		}
	}

	return $string; 
}


function select_customerunit($unit)
{
	if ($unit == NULL) { 
		$string = '<option>Select One</option>';
	} else { 
		$string = '';
	}
	include ('/var/www/sql.php');
	$query = "SELECT * FROM unit ORDER BY unit_floor,unit_name ASC";
	$result = mysqli_query($connect,$query) or die(mysqli_error($connect));

	while ($row = mysqli_fetch_assoc($result)) { 
		if ($unit == $row['unit_id']) { 
			$string .= '<option value="'.$row['unit_id'].'" selected>'.$row['unit_name'].'</option>';
		} else { 
			$string	.= '<option value="'.$row['unit_id'].'">'.$row['unit_name'].'</option>'; 
		}
	}

	return $string; 
}


function display_devicetype($type)
{
	$array = array( '0' => 'No Identification',
			'1' => 'Water',
			'2' => 'Repeater',
			'3' => 'VALVE');

	return $array[$type];
}

function select_devicetype($type,$array)
{
	$string	= '<option value="">Select One</option>';
	
	foreach($array as $k => $v) { 
		if ($type == $k) { 
			$string	.= "<option value=\"$k\" selected>$v</option>";
		} else { 
			$string	.= "<option value=\"$k\">$v</option>";
		}
	}
	return $string;
}
	

function select_unit($unit)
{
	$string = '<option value="">Select One</option>';
	$string	.= '<option value="0">None</option>';
	include ('/var/www/sql.php');
	$query	= "SELECT unit_id,unit_name FROM unit ORDER BY unit_name + 0";
	$result	= mysqli_query($connect,$query);
	while ($row = mysqli_fetch_assoc($result)) { 
		if ($row['unit_id'] == $unit) { 
			$string	.= '<option value="'.$row['unit_id'].'" selected>'.$row['unit_name'].'</option>';
		} else { 
			$string .= '<option value="'.$row['unit_id'].'">'.$row['unit_name'].'</option>';
		}
	}
	return $string; 
}

function select_parent($parent_id)
{
	$string	= '<option value="">Select One</option>';
	$string	.= '<option value="0">None</option>';
	include ('/var/www/sql.php');
	$query	= "SELECT id,unit FROM devices WHERE device_type in (3) ORDER BY unit";
	$result	= mysqli_query($connect,$query)or die (mysqli_error($connect));
	while ($row = mysqli_fetch_assoc($result)) { 
		if ($parent_id == $row['id']) { 
			$string	.= '<option value="'.$row['id'].'" selected>'.$row['unit'].'</option>';
		} else { 
			$string	.= '<option value="'.$row['id'].'">'.$row['unit'].'</option>';
		}
	}
	return $string;
}



//////////////////////////////////////
//NEW PROCESS FILE ///////////////////
//////////////////////////////////////

function clean_body($body)
{
	//clean all the multiple white spaces 
	$body = preg_replace('/\s+/',' ',$body);

	//clean beginning and end of whitespaces
	$pattern[0] = "/^\s/";
	$pattern[1] = "/\s$/";
	$pattern[2] = "/app\.ionleaks\.com /";
	$pattern[3] = "/This e-mail address is not monitored\. Please do not respond to this message\./";
	$replace = "";
	$body = preg_replace($pattern,$replace,$body);

	return $body;
}

function get_serial_info($body)
{
	if(preg_match('/\(\w+\)/',$body,$match)) { 
	
		$serial = $match[0];

		$pattern[0] = "/^\(RP/";
		$pattern[1] = "/^\(WT/";
		$pattern[3] = "/^\(/";
		$pattern[2] = "/\)$/";
		$replace	= "";

		$serial = preg_replace($pattern,$replace,$serial);
		$array['serial'] = $serial;

		if (preg_match('/RP/',$match[0])) { 
			$array['device_type'] = 2;
		}
		if (preg_match('/WT/',$match[0])) { 
			$array['device_type'] = 1;
		}
		if (!isset($array['device_type'])) { 
			$array['device_type'] = 0;
		}
	} elseif (preg_match('/Water Sensor Network \w+ Visit/',$body,$match)) { 
		$serial = preg_replace('/Water Sensor Network /','',$match[0]);
		$serial2 = preg_replace('/Visit/','',$serial);
		$serial = trim($serial2);
		$pattern[0] = '/RP/';
		$pattern[1] = '/WT/';
		$replace = '';
		$serial = preg_replace($pattern,$replace,$serial);
		$array['serial'] = $serial;

		if (preg_match('/RP/',$serial2)) { 
			$array['device_type'] = 2;
		}
		if (preg_match('/WT/',$serial2)) { 
			$array['device_type'] = 1;
		}
		if (!isset($array['device_type'])) { 
			$array['device_type'] = 0; 
		}
	} else { 
		$array['serial'] = "None";
		$array['device_type'] = "0";
	}

	return $array;
}


function get_notification_type($body)
{
	if (preg_match('/Device reported Low battery level/',$body)) { 
		$type = 'battery';
	}
	if (preg_match('/Device.*.Offline/',$body)) { 
		$type = 'deviceoffline';
	}
	if (preg_match('/Device.*.Online/',$body)) { 
		$type = 'deviceonline';
	}
	if (preg_match('/Water sensor is wet/',$body)) { 
		$type = 'wet';
	}
	if (preg_match('/Input is wet Details/',$body)) { 
		$type = 'inputwet';
	}
	if (preg_match('/System Floor \d+/',$body)) { 
		$type = 'valvestack';
	}
	if (preg_match('/Valve Control/',$body)) { 
		$type = 'valvecontrol';
	}
	if (preg_match('/Input closed/',$body)) { 
		$type ="inputclosed";
	}
	if (preg_match('/Input opened/',$body)) { 
		$type = 'inputopened';
	}
	return $type;
}


function do_deviceoffline($body)
{
	$body 			= preg_replace('/^Device /','',$body);
	$pos 			= strpos($body,"(");
	$array['unit']		= trim(substr($body,0,$pos));
	$serialinfo 		= get_serial_info($body);
	$array['serial']	= $serialinfo['serial'];
	$array['device_type']	= $serialinfo['device_type'];

	include ('/var/www/sql.php');
	$query	= "SELECT * FROM devices 
		LEFT JOIN unit ON devices.unit_id=unit.unit_id 
		LEFT JOIN customers ON customers.unit_id=unit.unit_id
		WHERE serial='$array[serial]'";
	$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
	if (mysqli_num_rows($result) > 0) { 
		$row	= mysqli_fetch_assoc($result);
		$row['status']	= "0";
		$row['notification']	= "Device Offline";
		ksort($row);
		return $row;
	} else { 
		$array['notification'] = 'Device Offline';
		$array['status'] = 0;

		return $array;
	}
}

function do_deviceonline($body)
{
	//does not exist today (2019-7-21)
}

function do_battery($body)
{
	$serialinfo		= get_serial_info($body);
	$array['serial']	= $serialinfo['serial'];
	$array['device_type']	= $serialinfo['device_type'];
	$array['status']	= '2';
	$array['notification']	= "Low Battery";
	

	include ('/var/www/sql.php');
	$query	= "SELECT * FROM devices 
		LEFT JOIN unit ON devices.unit_id=unit.unit_id 
		LEFT JOIN customers ON customers.unit_id=unit.unit_id 
		WHERE serial='$array[serial]'";
	$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
	$row	= mysqli_fetch_assoc($result);
	$row['notification'] = "Low Battery";

	$q = "SELECT * FROM battery WHERE serial='$array[serial]'";
	$r	= mysqli_query($connect,$q);
	$datenow	= $row['statdate'];
	if (mysqli_num_rows($r) == 0) { 
		$insert = "INSERT INTO battery (serial,firstcontact,counted,lastcontact) VALUES ('$array[serial]','$datenow','1','$datenow')";
		$insertq	= mysqli_query($connect,$insert);
	} elseif (mysqli_num_rows($r) > 0) { 
		$update = "UPDATE battery SET lastcontact='$datenow',counted=counted+1 WHERE serial='$array[serial]'";
		$updateq = mysqli_query($connect,$update);
	}	
	$row['battery_update'] = 1;

	ksort($row);
	return $row;

}

function do_inputwet($body)
{

	$device = preg_match('/Device: \d{3,4} - .*$/',$body,$match);
	$device = preg_replace('/Device: /','',trim($match[0]));
	include ('/var/www/sql.php');
	$query	= "SELECT * FROM devices 
		LEFT JOIN unit ON devices.unit_id=unit.unit_id 
		LEFT JOIN customers ON customers.unit_id=unit.unit_id
		WHERE unit LIKE '%$device%'";
	$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
	if ($result) { 
		$row = mysqli_fetch_assoc($result);
		$array['unit'] = $device;
		$array['serial'] = $row['serial'];
		$array['status'] = 3;
		$array['notification'] = "Input is wet";
		$array['device_type'] = $row['device_type'];
	} else { 
		$array[] = "No Device Found!";
	}
	$array = array_merge($array,$row);
	ksort($array);
	return $array;
}


function do_valvestack($body)
{
	$serialinfo 		= get_serial_info($body);
	$array['serial']	= $serialinfo['serial'];
	$array['device_type']	= $serialinfo['device_type'];
	$array['status']	= '0';
	$array['notification']	= "Valve Stack Offline";
	include ('/var/www/sql.php');
	$query	= "SELECT * FROM devices WHERE serial LIKE '%$array[serial]%'";
	$result	=  mysqli_query($connect,$query);
	if ($result){ 
		$row	= mysqli_fetch_assoc($result);
	} else { 
		$pos	= strpos($body,"(");
		$string	= substr($body,0,$pos);
		$pattern[0] = '/System/';
		$pattern[1] = '/Meridian/';
		$replace = '';
		$row['unit'] = preg_replace($pattern,$replace,$string);
	}
	$array = array_merge($array,$row);
	ksort($array);
	return $array;
}

function do_wet($body)
{
	$unit	= preg_match('/Device: [0-9]{3,4}.*$/',$body,$match);
	$unit	= preg_replace('/Device: /','',trim($match[0]));

	include ('/var/www/sql.php');
	$query	= "SELECT * FROM devices WHERE unit LIKE '$unit%'";
	$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
	if ($result) { 
		$row = mysqli_fetch_assoc($result);
		$array['unit'] = $row['unit'];
		$array['serial'] = $row['serial'];
		$array['notification'] = "Water Sensor is Wet";
		$array['device_type'] = $row['device_type'];
		$array['sql'] = 1;
	} else { 
		$array['sql'] = "0";
	}
	$array = array_merge($array,$row);
	$array['status'] = 3;
	ksort($array);
	return $array;
}

function do_valvecontrol($body)
{
	$device = preg_replace('/\- .*$/','',$body);
	$device = preg_replace('/^Zone /','',$device);

	include ('/var/www/sql.php');
	$query	= "SELECT * FROM devices WHERE unit LIKE '$device%'";
	$result	= mysqli_query($connect,$query);
	$row	= mysqli_fetch_assoc($result);
	$array['unit'] = $row['unit'];
	$array['serial'] = $row['serial'];
	$array['status'] = 4;
	$array['notification'] = "Valve Control Change";
	$array['device_type'] = $row['device_type'];
	$array['sql'] = 1;

	$array = array_merge($array,$row);
	$array['status'] = 4;
	if ($array['unit_id'] == '') { $array['unit_id'] = 0; }
	ksort($array);
	return $array;
}


function do_inputopened($body)
{
	$device = preg_replace('/^.*.Zone:/','',$body);
	$device = preg_replace('/ \- Valve Control.*$/','',trim($device));

	$deviceRS = preg_replace('/^.*Device:/','',$body);

	include ('/var/www/sql.php');
	$query	= "SELECT * FROM devices WHERE unit LIKE '$device%'";
	$result	= mysqli_query($connect,$query) or die(mysql_error($connect));
	if ($result) { 
		$row	= mysqli_fetch_assoc($result);
		$row['notification'] = "Valve Input Opened";
		$row['status'] = 5;
	} else { 
		$row['unit_id'] = 0;
	}	

	$row['unit'] = $device;
	$row['device_type'] = 3;
	ksort($row);
	return $row;
}


function do_inputclosed($body)
{
        $device = preg_replace('/^.*.Zone:/','',$body);
        $device = preg_replace('/ \- Valve Control.*$/','',trim($device));

        $deviceRS = preg_replace('/^.*Device:/','',$body);

	include ('/var/www/sql.php');
        $query  = "SELECT * FROM devices WHERE unit LIKE '$device%'";
        $result = mysqli_query($connect,$query) or die(mysql_error($connect));

        if ($result) {
		$row    = mysqli_fetch_assoc($result);
		$row['notification'] = "Valve Input Closed";
		$row['status'] = 6;
	} else { 
		$row['unit_id'] = 0;
	}

	$row['unit'] = $device;
	$row['device_type'] = 3;
	ksort($row);
	return $row;
}


function strip_unit($unit) 
{
	if (preg_match('/\-/',$unit)) { 
		$unit = explode(" - ", $unit);
	} 
	$unit = trim($unit[0]);
	return $unit;
}

function strip_floor($unit) 
{
	$len_floor = strlen($unit);
	if ($len_floor == 4) { 
		$floor = substr($unit,0,2);
	} elseif ($len_floor == 3) { 
		$floor = substr($unit,0,1);
	}
	return $floor;
}

function find_unitid($unit)
{
	include ('/var/www/sql.php');
	$query = "SELECT unit_id FROM unit WHERE unit_name LIKE '$unit%'";
	$result	= mysqli_query($connect,$query)or die (mysqli_error($connect));
	$row	= mysqli_fetch_assoc($result);

	return $row['unit_id'];
}

function select_zone($zone,$connect)
{
	$query = "SELECT DISTINCT (zone) FROM devices ORDER by zone";
	$result = mysqli_query($connect,$query) or die (mysqli_error($connect));

	$string	= '<option value="">Select One</option>';

	if ($result) { 
		while ($row = mysqli_fetch_assoc($result))
		{
			if ($zone == $row['zone']) { 
				$string	.= '<option value="'.$row['zone'].'" selected>'.$row['zone'].'</option>';
			} else { 
				$string	.= '<option value="'.$row['zone'].'">'.$row['zone'].'</option>';
			}
		}
	}

	return $string;
}

function sendmail($to,$subject,$message)
{
	$strRawMessage = "From: Leaks<leaks@bidermann.com>\r\n";
	$strRawMessage .= "To: $to\r\n";
	$strRawMessage .= 'Subject: =?utf-8?B?' . base64_encode($subject) . "?=\r\n";
	$strRawMessage .= "MIME-Version: 1.0\r\n";
	$strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
	$strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
##################
	$strRawMessage .= "$message\r\n";
// The message needs to be encoded in Base64URL
	$mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
	
	return $mime;
}


/*function log_battery($array)
{
	$serial = $array['serial'];
	include('/var/www/sql.php');
	$query = "SELECT * FROM battery WHERE serial='$serial'";
	$result	=  mysqli_query($connect,$query);
	$numrow	= mysqli_num_rows($result);
	$received	= $array['received'];
	if ($numrow > 0) { 
		$q = "UPDATE battery SET lastcontact='$received', counted=counted+1 WHERE serial='$serial'";
		$r = mysqli_query($connect,$q) or die (mysqli_error($connect));
	} else { 
		$q = "INSERT INTO battery (serial,firstcontact,counted,lastcontact) VALUES ('$serial','$received','1','$received')";
		$r = mysqli_query($connect,$q) or die (mysqli_error($connect));
	}
	echo "QUERY: $query \n";
	echo "Query: $q \n";
}
 */
