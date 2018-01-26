<html>
<head>
	<title>
		List Puzzles
	</title>

	<link rel="stylesheet" type="text/css" href="css/style.css">
	
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

</head>
<body>
<?php
	// Include header file
	include "header.php";
	
	// Require db config file
	require 'db_configuration.php';

	// Create new database object
	$db = new Database();

	// Establish a connection
	$db->connect();
	
	// If id is set
	if(isset($_GET['id']))
	{
		// Query
		$query = "DELETE FROM `synonyms` WHERE `id` =".$_GET['id'];
		
		// Run the query
		$result = mysqli_query($db->connection, $query);
		
		// If successful
		if($result)
		{
			// Success message
			echo "<h1>Successfully removed the word from the database!</h1>";
			
			// REMOVE FROM LOGICAL CHARS //
		}
		// Else if failed
		else
		{
			// Error message
			echo "<h1>There was an error removing the word!</h1>";
		}
	}
	// Else if rep_id is set
	else if(isset($_GET['rep_id']))
	{
		// Query
		$query = "DELETE FROM `synonyms` WHERE `rep_id` =".$_GET['rep_id'];
		
		// Run the query
		$result = mysqli_query($db->connection, $query);
		
		// If successful
		if($result)
		{
			// Success message
			echo "<h1>Successfully removed the word pairs from the database</h1>";
			// REMOVE FROM LOGICAL CHARS //
		}
		// Else if failed
		else
		{
			// Error message
			echo "<h1>There was an error removing the words!</h1>";
		}
	}
	
	// Include footer file
	include 'footer.php';
?>
</body>
</html>