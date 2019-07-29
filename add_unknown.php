<?php
session_start();
if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); } 

include('/var/www/html/library.php');
include ('header.php');

if (isset($_POST['serial'])) { 
	$serial = mysqli_real_escape_string($connect,$_POST['serial']);

	$query 	= "SELECT * FROM unknown WHERE serial='$serial'";
	$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
	if (mysqli_num_rows($result) >= 1) { 
		$row = mysqli_fetch_assoc($result);
		echo '
		<div align="center">
		<div style="height:50px;">&nbsp;</div>
		<div><h2>Add Unknown Device to System</h2></div>
		<div>The floor and other data will be added automatically.</div>
		<div>&nbsp;</div>
		<form name="add_unknown" method="post" action="check_unknown.php">
		<input type="hidden" name="status" value="'.$row['status'].'">
		<input type="hidden" name="device_type" value="'.$row['device_type'].'">
		<input type="hidden" name="received2" value="'.$row['received'].'">
		<table bgcolor="grey" cellpadding="5" cellspacing="0" border="1">
		<tr>
			<td>Serial Number:</td>
			<td><input type="text" name="serial" value="'.$serial.'" readonly></td>
		</tr><tr>
			<td>Received:</td>
			<td><input type="text" name="received" value="'.date('Y-m-d H:i:s',$row['received']).'" readonly></td>
		</tr><tr>
			<td>Unit - Name:</td>
			<td><input type="text" name="unit" value="'.$row['unit'].'" readonly></td>
		</tr><tr>
			<td>Select Unit:</td>
			<td><select name="unit_id">'.select_unit(NULL).'</select></td>
		</tr><tr>
			<td>Parent:</td>
			<td><select name="parent_id">'.select_parent(NULL).'</select></td>
		</tr><tr>
			<td>Zone:</td>
			<td><select name="zone">'.select_zone(NULL,$connect).'</select></td>
		</tr><tr>
			<td>Device Type:</td>
			<td><select name="device_type" readonly>'.select_devicetype($row['device_type'],$devicearray).'</select></td>
		</tr><tr>
			<td colspan="2"><input type="submit" name="action" value="Add Uknown Device"><input type="submit" name="action" value="Cancel"></td>
		</tr>
		</table>
		</form>
		';
	}
} else { 
	echo "<div>Something went horribly wrong! You should call someone!</div>";
}


include ('footer.php');
