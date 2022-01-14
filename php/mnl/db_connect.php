<?php
    // Four variables for database connection.
	$host = "localhost";
	$username = "tronec_mythsandlegacy";
	$user_pass = "v72Nfs.Dg7a_c";
	$database_in_use = "tronec_mythsandlegacy";

    // Create a database connection instance.
	$mysqli = new mysqli($host, $username, $user_pass, $database_in_use);
	mysqli_set_charset($mysqli, 'utf8');
