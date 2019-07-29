<?php
session_start();
if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); }

if (isset($_POST['action'])) { 
	if ($_POST['action'] == "Cancel") { 
		header("Location: show_unknown.php");
	}
}

include ('/var/www/html/library.php');
include ('header.php');

print_r($_POST);

$a['unit']		= strip_unit($_POST['unit']);
$a['floor']		= strip_floor($a['unit']);
$a['device_type'] 	= $_POST['device_type'];
$a['serial']		= $_POST['serial'];
$a['zone']		= $_POST['zone'];
$a['statdate']		= $_POST['received'];
$a['enabledate']	= date('U');
$a['parent_id']		= $_POST['parent_id'];
$a['unit_id']		= find_unitid($a['unit']);
$a['unit'] 		= $_POST['unit'];
$a['status']		= $_POST['status'];
$a['statdate']		= $_POST['received2'];
$a['enabled']		= 1;
$a['enabledate']	= date('U');

$query	= "INSERT INTO devices
	(device_type,
	parent_id,
	unit_id,
	floor,
	serial,
	unit,
	zone,
	enabled,
	status,
	statdate,
	enabledate)

	VALUES
	('$a[device_type]',
	'$a[parent_id]',
	'$a[unit_id]',
	'$a[floor]',
	'$a[serial]',
	'$a[unit]',
	'$a[zone]',
	'$a[enabled]',
	'$a[status]',
	'$a[statdate]',
	'$a[enabledate]')";

$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
if ($result) { 
	$q = "DELETE FROM unknown WHERE serial='$a[serial]'";
	$r	= mysqli_query($connect,$q);
	if ($r) { 
		$_SESSION['floor'] = $a['floor'];
		header("Location: show_devices.php");
	} else { 
		echo "Problem deleting uknown entry.";
	}
} else { 
	mysqli_error($connect);
}
