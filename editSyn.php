<?php 
	require_once 'sessionCheck.php';
	header( 'Content-Type: text/html; charset=utf-8' ); 
	/*
		1. We need to add SESSION data to allow only the creator of the user to edit and delete a puzzle
		2. Change listSyn.php to show word syns instead of each individual ~ KEY cannot change
	*/
?>
<html>
	<head>
		<title>Edit Synonyms</title>
		
		<link rel="stylesheet" type="text/css" href="css/style.css">
	
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	</head>
	<body>
	<?php 
		
		// Include header file
		include('header.php');
		
		// Require database config
		require 'db_configuration.php';
		
		// Create new database object
		$db = new Database();
	
		// Establish a connection to the database
		$db->connect();
		
		// If posting
		if($_SERVER['REQUEST_METHOD']== "POST")
		{
			// If single
			if(isset($_GET['id']))
			{
				$id = $_GET['id'];
			}
			// Else if pair
			else if(isset($_GET['rep_id']))
			{
				$rep_id = $_GET['rep_id'];
			}	
			// Initailize
            $wordPairs = [];
           
            // Remove whitespaces
            $temp = preg_replace('/\s+/', '', $_POST['wordPairs']);
           
            // Split textarea value into an array
            $wordPairs = explode(",", $temp);
       
            // Errors array
            $errors = [];
       
            // If array conatins less that one values
            if(count($wordPairs) < 2)
            {
                // Push error to array
                array_push($errors, "There must be at least two entries!");
            }
            // If there are errors
            if(count($errors) != 0)
            {
                // Start error holder
                echo '<ul class="errors">';
           
                // Iterate through the errors
                for($e = 0; $e < count($errors); $e++)
                {
                    // Display error
                    echo '<li>' . $errors[$e] . '</li>';
                }
                echo '</ul>';
            }
			else
			{
				// Remove any duplciate entries
				$unique = array_unique($wordPairs);

				// Impode to string
				$preUpdate = implode(",", $unique);

				// Remove any duplicate commas
				$updateFinal = preg_replace('/\,\,/', ',', $preUpdate);
			
				// Explode back to array
				$updateArray = explode(',', $updateFinal);
			
				// Count var
				$good = 0;
				
				// Iterate through all indicies
				for($i = 0; $i < count($updateArray); $i++)
				{
					if(isset($_GET['id']))
					{
						// Query
						$query = "INSERT INTO `synonyms` (`word`, `rep_id`) VALUES ('" . $updateArray[$i] . "', '" .  $id . "')";

					}
					else if(isset($_GET['rep_id']))
					{
						// Query
						$query = "INSERT INTO `synonyms` (`word`, `rep_id`) VALUES ('" . $updateArray[$i] . "', '" .  $rep_id . "')";
					}
					// Run the query
					$result = mysqli_query($db->connection, $query);
	
					if ($result)
					{
						// Increment
						$good++;
					}
				}
				// If good doesnt equal 0
				if($good != 0)
				{
					echo '<a href="listSyn.php">Go Back! </a>';
					echo '<h3>Success!</h3>';
				}
				// Else error
				else
				{
					echo "There was an error running all of the queries on the database.";
				}
			}
		}
		else
		{
			// Type of
			$type = "";
			
			// If id is set
			if(isset($_GET['id']))
			{
				// Selection query
				$query = "SELECT `id`, `word` FROM `synonyms` WHERE `id`=".$_GET['id'];
				
				$type = "single";
			}
			// Else if rep_id is set
			else if(isset($_GET['rep_id']))
			{
				// Selection query
				$query = "SELECT `word`, `rep_id` FROM `synonyms` WHERE `rep_id`=".$_GET['rep_id'];
				
				$type = "pair";
			}
			
			// Run the query
			$result = mysqli_query($db->connection, $query);
			
			// If query was successful
			if($result)
			{
				echo '<div style="margin-top: 100px;" class="main">';
				echo '<h3>Enter all the synonyms separated by comma</h3>';
				
				switch($type)
				{
					case "single":
						echo '<form method="post" action="editSyn.php?id=' . $_GET['id'] . '">';
						break;
					case "pair":
						echo '<form method="post" action="editSyn.php?rep_id=' . $_GET['rep_id'] . '">';
						break;
					}
				echo '<input  class="main-input" type="text" name="wordPairs" placeholder="Enter all the synonyms separated by comma" value="';
				
				// Temp rep id and id
				$id = 0;
				$rep_id = 0;
				
				// While there is data to fetch
				while($row = mysqli_fetch_assoc($result))
				{
					echo $row['word'] . ",";
					
					switch($type)
					{
						case "single":
							$id = $row['id'];
							break;
						case "pair":
							$repId = $row['rep_id'];
							break;
					}
				}
				echo '" />';
				echo '<input type="hidden" name="id" value="' . $id . '" />';
				echo '<button class="main-btn">Update Word Pairs</button>';
				echo '</form>';
				echo '</div>';
			}
		}

		// Include footer file
		include('footer.php');
	?>
	</body>
</html>