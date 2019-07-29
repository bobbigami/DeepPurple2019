<?php

session_start();
if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); } 

include ('library.php');

include ('header.php');

echo "Configuration....";

$query	= "SELECT * FROM configuration";
$result	= mysqli_query($connect,$query);

echo '
<div align="center">
<table bgcolor="grey" cellpadding="5" cellspacing="0" width="1024">
';

while ($row = mysqli_fetch_assoc($result)) { 
	echo '
	<tr>
		<td class="menuitem">'.$row['config'].': </td><td><input type="text" name="'.$row['config'].'" value="'.$row['configvalue'].'"></td>
	</tr>
	';
}
echo '
<tr>
	<td colspan="2"><input type="submit" name="action" value="Configure"><input type="submit" name="action" value="Cancel"></td>
</tr>
</table>
</form>
</div>
';

