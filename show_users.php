<?php

#Bobbi Pierce
#2019-7-16
#show_users.php
#
#Show the users configured for the building.
#


session_start();
if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); }

$_SESSION['page'] = "show_users.php";

include ('library.php');

$userlevel = $_SESSION['user']['userlevel']; 

$query 	= "SELECT * FROM users WHERE userlevel <= '$userlevel' ORDER BY username";
$result	= mysqli_query($connect,$query) or die(mysqli_error($connect));

if (mysqli_num_rows($result) > 0) { 
	include ('header.php');


	echo '<div align="center" style="padding:30px;">'; 
	echo '<div>&nbsp;</div>';
	echo '<div style="padding:15px;">
		<form method="post" action="add_user.php" class="inline">
		  <button type="submit" name="submit_param" value="submit_value" class="link-button">
		    Add User
		  </button>
		</form>
		</div>

	       			
		

	<table bgcolor="grey" border="1" cellspacing="0" cellpadding="5">
	<tr>
		<th>Last Name</th>
		<th>First Name</th>
		<th>Username</th>
	</tr>
	';
	while ($row = mysqli_fetch_assoc($result)) 
	{
		echo '
		<tr>
			<td>'.$row['lastname'].'</td>
			<td>'.$row['firstname'].'</td>
			<td>';
			if ($_SESSION['user']['userlevel'] > 250) { 
			echo '
			<form method="post" action="edit_user.php" class="inline">
			<input type="hidden" name="userid" value="'.$row['userid'].'">
			<button type="submit" name="action" value="edit" class="link-button">'.$row['username'].'</button></form>'; 
			} else { 
			echo $row['username']; 
			} 
			echo '</td>
		</tr>
		';
	}
	echo '
	</table>
';
	echo '</div>'; 
}

