<?php

session_start();
if(!isset($_SESSION['loggedIn'])) { header("Location: index.php"); } 

include ('library.php');
include ('header.php');

echo '
<div>
<form method="post" action="add_customer.php" class="inline">
  <button type="submit" name="submit_param" value="submit_value" class="link-button">
    Add Customer
  </button>
</form>
</div>

';

$query	= "SELECT * FROM customers LEFT JOIN unit ON customers.unit_id=unit.unit_id ORDER BY cust_lastname";
$result	= mysqli_query($connect,$query) or die(mysqli_error($connect));
if (mysqli_num_rows($result) > 0) {


	echo '<div align="center">
	<div>&nbsp;</div>
	'; 
	if (isset($_SESSION['error'])) { echo '<div style="padding:10px; font-weight:bold;">'.$_SESSION['error'].'</div>'; unset($_SESSION['error']);  } 
	echo '
	<div style="width:80%;">

	<table cellpadding="3" cellspacing="0" border="1" bgcolor="grey" width="100%">
	<tr>
	<th>Lastname</th>
	<th>Firstname</th>
	<th>Unit</th>
	<th>floor</th>
	<th>E-Mail Address</th>
	<th>Text Number</th>
	<th>Notify</th>
	</tr>
	';	

	while ($row = mysqli_fetch_assoc($result)) { 
		if ($row['notifycustomer'] == 1) { $bgcolor = "green"; } else { $bgcolor="red"; }
		echo '
		<tr>
			<td><form method="post" action="edit_customer.php" class="inline">
                                <input type="hidden" name="id" value="'.$row['customer_id'].'">
				<input type="hidden" name="mode" value="edit">
                                <button type="submit" name="submit" value="Submit" class="link-button">
					'.$row['cust_lastname'].'
                                </button> ('.$row['customer_id'].')
                        </form>
				</td>
			<td>'.$row['cust_firstname'].'</td>
			<td>'.$row['unit_name'].'</td>
			<td>'.$row['unit_floor'].'</td>
			<td>'.$row['emailaddress'].'</td>
			<td>'.$row['textnumber'].'</td>
			<td bgcolor="'.$bgcolor.'">'.(($row['notifycustomer'] == 1) ? "Yes" : "No").'</td>
		</tr>
		';
	}
	echo '
	</table>
	</div>'; 
} else { 
	echo ' 
	<div>&nbsp;</div>
	<div>
		<table>
			<tr>
				<td>There are no customers.</td>
			</tr>
		</table>
	</div>
	';
}
