<?php 
if (!isset($_SESSION)) { 
	session_start();
} 
?>


<!DOCTYPE html>
<html
<head>
<title>Deep Purple</title>
<style>
.inline {
  display: inline;
}
 a.reg:link, a.reg:visited {
  background-color: #f44336;
  color: white;
  padding: 14px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
}


.link-button {
  background: none;
  border: none;
  color: blue;
  text-decoration: underline;
  cursor: pointer;
  font-size: 1em;
  font-family: serif;
}
.link-button:focus {
  outline: none;
}
.link-button:active {
  color:red;
}
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #800080;
}

li {
  float: left;
}

li a, .dropbtn {
  display: inline-block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

li a:hover, .dropdown:hover .dropbtn {
  background-color: red;
}

li.dropdown {
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: grey;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  text-align: left;
}

.dropdown-content a:hover {background-color: #f1f1f1;}

.dropdown:hover .dropdown-content {
  display: block;
}
.menuitem { 
  align: right;
}

</style>
</head>
<body bgcolor="#600060">

<ul>
  <li><a href="index.php">Home</a></li>
  <li class="dropdown">
	<a href="javascript:void(0)" class="dropbtn">Devices</a>
	<div class="dropdown-content">
		<a href="show_devices.php">List</a>
		<a href="show_offline.php">Show Offline</a>
		<a href="show_disabled_devices.php">Show Disabled</a>
		<a href="show_wet.php">Show Wet</a>
		<a href="show_unknown.php">Uknown Devices</a>
		<a href="show_battery.php">Show Low Battery</a>
	</div>
</li>
  <li class="dropdown">
	<a href="javascript:void(0)" class="dropbtn">Downloads</a>
	<div class="dropdown-content">
		<a href="dl_offline.php">Offline</a>
	</div>
  </li>
  <li class="dropdown">
    <a href="javascript:void(0)" class="dropbtn">Administrative</a>
    <div class="dropdown-content">
<?php if ($_SESSION['user']['userlevel'] > 100) { echo '<a href="show_users.php">Users</a>'; } ?>
      <a href="show_customers.php">Customers</a>
<?php if ($_SESSION['user']['userlevel'] > 250) { echo '<a href="show_configuration.php">Configuration</a>'; } ?>
<?php if ($_SESSION['user']['userlevel'] > 100) { echo '<a href="show_log.php">Show Log</a>'; } ?>
    </div>
  </li>
  <li><a href="search.php">Search</a></li>
<?php if (isset($_SESSION['loggedIn'])) { echo '<li><a href="logout.php">Logout</a></li>'; } ?>
</ul>


