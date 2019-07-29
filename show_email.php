<?php

session_start();
if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); }

include ('library.php');
include ('header.php');

if (isset($_POST['id'])) 
{ 
	$id = $_POST['id'];
	$query 	= "SELECT * FROM email_log WHERE id='$id'";
	$result	= mysqli_query($connect,$query);
	$row	= mysqli_fetch_assoc($result);
//	echo '<pre>'; print_r($row); echo '</pre>';	
	echo '
	<div align="center"> 
	<div>&nbsp;</div>
	<table bgcolor="grey">
		<tr>
		<td>
		<div>Date: '.date('Y-m-d H:i:s',$row['received']).'</div>
		<div>Email: '.$row['body'].'</div>
		</td>
		</tr>
		</table>
	</div>
	';
}
