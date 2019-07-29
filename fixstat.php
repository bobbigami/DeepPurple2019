<?php

include ('library.php');

$startdate	= mktime(0,0,0,7,1,2019);
$query	= "UPDATE devices SET statdate='$startdate' WHERE statdate='0'";
$result	= mysqli_query($connect,$query);
