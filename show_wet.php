<?php
session_start();
if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); } 

include ('library.php');
include ('header.php');


if (isset($_POST['action'])) { 
	if ($_POST['action'] == "Clear Wet Device(s)") { 

	}
}




$query	= "SELECT * FROM devices WHERE status='3' ORDER BY statdate DESC";
$result	= mysqli_query($connect,$query);
if (mysqli_num_rows($result) > 0 ) { 

	echo '<div align="center">
		<div style="height:25px;">&nbsp;</div>
	<table cellpadding="3" cellspacing="0" border="1" width="1200" bgcolor="grey">
	<tr>
	        <th>Serial</th>
	        <th>Floor</th>
	        <th>Unit</th>
	        <th>Device Type</th>
	        <th>Parent</th>
	        <th>Unit Description</th>
	        <th>System Status</th>
	        <th>Up/Down Time</th>
	</tr>';
	while ($row = mysqli_fetch_assoc($result)) { 
	$bgcolor = "#OFF";
		echo "
        <tr style='background-color:".$bgcolor.";'>
                <td align='left'>
                        ";
                        if ($_SESSION['user']['userlevel'] >= 200) {
                        echo "
                        <form method='post' action='edit_device.php' class='inline'>
                                <input type='hidden' name='id' value='$row[id]'>
                                <button type='submit' name='submit' value='Submit' class='link-button'>
                                        $row[serial]
                                </button>
                        </form>";
                        } else {
                                echo $row['serial'];
                        }
                        echo "
                        </td>
                <td align='center'>$row[floor]</td>
                <td>$row[unit_name]</td>
                <td>".display_devicetype($row['device_type'])."</td>
                <td>".(isset($parent[$row['parent_id']]) ? $parent[$row['parent_id']] : "No Parent")."</td>
                <td>$row[unit]</td>";
                switch($row['status'])
                {
                case 0:
                        $bgcolor = '#990000;';
                        break;
                case 1:
                        $bgcolor = 'green';
                        break;
                case 3:
                        $bgcolor = '#0FF;';
                        break;
                case 4:
                        $bgcolor = '#001122;';
                        break;
                case 5:
                        $bgcolor = '#335511;';
                        break;
                case 6:
                        $bgcolor = '#551351;';
                        break;
                default:
                        break;
                }
                echo "
                <td align='center' style='background-color:".$bgcolor.";'>".$statusarray[$row['status']]."</td>
                <td align='left'>".(($row['status'] == '0') ? display_time($row['statdate']) : date("Y-m-d H:i",$row['statdate'])." : ".display_time($row['statdate']))."</td>
        </tr>
        ";
	}
	echo '
	</table>
	</div>
	';
} else { 
	echo '<div>No devices are wet.</div>';
}

