<?php
	// If data is set
	if(isset($_POST['word']))
	{
		// Require db config file
		require 'db_configuration.php';
		
		// Create new database object
		$db = new Database();
		
		// Establish a conenction to the database
		$db_connect  = $db->connect();
		
		// Selection query
		$query = "SELECT `id` FROM `puzzles` WHERE `word`='" . $_POST['word'] ."'";
		
		// Run the query
		$result = mysqli_query($db_connect->connection, $query);
		
		// If query was successful
		if($result)
		{
			// While there is data to fetch
			while($row=mysqli_fetch_assoc($result))
			{
			
			}
			echo "EXISTS";
		}
		// Else if the query failed ~ assume the puzzle doesn't exist
		else
		{
			echo "DOES NOT EXIST";
		}
	}
?>