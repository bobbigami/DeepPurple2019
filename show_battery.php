<?php

session_start();
if (!isset($_SESSION['loggedIn'])) { 
	header("Location: index.php");
}

include ('library.php');
include ('header.php');

if (isset($_POST['action'])) { 
	if ($_POST['action'] == "Clear Battery Warnings") 
	{
		foreach($_POST['serial'] as $k => $v) { 
			$query = "DELETE FROM battery WHERE serial='$v'";
			$result = mysqli_query($connect,$query);
		}
	}
}



$query	= "SELECT * FROM battery LEFT JOIN devices ON battery.serial=devices.serial
		LEFT JOIN unit ON devices.unit_id=unit.unit_id ORDER BY counted,unit_floor";

$result	= mysqli_query($connect,$query) or die(mysqli_error($connect));
$numrow = mysqli_num_rows($result); 

if ($numrow == 0) { 

	echo '<div align="center" style="padding:15px;">
		<table bgcolor="grey">
		<tr><td>No Low Batteries to report.</td></tr>
		</table>
		';
} else { 
	echo '
	<div align="center">
	<div style="padding:25px;">&nbsp;</div> 
	<form name="clearbattery" action="show_battery.php" method="post">
	<table border="1" cellpadding="5" cellspacing="0" bgcolor="grey">
	<tr>
		<th>Serial Number</th>
		<th>Unit</th>
		<th>First Contact</th>
		<th>Counted</th>
		<th>Last Contact</th>
	</tr>
	';
	while ($row = mysqli_fetch_assoc($result)) { 
	echo '
	<tr>
		<td><input type="checkbox" name="serial[]" value="'.$row['serial'].'"> '.$row['serial'].'</td>
		<td>'.$row['unit'].'</td>
		<td>'.date("Y-m-d H:i",$row['firstcontact']).'</td>
		<td>'.$row['counted'].'</td>
		<td>'.date('Y-m-d H:i',$row['lastcontact']).'</td>
	</tr>
	';
	}
	echo '
	<tr><td colspan="5" align="center"><input type="submit" name="action" value="Clear Battery Warnings"></td></tr>
	</table>
	</div>
	';
}

include ('footer.php');
