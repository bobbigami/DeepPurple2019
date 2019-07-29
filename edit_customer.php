<?php

session_start();
if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); }

include ('library.php');



if (!isset($_POST['mode'])) { 
	header("Location: show_customers.php");
} else { 

	if (!isset($_POST['id']))
	{
		header("Location: show_customers.php");
	} else { 
		include ('header.php');

		$id	= mysqli_real_escape_string($connect,$_POST['id']);

		$query	= "SELECT * FROM customers LEFT JOIN unit ON customers.unit_id=unit.unit_id WHERE customer_id='$id'";
		$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
		$row	= mysqli_fetch_assoc($result);

		echo '
		<div align="center">
		<form name="edit_customer" method="post" action="check_customer.php">
		<input type="hidden" name="customer_id" value="'.$_POST['id'].'">
		<table bgcolor="grey">
		<tr>
 		       	<th>Last Name:</th>
	        	<th>First Name:</th>
	        	<td>Unit</td>
		        <th>E-Mail Address:</th>
		        <th>Text Number:</th>
			<th>Receive Notifications</th>
			'.(($_SESSION['user']['userlevel'] >= 100) ? "<th>Delete?</th>" :"" ).'
		</tr>
		<tr>
		        <td><input type="text" name="cust_lastname" value="'.$row['cust_lastname'].'"></td>
	        	<td><input type="text" name="cust_firstname" value="'.$row['cust_firstname'].'"></td>
		        <td><select name="unit_id">'.select_customerunit($row['unit_id']).'</select></td>
		        <td><input type="text" name="emailaddress" value="'.$row['emailaddress'].'"></td>
		        <td><input type="text" name="textnumber" value="'.$row['textnumber'].'"></td>
	        	<td><input type="checkbox" name="notifycustomer" value="notifycustomer" '.(($row['notifycustomer'] == 1) ? "checked" : "").'></td>
			'.(($_SESSION['user']['userlevel'] >= 100) ? '<td><input type="checkbox" name="confirmdelete" value="1"></td>' : "").'
		</tr>
			<tr>
			<td colspan="6"><input type="submit" name="action" value="Edit Customer">
			'.(($_SESSION['user']['userlevel'] >= 100) ? '<input type="submit" name="action" value="Delete">' : "").'<input type="submit" name="action" value="Cancel"></td>
		</tr>
		</table>
		</form>
		</div>
		';

	}
}

