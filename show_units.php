<?php
session_start();
if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); }

include ('library.php');

$query	= "SELECT * FROM unit ORDER BY unit_name ASC";
$result	= mysqli_query($connect,$query) or die(mysqli_error($connect)); 

if (mysqli_num_rows($result) > 0) { 
	echo '
	<table>
	<tr>
		<th>Unit Name</th>
		<th>Floor</th>
		<th>Customer</th>
	</tr>
	';
	while ($row = mysqli_fetch_assoc($result))
	{
		echo '
		<tr>
			<td>'.$row['unit_name'].'</td>
			<td>'.$row['floor'].'</td>
			<td>Customer Info</td>
		</tr>
		';
	}
	echo '
	</table>
	';
}

