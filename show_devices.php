<?php
session_start();
if (!isset($_SESSION['loggedIn']))
{
	header("Location: index.php");
}
include ("library.php");
include ('header.php');

if (isset($_SESSION['floor'])) { 
	if (!isset($_POST['floor'])) { 
		$_POST['floor'] = $_SESSION['floor'];
	}
}


if (!isset($_POST['floor'])) { $floor = "*"; } else { $floor = $_POST['floor']; $_SESSION['floor'] = $_POST['floor']; }

if ($floor !== "*" ) {
	$query	= "SELECT * FROM devices 
		LEFT JOIN unit ON devices.unit_id=unit.unit_id 
		WHERE enabled='1' AND devices.floor = '$floor' ORDER BY devices.floor ASC,unit ASC";
} else {
	$query = "SELECT * FROM devices 
		LEFT JOIN unit ON devices.unit_id=unit.unit_id 
		WHERE enabled='1' ORDER by devices.floor ASC";
}

$result = mysqli_query($connect,$query)or die (mysqli_error($connect));

while ($row = mysqli_fetch_assoc($result))
{
	$db_array[] = $row;
	if ($row['status'] == 0) { $offline[$row['serial']] = $row; } else { $online[$row['serial']] = $row; }
	if ($row['device_type'] == 3) { 
		$parent[$row['id']] = $row['unit'];
	}
}
$online_count = count($online);
$offline_count	= count($offline);

echo '
<div align="center">
<div>&nbsp;</div>

<div>
<table width="1200" bgcolor="grey">
<tr><td><form name="initial" method="post" action="show_devices.php"><select name="floor" onChange="this.form.submit()"><option value="*">All</option>'.select_this("floor",$floor).'</select> &nbsp; Select Floor </form> (Online: '.$online_count.') :: (Offline: '.$offline_count.')</td>
</tr>
</table>
</div>


<table cellpadding="3" cellspacing="0" border="1" width="1200" bgcolor="grey">
<tr>
	<th>Serial</th>
	<th>Floor</th>
	<th>Unit</th>
	<th>Device Type</th>
	<th>Parent</th>
	<th>Unit Description</th>
	<th>System Status</th>
	<th>Up/Down Time</th>
</tr>
';
$device_type = array('1'=>'Water Sensor',
			'2'=>'Repeater | WS',
			'3'=>'Valve');
foreach($db_array as $serial => $array)
{
	switch($array['status']) 
		{
		case 0:
			$bgcolor = '#990000;';
			break;
		case 1:
			$bgcolor = 'grey';
			break;
		case 3:
			$bgcolor = '#0FF;';
			break;
		case 4:
			$bgcolor = '#001122;';
			break;
		case 5:
			$bgcolor = '#335511;';
			break;
		case 6:
			$bgcolor = '#551351;';
			break;
		default:
			break;
		}

	echo "
	<tr style='background-color:".$bgcolor.";'>
		<td align='center'>
			"; 
			if ($_SESSION['user']['userlevel'] > 100) { 
			echo "
			<form method='post' action='edit_device.php' class='inline'>
				<input type='hidden' name='id' value='$array[id]'>
  				<button type='submit' name='submit' value='Submit' class='link-button'>
					$array[serial]
  				</button>
			</form>";
			} else { 
				echo $array['serial'];
			}
			echo "
			</td>
		<td align='center'>$array[floor]</td>
		<td>$array[unit_name]</td>
		<td>".display_devicetype($array['device_type'])."</td>
		<td>".(isset($parent[$array['parent_id']]) ? $parent[$array['parent_id']] : "No Parent")."</td>
		<td>$array[unit]</td>";
		switch($array['status']) 
		{
		case 0:
			$bgcolor = '#990000;';
			break;
		case 1:
			$bgcolor = 'green';
			break;
		case 3:
			$bgcolor = '#0FF;';
			break;
		case 4:
			$bgcolor = '#001122;';
			break;
		case 5:
			$bgcolor = '#335511;';
			break;
		case 6:
			$bgcolor = '#551351;';
			break;
		default:
			break;
		}
		echo "
		<td align='center' style='background-color:".$bgcolor.";'>".$statusarray[$array['status']]." (".$array['status'].")</td>
		<td align='center'>".(($array['status'] == '0') ? display_time($array['statdate']) : display_time($array['statdate']))."</td>
	</tr>
	";
}

echo '
</table>
</div>
';


include ('footer.php');
