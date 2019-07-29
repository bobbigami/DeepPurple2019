<?php

// Add a Sensor Module to the DB.. 
// Bobbi Pierce (2019.06.30)

include ('library.php');
include ('header.php');

$id = $_POST['id'];
$query	= "SELECT * FROM devices WHERE id='$id'";
$result	= mysqli_query($connect,$query);
$row	= mysqli_fetch_assoc($result);





echo '
<div align="center">
<div>&nbsp;</div>
<div>&nbsp;</div>
<form name="add_device" method="post" action="check_data.php">
<input type="hidden" name="mode" value="edit">
<input type="hidden" name="id" value="'.$row['id'].'">

<table cellpadding="5" cellspacing="0" bgcolor="grey">
<tr>
	<td align="right">Serial Number:</td>
	<td><input type="text" name="serial" maxlength="10" value="'.$row['serial'].'">
</tr><tr>
	<td align="right">Floor:</td>
	<td><select name="floor">'.select_this("floor",$row['floor']).'</select></td>
</tr><tr>
	<td align="right">Unit:</td>
	<td><select name="unit_id">'.select_unit($row["unit_id"]).'</select></td>
</tr><tr>
	<td align="right">Unit Description:</td>
	<td><input type="text" name="unit" value="'.$row['unit'].'">
</tr><tr>
	<td align="right">Zone:</td>
	<td><textarea name="zone">'.$row['zone'].'</textarea></td>
</tr><tr>
	<td align="right">Device Type:</td>
	<td><select name="device_type">
		'.select_devicetype($row['device_type'],$devicearray).'</select>
		</td>
</tr><tr>
	<td align="right">Parent:</td>
	<td><select name="parent_id">'.select_parent($row['parent_id']).'</select></td>
</tr><tr>
	<td align="right">System Status:</td>
	<td><select name="enabled">
		<option value="0" '.(($row['enabled'] == 0) ? "selected" : "").'>Disabled</option>
		<option value="1" '.(($row['enabled'] == 1) ? "selected" : "").'>Enabled</option>
		</select>
		</td>
</tr><tr>
	<td align="right">Device Status:</td>
	<td><select name="status">
'.select_viaarray($row['status'],$statusarray).'
		</select>
	</td>
</tr><tr>
	<td align="right" colspan="2"><input type="submit" name="action" value="Submit">
			<input type="submit" name="action" value="Cancel">
	</td>
</tr>
</table>
</form>
</div>
</div>
<div style="height:50px;">&nbsp;</div>

';

$query = "SELECT * FROM email_log WHERE body LIKE '%$row[serial]%' ORDER BY received DESC";
$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
$rows = mysqli_num_rows($result);
echo '<div align="center">
	<div>Number of records in history: '.$rows.'</div>
	<table bgcolor="grey">';
while ($row = mysqli_fetch_assoc($result)) { 
	echo '
	<tr>
		<td>Date: '.date('Y-m-d H:i:s',$row['received']).'</td>
		<td>Body: '.preg_replace('/\s+/',' ',$row['body']).'</td>
	</tr>
	';
}
	echo '</table>
		</div>
		';




include ('footer.php');
