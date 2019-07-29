<?php

session_start();
if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); }

include ('library.php');

if ($_POST['action'] == "Cancel") { header("Location: show_users.php"); }

$userid	= mysqli_real_escape_string($connect,trim($_POST['userid']));
$username = mysqli_real_escape_string($connect,trim($_POST['username']));
$firstname = mysqli_real_escape_string($connect,trim($_POST['firstname']));
$lastname = mysqli_real_escape_string($connect,trim($_POST['lastname']));
$userlevel = mysqli_real_escape_string($connect,trim($_POST['userlevel']));
$password	= mysqli_real_escape_string($connect,trim($_POST['password']));

if ($_POST['action'] == "Edit") { 

	$query	= "UPDATE users SET userlevel='$userlevel', username='$username', password='$password', firstname='$firstname', lastname='$lastname' WHERE userid='$userid'";
	$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
	header("Location: show_users.php");
}

if ($_POST['action'] == "Add User") { 
	$query	= "SELECT * FROM users WHERE username='$username'";
	$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
	$numrow	= mysqli_num_rows($result); 
	if ($numrow == 0) { 
		$enabled = date('U');
		$query	= "INSERT INTO users (username,password,firstname,lastname,userlevel,enabled) VALUES ('$username','$password','$firstname','$lastname','$userlevel','$enabled')";
		$result	= mysqli_query($connect,$query) or die (mysqli_error($connect)); 
		$_SESSION['error'] = 'User Added.';
		header("Location: show_users.php");
	} else { 
		$_SESSION['error'] = "User Already Exists. Please try another.";
		header("Location: add_user.php");
	}
}

if ($_POST['action'] == "Delete") 
{

	print_r($_POST);
	if (isset($_POST['confirmdelete'])) { 
		$query = "DELETE FROM users WHERE userid='$userid'";
		$result= mysqli_query($connect,$query) or die (mysqli_error($connect));
	}
}
header("Location: show_users.php");
