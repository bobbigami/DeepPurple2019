<?php

session_start();
if (!isset($_SESSION['loggedIn'])) { header("Location: index.php"); } 
include ('library.php');
include ('header.php');


