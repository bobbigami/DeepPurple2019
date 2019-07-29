<?php
#Bobbi Pierce 2019.7.11
#

session_start();
if (!isset($_SESSION['loggedIn']))
{
	header("Location: index.php");
}

include ('library.php');

$query = "SELECT * FROM email_log ORDER BY received DESC";
$result	= mysqli_query($connect,$query) or die (mysqli_error($connect));

$rows = mysqli_num_rows($result);
if (mysqli_num_rows($result)> 0) {
       include ('header.php');	
       echo '<div align="center">
	       <div>&nbsp;</div>
		<div>Records: '.$rows.'</div>';
	echo '
	<table cellpadding="3" cellspacing="0" border="1" bgcolor="grey">
	<tr>
		<th>Processed</th>
		<th>E-Mail Sent</th>
		<th>Notification</th>
		<th>Message ID</th>
		<th>Snippet</th>
	</tr>
	';
	while ($row = mysqli_fetch_assoc($result)) {
		if (strlen($row['body']) > 70) { 
			$row['body'] = substr($row['body'],0,75)."..."; 
		}
		echo '
		<tr>
			<td>'.date('Y-m-d H:i:s',$row['datetime']).'</td>
			<td>'.date('Y-m-d H:i:s',$row['received']).'</td>
			<td>'.$row['notification'].'</td>
			<td>'.$row['message_id'].'</td>
			<td><form name="show_email" method="post" class="inline" action="show_email.php">
				<button type="submit" name="id" value="'.$row['id'].'" class="link-button">(Show)</button></form> 
				'.$row['body'].'</td>
		</tr>
		';
	}
	echo '
	</table>
	</div>
	';
}


