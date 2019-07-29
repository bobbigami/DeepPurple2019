<?php

session_start();
if (!isset($_SESSION['loggedIn'])) { 
	header("Location: index.php");
}
if (isset($_POST['userid'])) { $userid = $_POST['userid']; } else { $userid = NULL; }

include ('library.php');
include ('header.php');

		echo '
		<div align="center">
		';
		if (isset($_SESSION['error'])) { echo '$_SESSION[error]'; } 
		unset($_SESSION['error']);
		echo '
		<div>Editing User:</div>
		<div>&nbsp;</div>

		<form method="post" name="adduser" action="check_user.php">
		<input type="hidden" name="userid" value="'.$userid.'">
		<table>
		<tr>
			<td>Username:</td><td><input type="text" name="username" value=""></td>
		</tr><tr>
			<td>First Name:</td><td><input type="text" name="firstname" value=""></td>
		</tr><tr>
			<td>Last Name:</td><td><input type="text" name="lastname" value=""></td>
		</tr><tr>
			<td>Password:</td><td><input type="password" name="password" value=""></td>
		</tr><tr>
			<td>User Level:</td>
			<td><select name="userlevel">'.select_userlevel(NULL).'</select>
									</td>
		</tr><tr>
			<td colspan="2"><input type="submit" name="action" value="Add User"><input type="submit" name="action" value="Cancel"></td>
		</tr>
		</table>
		</form>
		</div>
		';

include ('footer.php');
