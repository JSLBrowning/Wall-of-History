<?php
    // Four variables for database connection.
	$host = "localhost";
	$username = "root";
	$user_pass = "usbw";
	$database_in_use = "test";

    // Create a database connection instance.
	$mysqli = new mysqli($host, $username, $user_pass, $database_in_use);
	mysqli_set_charset($mysqli, 'utf8');
?>