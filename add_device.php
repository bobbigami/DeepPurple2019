<?php

// Add a Sensor Module to the DB.. 
// Bobbi Pierce (2019.06.30)
session_start();
if (!isset($_SESSION['loggedIn'])) { 
	die("Sorry, you must first login.");
}

include ('library.php');


include ('header.php');

echo '
<form name="add_device" method="post" action="check_data.php">
<input type="hidden" name="mode" value="add">

<table>
<tr>
	<td>Serial Number:</td>
	<td><input type="text" name="serial" maxlength="10" value="">
</tr><tr>
	<td>Floor:</td>
	<td><select name="floor">'.select_this("floor",NULL).'</select></td>
</tr><tr>
	<td>Unit Description:</td>
	<td><input type="text" name="unit" value="">
</tr><tr>
	<td>Zone:</td>
	<td><textarea name="zone"></textarea></td>
</tr><tr>
	<td>System Status:</td>
	<td><select name="enabled">
		<option value="0">Disabled</option>
		<option value="1">Enabled</option>
		</select>
		</td>
</tr><tr>
	<td>Device Status:</td>
	<td><select name="status">
		<option value="0">Offline</option>
		<option value="1">Online</option>
		</select>
	</td>
</tr><tr>
	<td colspan="2"><input type="submit" name="action" value="Submit">
			<input type="submit" name="action" value="Cancel">
	</td>
</tr>
</table>
</form>
';

