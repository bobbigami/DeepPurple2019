<?php

session_start();
if (!isset($_SESSION['loggedIn'])) { 
	header("Location: index.php");
}
$_SESSION['page'] = "show_users.php";

include ('library.php');
include ('header.php');

if ((isset($_POST['action'])) && ($_POST['action'] == "edit")) { 
	if (isset($_POST['userid'])) { 
		$userid = mysqli_real_escape_string($connect,$_POST['userid']);
		$query = "SELECT * FROM users WHERE userid='$userid'";
		$result	= mysqli_query($connect,$query); 
		$row	= mysqli_fetch_assoc($result); 

		echo '
		<div align="center">
		<div>Editing User:</div>
		<div>&nbsp;</div>

		<form method="post" name="edituser" action="check_user.php">
		<input type="hidden" name="userid" value="'.$userid.'">
		<table>
		<tr>
			<td>Username:</td><td><input type="text" name="username" value="'.$row['username'].'"></td>
		</tr><tr>
			<td>First Name:</td><td><input type="text" name="firstname" value="'.$row['firstname'].'"></td>
		</tr><tr>
			<td>Last Name:</td><td><input type="text" name="lastname" value="'.$row['lastname'].'"></td>
		</tr><tr>
			<td>Password:</td><td><input type="password" name="password" value="'.$row['password'].'"></td>
		</tr><tr>
			<td>User Level:</td>
			<td><select name="userlevel">'.select_userlevel($row['userlevel']).'</select>
									</td>
		</tr><tr>
			<td colspan="2"><input type="submit" name="action" value="Edit"><input type="submit" name="action" value="Cancel">
				<input type="submit" name="action" value="Delete"><input type="checkbox" name="confirmdelete" value="confirmdelete"> Confirm Deletion</td>
		</tr>
		</table>
		</form>
		';
	}
}

