<?php

session_start();
if (!isset($_SESSION['loggedIn'])) { 
	header("Location: index.php");
}
$userlevel = $_SESSION['user']['userlevel']; 

include ('library.php');
include ('header.php');


if (isset($_POST['action'])) { 
	if ($_POST['action'] == "Mark Online") { 
		if (isset($_POST['serial'])) { 
			foreach($_POST['serial'] as $k => $v) { 
				$query = "UPDATE devices SET status='1', statdate='".date('U')."' WHERE serial='$v'";
				$result	= mysqli_query($connect,$query)or die(mysqli_error($connect));
			}
		}
	}
}




echo '

<div align="center">
<form name="online" method="post" action="show_offline.php">
<script language="JavaScript">
function toggle(source) {
  checkboxes = document.getElementsByName("serial\\[\\]");
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
<table border="1" bgcolor="grey" width="1024" cellpadding="3" cellspacing="0">
<tr>
	<th>'; 
	if ($userlevel >= 100) { echo '<input type="checkbox" onClick="toggle(this)" />(All) '; } echo 'Serial </th>
	<th>Floor</th>
	<th>Unit</th>
	<th>Zone</th>
	<th>Time Offline</th>
</tr>
';
$query = "SELECT * FROM devices WHERE enabled='1' AND status='0' ORDER BY statdate DESC";
$result	= mysqli_query($connect,$query);
if (mysqli_num_rows($result) > 0) { 
	while ($row = mysqli_fetch_assoc($result)) 
	{
		echo '
		<tr>
			<td>'; 
			if ($_SESSION['user']['userlevel'] >= 100) { echo '<input type="checkbox" name="serial[]" value="'.$row['serial'].'">'; } echo '
				'.$row['serial'].'</td>

			<td>'.$row['floor'].'</td>
			<td>'.$row['unit'].'</td>
			<td>'.$row['zone'].'</td>
			<td bgcolor="red">'.display_time($row['statdate']).'</td>
		</tr>
		';
	}
}
if ($userlevel >= 100) { 
echo '
<tr>
	<td colspan="5" align="right"><input type="submit" name="action" value="Mark Online"></td>
</tr>
';
}

echo '</table></form></div>';

