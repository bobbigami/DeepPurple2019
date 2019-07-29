<?php

include ('library.php');

$query	= "SELECT * FROM email_log WHERE processed='0' ORDER BY emaildatetime";
$result	= mysqli_query($connect,$query);
$numrows = mysqli_num_rows($result); 

$rowcount = 0; 
if (mysqli_num_rows($result) > 0) { 
	while ($row = mysqli_fetch_assoc($result)) { 
		$rowcount++;
		$new_array = array();

		$body 		= $row['body'];
		$mid 		= $row['message_id'];
		$emaildate 	= $row['emaildatetime'];
		$proctime 	= $row['datetime'];
		$id		= $row['id']; 


		$array	= explode("\n",trim($body));

		if (count($array) == 1) { $array = explode(" ",$array['0']); }

		// Parse the Long E-Mails
		foreach($array as $k => $v) 
		{
			if (trim($v) == "") { 
				unset($array[$k]);
			} else { 
				$array[$k] = trim($v);
			}
			if (preg_match('/ionleaks/',$v)) { 
				unset($array[$k]);
			}
			if (preg_match('/not monitored/',$v)) 
			{
				unset($array[$k]);
			}
			if (preg_match('/Device requested/',$v))
			{
				unset($array[$k]);
			}

			if (preg_match('/\(\w+\)/',$v,$bobbi)) { 
				$new_array['key'] = $bobbi['0'];
			}
			

		}

		$array = array_values($array);



		if ($array['0'] == "Device")
		{
			$Serial = array_search($new_array['key'],$array);
			if (isset($Serial))
			{
				$string = '';
				for($i = 1; $i < $Serial; $i++)
				{
					$string .= $array[$i]." "; 
				}
			}
			$new_array['Unit'] = $array['1'];
			$len_floor = strlen($new_array['Unit']);
			if ($len_floor == 4) { 
				$new_array['floor'] = substr($new_array['Unit'],0,2);
			} elseif ($len_floor == 3) { 
				$new_array['floor'] = substr($new_array['Unit'],0,1);
			}
			$new_array['Device'] = trim($string);
			$new_array['Status'] = ucwords(str_replace(".","",strtolower(trim(end($array)))));
					
		}

		///////////////////////////////////////////////////////////////////////
		// SYSTEM NOTIFICATIONS
		// ////////////////////////////////////////////////////////////////////

		if ($array['0'] == "System") { 
			$Serial = array_search($new_array['key'],$array);
			if (isset($Serial))
			{
				$string = '';
				for ($i = 1; $i < $Serial; $i++)
				{
					$string	.= $array[$i]." ";
				}
			}
			$new_array['Device'] = trim($string);
			$new_array['Floor'] = $array['2'];
			$new_array['Status'] = str_replace(".","",strtolower(trim(end($array))));	
		}

		//////////////////////////////////////////////////////////////////////////
		// Battery Notifications
		// ///////////////////////////////////////////////////////////////////////
		
		if ($array['0'] == "Device reported") 
		{
			$new_array['SerialLong'] = $array['3'];
			preg_match('/[a-z0-9]+/',$new_array['SerialLong'],$matches);
			$new_array['Serial'] = $matches['0'];
			$split_array = explode(" - ",$array['2']);
			$new_array['Status'] = $array['2'];
			$new_array['Device'] = $split_array['1'];
			$new_array['Floor'] = trim(substr($split_array['0'],-3));

		}	

		/////////////////////////////////////////////////////////////////////////////
		// General NOTIFICATION
		// //////////////////////////////////////////////////////////////////////////

		if ($array['0'] == "Notification") 
		{
			$new_array['Device'] = end($array);
			if ($array['4'] == "Zone:") { 
				$split_array = explode (" - ", $array['5']);
				$new_array['Stack'] = $split_array['1'];
				$new_array['Floor'] = trim(substr($split_array['0'],-3));
			} elseif ($array['5'] == "Zone:") { 
				$split_array = explode (" - ", $array['6']);
				$new_array['Stack'] = $split_array['1'];
				$new_array['Floor'] = trim(substr($split_array['0'],-3));
			}
			$new_array['Unit'] = substr($new_array['Device'],0,4);
		}



		
		if (isset($Serial)) { 
			preg_match('/[a-z0-9]+/',$array[$Serial],$serial);
			$new_array['SerialLong'] = $array[$Serial];
			$new_array['Serial'] = $serial['0'];
			$new_array['SKEY'] = $Serial;
		}

		//print_r($new_array);
		unset($array['Offline']);
		unset($Offline);
		unset($Serial);
		echo $rowcount ."\r\n";


		if (isset($new_array['Serial'])) { 
			$q = "SELECT * FROM devices WHERE serial LIKE '%$new_array[Serial]%'";
		} else { 
			$q = "SELECT * FROM devices WHERE unit LIKE '%$new_array[Device]%'";
		}

		$r	= mysqli_query($connect,$q) or die (mysqli_error($connect));
		if ($r) { 
			$rw = mysqli_fetch_assoc($r);
			if ($new_array['Status'] == "Offline") { 
				$qq = "UPDATE devices SET status=0,statdate='".$row['emaildatetime']."' WHERE id='$rw[id]'";
			} elseif ($new_array['Status'] == "Online") { 
				$qq = "UPDATE devices SET status=1,statdate='".$row['emaildatetime']."' WHERE id='$rw[id]'";
			}
			$rr = mysqli_query($connect,$qq) or die(mysqli_error($connect));
			echo "Query: $qq \n";
		} else { 
			$error[] = "Could not find device for $new_array[Device] || $new_array[Serial]";
		}

		$q = "UPDATE email_log SET processed='1' WHERE id='$id'";
		$r = mysqli_query($connect,$q); 
	}


}

if (isset($error))
{
	print_r($error);
}
