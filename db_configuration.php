<?php

	// Define DATABASE credentials
	DEFINE('HOST', 'localhost');
	DEFINE('DATABASE', 'name_and_synonym');
	DEFINE('USER', 'root');
	DEFINE('PASSWORD', '');


	// Start class database
	class Database
	{
		// Function to establish a database connection
		public function connect()
		{
			// Connect to the database
			$this->connection =  mysqli_connect(HOST, USER, PASSWORD, DATABASE);

			// Set the charset
			mysqli_set_charset($this->connection, 'utf8');

			// If there is an error
			if (mysqli_connect_error())
			{
				// Stop conneciton and display error
				die("Database connection failed: " . mysqli_connect_error());
			}
		}

		// Function to get highest value in database
		public function getMax($tableName, $value)
		{
			// Query
			$query = "SELECT max(`" . $value . "`) FROM `" . $tableName . "`";

			// Run the query
			$result = mysqli_query($this->connection, $query);

			// Initialize return value
			$returnValue = 0;

			// Set row value
			$rowValue = "max(`" . $value . "`)";

			// While there is data to fetch
			while($row = mysqli_fetch_assoc($result))
			{
				// Set return value
				$returnValue = $row[$rowValue];
			}
			// Return value
			return $returnValue;
		}
	}
?>
