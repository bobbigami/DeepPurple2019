<?php

session_start();
if (!isset($_SESSION['loggedIn'])) { 
	header("Location:index.php");
}


include ('library.php');

$query 	= "SELECT * FROM configuration"; 
$result	= mysqli_query($connect,$query);

$row	= mysqli_fetch_assoc($result);


echo '
<form name="config" method="post" action="checkconfig.php">
<table>
<tr>
	<td>Days to Report</td>
	<td>	<input type="checkbox" name="day[1]" value="Monday">Monday<br>
		<input type="checkbox" name="day[2]" value="Tuesday">Tuesday<br>
		<input type="checkbox" name="day[3]" value="Wednesday">Wednesday<br>
		<input type="checkbox" name="day[4]" value="Thursday">Thursday<br>
		<input type="checkbox" name="day[5]" value="Friday">Friday<br>
		<input type="checkbox" name="day[6]" value="Saturday">Saturday<br>
		<input type="checkbox" name="day[0]" value="Sunday">Sunday<br>
	</td>
</tr><tr>
	<td>Auto Add Sensors from E-Mail<td>
	<td><input type="radio" name="autoadd" value="1">Yes <input type="radio" name="autoadd" value="0">No
<tr><td colspan="2"><input type="submit" name="action" value="Configure">
		<input type="submit" name="action" value="Cancel"></td></tr>
</table>
</form>
';

