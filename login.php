<?php
session_start();
if (isset($_SESSION['error']))
{
	echo '<center><h1>'.$_SESSION['error'].'</h1></center>';
}

echo '
<!DOCTYPE html>
<head>
<title>Logging in...</title>
</head>
<body bgcolor="#600060">
<div align="center">
<div><h1>Deep Purple</h1></div>
<div>&nbsp;</div>
<form name="auth" method="post" action="loggingin.php">
<table border="0" bgcolor="grey" cellpadding="5" cellspacing="0">
<tr>
<td align="right">Username:</td><td><input type="text" name="username" value=""></td>
</tr><tr>
<td align="right">Password:</td><td><input type="password" name="password" value=""></td>
</tr><tr>
<td colspan="2"><input type="submit" name="action" value="Login"></td>
</tr>
</table>
<div>&nbsp;</div>
<div><img src="logo.jpg" width="50%" height="50%" /></div>
</form>
</div>
</body>
</html>
';

