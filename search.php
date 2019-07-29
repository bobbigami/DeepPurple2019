<?php
session_start();

if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); } 
include ("library.php");

include ("header.php");


if (isset($_POST['search'])) { 
	$search = mysqli_real_escape_string($connect,$_POST['search']);
} else { 
	$search = '';
}


echo '<div>&nbsp;</div>'; 

echo '<div align="center"><h1>Search</h1></div>'; 

echo '
<div align="center">
<div>&nbsp;</div>
<form name="search" method="post" action="search.php">
<table>
<tr>
<td>Search Criteria:</td><td><input type="text" name="search" value="'.$search.'"></td>
</tr><tr>
<td colspan="2"><input type="submit" name="action" value="Search"></td>
</tr>
</table>
</form>
</div>
'; 


if ($search != '') { 
	$query = "SELECT * FROM devices 
		LEFT JOIN unit ON devices.unit_id=unit.unit_id 
		LEFT JOIN customers ON customers.unit_id=unit.unit_id  
		WHERE devices.unit_id LIKE '%$search%' OR devices.unit LIKE '%$search%'";
	$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
	while ($row = mysqli_fetch_assoc($result)) { 
		$array['devices'][] = $row;
	}

	$query	= "SELECT * FROM unit LEFT JOIN customers ON customers.unit_id=unit.unit_id WHERE unit_name LIKE '%$search%'";
	$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
	while ($row = mysqli_fetch_assoc($result)) { 
		$array['unit'][] = $row;
	}

	$query = "SELECT * FROM customers WHERE cust_lastname LIKE '%$search%' OR cust_firstname LIKE '%$search%' OR 
		emailaddress LIKE '%$search%' OR textnumber LIKE '%$search%'";
	$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
	while ($row = mysqli_fetch_assoc($result)) { 
		$array['customers'][] = $row;
	}

	$query	= "SELECT * FROM email_log WHERE body LIKE '%$search%'";
	$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
	while ($row = mysqli_fetch_assoc($result)) { 
		$array['email_log'][] = $row;
	}
}

if (isset($array)) {
	echo '<div align="center">';

	foreach($array as $table => $arg) { 
		echo '
		<div>'.$table.'</div>
		';
		foreach($arg as $k => $v) { 
			echo '<div>
				<table bgcolor="grey" width="1080" cellpadding="5" cellspacing="0">';
			foreach($v as $a => $b) { 
				echo '<tr><td align="right">'.$a.'</td><td>'.$b.'</td></tr>';
			}
			echo '</table></div><div>&nbsp;</div>';

		}
			echo '</div>'; 
			echo '<div>&nbsp;</div>';
	}
} else { 
	echo 'No Results Found.';
}
