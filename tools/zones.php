<?php 

include ('../library.php');

$query = "SELECT DISTINCT(zone) FROM devices ORDER BY zone ASC";
$result	= mysqli_query($connect,$query);

while ($row = mysqli_fetch_assoc($result)) { 

	$array[] = $row['zone'];

}
sort($array);

	foreach($array as $k => $v) { 

		$arg = explode("-",$v);
		foreach($arg as $key => $value) 
		{
			if (preg_match('/Floor/',$value)) { 
				$floor = preg_replace('/Floor /','',$value);
			}
			$arg[$key] = trim($value);
		}

		$new_array[$k]['zone_floor'] = $floor;
		$new_array[$k]['zone_data'] = $v;
		unset($floor);
	}

foreach($new_array as $k => $arg) 
{ 

	foreach($arg as $key => $value) { 
		if ($value == '') { 
			$arg[$key] = 0;
		}
	}

	$query	= "INSERT INTO zones (zone_floor,zone_data) VALUES ('$arg[zone_floor]','$arg[zone_data]')";
	$result	= mysqli_query($connect,$query);
	if ($result) { 
		echo "Zone data entered for $arg[zone_data] \n";
	} else { 
		echo mysqli_error($connect)."\n";
	}
	unset($result);
}
