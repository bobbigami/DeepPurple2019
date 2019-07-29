<?php

session_start();
if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); }

include ('library.php');
include ('header.php');

echo '
<div align="center">
<div>&nbsp;</div>
<div>&nbsp;</div>
<form name="add_customer" method="post" action="check_customer.php">

<table bgcolor="grey">
<tr>
	<th>Last Name:</th>
	<th>First Name:</th>
	<td>Unit</td>
	<th>E-Mail Address:</th>
	<th>Text Number:</th>
	<th>Receive Notifications</th>
</tr>
<tr>
	<td><input type="text" name="cust_lastname"></td>
	<td><input type="text" name="cust_firstname"></td>
	<td><select name="unit_id">'.select_customerunit(NULL).'</select></td>
	<td><input type="text" name="emailaddress"></td>
	<td><input type="text" name="textnumber"></td>
	<td><input type="checkbox" name="receivenotification" value="checked"></td>
</tr>
<tr>
	<td colspan="6" align="center"><input type="submit" name="action" value="Add Customer"><input type="submit" name="action" value="Cancel"></td>
</tr>
</table>
</form>
</div>
';


