<?php
session_start();
if (!isset($_SESSION['loggedIn']))
{
	include ("login.php"); 
	die();
}

include ("library.php");
include ("header.php");

$hostname = gethostname();
$ip = gethostbyname($hostname);


echo '
<div align="center">
<div>&nbsp;</div>
'; 
if ($_SESSION['user']['userlevel'] == 255) { echo '<div>SERVER IP: '.$_SERVER['SERVER_ADDR'].'</div>'; } 

$file = file('version.txt');

echo '
<div><b>Meridian San Diego</b></div>
<div>Version: '.$file['0'].'</div>
<div>&nbsp;</div>
<img src="logo.jpg" width="50%" height="50%">
</div>
';

$query		= "SELECT * FROM customers";
$result		= mysqli_query($connect,$query);
$customers 	= mysqli_num_rows($result);

$query		= "SELECT * FROM devices";
$result		= mysqli_query($connect,$query);
$devices	= mysqli_num_rows($result);

$today		= mktime(0,0,1,date('n'),date('j'),date('Y'));

$query		= "SELECT * FROM email_log WHERE received >= '$today'";
$result		= mysqli_query($connect,$query) or die (mysqli_error($connect));
$todaylogs	= mysqli_num_rows($result);

$query		= "SELECT * FROM unknown";
$result		= mysqli_query($connect,$query);
$unknown	= mysqli_num_rows($result);


echo '
<div align="center">
<table bgcolor="grey">
<tr>
	<td align="right">Customers:</td><td>'.$customers.'</td>
</tr><tr>
	<td align="right">Devices:</td><td>'.$devices.'</td>
</tr><tr>
	<td align="right">Logs Today:</td><td>'.$todaylogs.'</td>
</tr><tr>
	<td align="right">Uknown Devices:</td><td>'.$unknown.'</td>
</tr>
</table>
</div>
';

