<?php

session_start();
if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); } 

include ('library.php');
include ('header.php');

$query	= "SELECT * FROM unknown";
$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));

if (mysqli_num_rows($result) > 0) { 
	echo '<div align="center">
		<div style="height:100px;">&nbsp;</div>
		<div><h3>Uknown Devices</h3></div>
		<table bgcolor="grey" cellpadding="5" cellspacing="0" border="1">
		<tr>
			<th>Received</th>
			<th>Unit</th>
			<th>Serial</th>
			<th>Device Type</th>
			<th>Notification</th>
			<th>E-Mail</th>
		</tr>
		';
	while ($row = mysqli_fetch_assoc($result)) { 
	echo '<tr>
			<td>'.date('Y-m-d H:i:s',$row['received']).'</td>
			<td>'.$row['unit'].'</td>
			<td><form name="addunknown" method="post" class="inline" action="add_unknown.php">
			<input type="hidden" name="status" value="'.$row['status'].'">
			<input type="hidden" name="device_type" value="'.$row['device_type'].'">
			<button type="submit" name="serial" value="'.$row['serial'].'" class="link-button">'.$row['serial'].'</button></form></td>
			<td>'.display_devicetype($row['device_type']).'</td>
			<td>'.$row['notification'].'</td>
			<td>'.$row['body'].'</td>
		</tr>
		';
	}
	echo '
	</table>
	</div>
	';
} else { 
	echo '<div align="center"><h1>No Uknown Devices</h1></div>';
}
