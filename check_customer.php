<?php

session_start();
if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); }

include ('library.php');
include ('header.php');

if (isset($_POST['action']))
{
	if ($_POST['action'] == "Add Customer") 
	{
		$lastname = mysqli_real_escape_string($connect,$_POST['cust_lastname']);
		$firstname = mysqli_real_escape_string($connect,$_POST['cust_firstname']);
		$email	= mysqli_real_escape_string($connect,$_POST['emailaddress']);
		$text	= mysqli_real_escape_string($connect,$_POST['textnumber']);
		$unitid	= mysqli_real_escape_string($connect,$_POST['unit_id']); 
		if ($_POST['receivenotification'] == 'checked') { $notify_customer = 1;  } else { $notify_customer = 0; }

		if (filter_var($email, FILTER_VALIDATE_EMAIL)) { 
			$email = $email; 
		} else { 
			$email = '';
		}
		$userid = $_SESSION['user']['userid'];
		
		$query	= "INSERT INTO customers
				(
				unit_id,
				cust_lastname,
				cust_firstname,
				emailaddress,
				textnumber,
				enabled,
				created_by,
				created_on,
				notifycustomer)

				values 
				('$unitid','$lastname','$firstname','$email','$text','1','$userid','".date('U')."','$notify_customer')"; 
		echo "QUERY: $query <br>";
		$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));
		header("Location: show_customers.php");

	}
	if ($_POST['action'] == "Edit Customer") 
	{

		$cust_id	= mysqli_real_escape_string($connect,$_POST['customer_id']); 
                $lastname 	= mysqli_real_escape_string($connect,$_POST['cust_lastname']);
                $firstname 	= mysqli_real_escape_string($connect,$_POST['cust_firstname']);
                $email  = mysqli_real_escape_string($connect,$_POST['emailaddress']);
                $text   = mysqli_real_escape_string($connect,$_POST['textnumber']);
                $unitid = mysqli_real_escape_string($connect,$_POST['unit_id']);
		if ($_POST['notifycustomer'] == 'notifycustomer') { $notifycustomer = 1; } else { $notifycustomer = 0; }

		if (filter_var($email, FILTER_VALIDATE_EMAIL)) { 
			$email = $email;
		} else { 
			$email = '';
		}

		$userid=$_SESSION['user']['userid'];
		$modified = date('U');


		$query	= "UPDATE customers SET
				unit_id='$unitid',
				cust_lastname='$lastname',
				cust_firstname='$firstname',
				emailaddress='$email',
				textnumber='$text',
				modified='$modified',
				modified_by='$userid',
				notifycustomer='$notifycustomer'
				WHERE customer_id='$cust_id'";
		$result	= mysqli_query($connect,$query) or die (mysqli_error($connect)."QUERY:".$query);
		if ($result) { $_SESSION['error'] = "User Updated."; header("Location: show_customers.php"); }
	}
				
	if ($_POST['action'] == "Cancel") { header("Location: show_customers.php"); }


}

if ($_POST['action'] == "Delete") { 
	if (isset($_POST['confirmdelete'])) { 
		$query = "DELETE FROM customers WHERE customer_id='$_POST[customer_id]'";
		$result = mysqli_query($connect,$query) or die (mysqli_error($connect)); 
		if ($result) { $_SESSION['error']="User deleted."; } 
	} else { 
		$_SESSION['error'] = "You must select the confirm delete checkbox to delete a customer.";
	}
}

header("Location: show_customers.php");
