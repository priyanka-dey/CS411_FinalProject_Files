<?php

	$dbhost = '127.0.0.1';
	$dbuser = 'pdey3';
	$dbpass = 'cs411';
	$dbport = 3036;
	$db = 'wine_snob';

	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
	if(! $conn){
   		die('Could not connect: '.mysql_error());
	}

?>
