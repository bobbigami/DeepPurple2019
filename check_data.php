<?php
session_start();

include ('library.php');
unset($_SESSION['error']);

//echo '<pre>'; print_r($_POST); echo '</pre>';
$_POST['serial'] = trim($_POST['serial']);

if ($_POST['action'] == "Cancel") { 
	$_SESSION['error'] = "Canceled Transaction";
	header("Location: show_devices.php");
}


if (($_POST['serial'] == NULL) || $_POST['serial'] == "" || $_POST['serial'] == " ") { 
	$_SESSION['error'] = "One or more input variables are invalid or empty. Please try again.";
	header("Location: index.php");
}

	$serial 	= mysqli_real_escape_string($connect,strtolower($_POST['serial']));
	$unit 		= mysqli_real_escape_string($connect,$_POST['unit']);
	$zone		= mysqli_real_escape_string($connect,$_POST['zone']);


	/////   ADD   ///////


if($_POST['mode'] == 'add' ) { 

	$query		= "INSERT INTO devices (floor,serial,unit,devicetype,repeater,zone,enabled,status) VALUES ('$_POST[floor]','$serial','$unit','$_POST[devicetype]','$_POST[repeater]','$zone','$_POST[enabled]','$_POST[status]')";


	////////   EDIT ////////////////

} elseif ($_POST['mode'] == 'edit' && $_POST['action'] == "Submit") { 



	$query 		= "UPDATE devices SET floor='$_POST[floor]',
						serial='$serial',
						unit='$unit',
						zone='$zone',
						device_type='$_POST[device_type]',
						parent_id='$_POST[parent_id]',
						unit_id='$_POST[unit_id]',
						enabled='$_POST[enabled]',
						status='$_POST[status]'
					WHERE id='$_POST[id]'"; 
						

}
	if (!isset($_SESSION['error'])) { 
		if (isset($query)) { $result = mysqli_query($connect,$query) or die (mysqli_error($connect)); }
		if ($result) { 
			header("Location: show_devices.php");
		} else { 
			echo "ERROR: Something went horribly wrong.";
		}
	}



