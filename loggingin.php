<?php
session_start();

include ('library.php');

if (isset($_SESSION['error'])) { 
	echo '<center><h1>'.$_SESSION['error'].'</h1></center>';
}

print_r($_SESSION);

if (isset($_POST['username'])) { 
	if (isset($_POST['password'])) { 
		$username 	= mysqli_real_escape_string($connect,$_POST['username']);
		$password 	= mysqli_real_escape_string($connect,$_POST['password']);

		$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
		$result = mysqli_query($connect,$query) or die(mysqli_error($connect));

		if (mysqli_num_rows($result) == 1) { 
			$_SESSION['loggedIn'] = TRUE;
			$_SESSION['user'] = mysqli_fetch_assoc($result);
		} else { 
			$_SESSION['error'] = "User Login Info Not Valid";
		}
	}
}


header("Location: index.php");
