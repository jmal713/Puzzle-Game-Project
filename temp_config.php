<?php
	//Localhost
	DEFINE('DATABASE_HOST', 'localhost');
	DEFINE('DATABASE_NAME', 'name_and_synonym');
	DEFINE('DATABASE_USER', 'root');
	DEFINE('DATABASE_PASSWORD', '');
	
	$dbc = @mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME) OR die ('There was an error connecting to the database.');

	mysqli_set_charset($dbc, 'utf8');
	
?>