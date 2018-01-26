<?php
	header( 'Content-Type: text/html; charset=utf-8' );
?>
<html>
	<head>
		<title>Name in Synonyms</title>

		<link rel="stylesheet" type="text/css" href="css/style.css">
	
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	</head>
	<body>
	<?php
		// Include header file
		include 'header.php';

		// Require the heper file
		require 'helper.php';
		
		// Require db file	
		require 'db_configuration.php';
		
		// Create new database object
		$db = new Database();
				
		// Establish a connection
		$db->connect();
		
		// Query to run ~ check if PK exists
		$q = "SELECT `word`, `word_list` FROM `puzzles` where `id`=".$_GET['id'];

		// Run the query
		$r = mysqli_query($db->connection, $q);

		// If successful
		if($r)
		{
			// If atleast one row is returned ~ existing PK
			if(mysqli_num_rows($r) != 0)
			{
				// Fetch the data
				while($row = mysqli_fetch_assoc($r))
				{			
					$pname= $row['word'];
					$wordz= $row ['word_list'];
				}
			}
		}
		$pwords = explode(",", $wordz);
		
		$syns = [];
		$clues = [];
		
		for($i = 0; $i < count($pwords); $i++)
		{
			if($i == 0 || $i % 2 == 0)
			{
				// Push to array
				array_push($syns, $pwords[$i]);
			}
			else
			{
				// Push to array
				array_push($clues, $pwords[$i]);
			}
		}				
		// Display main content
		echo '<div style="margin-top: 100px;" class="main">';
		echo '<form method="post" action="editPuzzle.php?id=' . $_GET['id'] . '&pname='.$pname.'">';
		echo '<h2>Edit the words and clues for <span style="color:#F00;">' . $pname . '</span></h2>';
		echo '<table>';
		echo '<thead>';
		echo '<tr>';
		echo '<th>No</th>';
		echo '<th>Character</th>';
		echo '<th>Synonym (word)</th>';
		echo '<th>Clue</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody  id="words">';
		
		// Iterate though each char of puzzle name
		$id =$_GET['id'];
		
		// Iterate
		for ($i = 0; $i < count($clues); $i++) 
		{ 
			echo '<tr>';
			
			// Display char index
			echo '<td>' . ($i+ 1) . '</td>';
			
			// Display char name
			echo '<td>' .  $pname[$i].  '</td>';
			echo '<td>';
			
			// Synonym input field
			echo '<input class="main-input" type="text" name="' . ($i + 1) .'_syn" required value= "'.$clues[$i].'" />';
			echo '</td>';
			echo '<td>';
			
			// Clue input field
			echo '<input class="main-input" type="text" name="' . ($i + 1) .'_clue" required value= "'.$syns[$i].'" />';
			echo '</td>';
			echo '</tr>';
		}

		echo '</tbody>';
		echo '</table>';
		echo '<button class="main-btn" name="create_puzzle">Save Puzzle</button>';
		echo '</form>';
		echo '</div>';
		
		// Include footer file
		include('footer.php');
		
		// If posting
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			// Assign puzzle name
			$id = $_GET['id'];
			$pname = $_GET['pname'];
			
			// Initialize errors count
			$errors = [];
				
			// Initialize synonym array
			$wordList = "";
				
			// Iterate through each char of the puzzle name
			for($i = 0; $i < strlen($pname); $i++)
			{
				// Get the value of the synonym
				$synonym = $_POST[($i + 1) . '_syn'];
				
				// Get clue value
				$clue = $_POST[($i + 1) . '_clue'];
				
				// Char check boolean
				$charCheck = 0;
				
				// Add the synonym to the array
				$wordList .= strtolower($clue) . "," . strtolower($synonym) . ","; 

				// Iterate through the chars of the synonym
				for($j = 0; $j < strlen($synonym); $j++)
				{				
					// If the syn char eqauls the pname char
					if($synonym[$j] == $pname[$i])
					{
						// Set char check to true
						$charCheck = 1;
					}
				}
				// If char check is false ~ char was not in the string
				if($charCheck != 1)
				{ 
					// Increment errors
					array_push($errors, 'Error at line ' . ($i + 1) . '! Character "' . $pname[$i] .'" MUST exist at least once in the synonym!');
				}
			}
			// If there are errors
			if(count($errors) != 0)
			{
				// Start error holder
				echo '<ul class="errors">';
				
				// Iterate through errors
				for($e = 0; $e < count($errors); $e++)
				{
					// Display individual error
					echo '<li>' . $errors[$e] . '</li>';
				}
				// End error holder
				echo '</ul>';
			}
			// Else if there are no errors
			else
			{			
				// Create new database object
				$db = new Database();
				
				// Establish a conenction to the database
				$db->connect();
				
				// Remove the final comma
				$insert = preg_replace('/,$/', '', $wordList);
				
				// Replace all white spaces
				$finalInsert =  preg_replace('/\s+/', '', $insert);
				
				// Selection query
				$query = "UPDATE `puzzles` SET `word_list`='".$finalInsert."' WHERE `id`=".$_GET['id'];
		
				// Run the query
				$result = mysqli_query($db->connection, $query);
		
				// If query was successful
				if($result)
				{
					// Explode final insert into array
					$toChars = explode(',', $finalInsert);
					
					// Iterate though all indecies
					for($i = 0; $i < count($toChars); $i++)
					{
						// Query to check if already exsits
						$checkQuery = "SELECT * FROM `logical_chars` WHERE `word`='" . $toChars[$i] . "'";

						// Run query
						$checkResult = mysqli_query($db->connection, $checkQuery);
						
						// If successful
						if($checkResult)
						{
							// If there was a not a row returned
							if(mysqli_num_rows($checkResult) == 0)
							{
								// Set temp to value
								toLogicalChars($toChars[$i], $db);
							}
						}
					}
					
					// Redirect
					header('Location: add_puzzle_name.php?anotherOne=true');
				}
				// Else if the query failed
				else
				{
					// Error mseeasge
					echo "<script>alert(\"Error running the query on the daabase!\")</script>";
				}
			}
		}
		
	?>
	</body>
</html>