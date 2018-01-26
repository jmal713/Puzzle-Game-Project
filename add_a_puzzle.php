<?php
	// NEED TO CHECK AGAINST ADDING WORDS TO DB FROM PUZZLE CREATION
	// DOESNT ALWAYS DO CORRECT REP_ID FOR WORDS/ IT CHOSE THE FINAL ONE 
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
		
		// Require the helper file
		require 'helper.php';
		
		// Require the word processsor fil
		require 'IndicTextAnalyzer/word_processor.php'; 
			
		// Assign Lang
		//$lang = $_GET['lang'];
		
		// Create a new word_processor object
		$puzzleWP = new wordProcessor($_GET['pname'], "Telugu");
		
		// Assign puzzle name
		$pname = $puzzleWP->getWord();
		
		// Set logical chars
		$pnameLogChars = $puzzleWP->getLogicalChars();
		
		// Display main content
		echo '<div style="margin-top: 100px;" class="main">';
		echo '<form method="post" action="add_a_puzzle.php?pname=' . $pname . '">';
		echo '<h2>Enter the words and clues for <span style="color:#F00;">' . $pname . '</span></h2>';
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
		for ($i = 0; $i < count($pnameLogChars); $i++) 
		{ 
			echo '<tr>';
			echo '<td>';
			
			if(!ctype_space($pnameLogChars[$i]))
			{
				// Display char index
				echo $i + 1;
			}
			echo '</td>';
			// Display char name
			echo '<td>';
			// If char is not a space
			if(!ctype_space($pnameLogChars[$i]))
			{
				 echo $pnameLogChars[$i]; 
			}
			echo '</td>';
			echo '<td>';
			
			// If char is not a space
			if(!ctype_space($pnameLogChars[$i]))
			{
				// Synonym input field
				echo '<input class="main-input" type="text" name="' . ($i + 1) .'_syn" required';
			
				// If value is already set
				if(isset($_POST[($i + 1) .'_syn']))
				{
					// Display the value
					echo ' value="' . $_POST[($i + 1) .'_syn'] . '" ';
				}
				echo '/>';
			}
			echo '</td>';
			echo '<td>';
			
			// If char is not a space
			if(!ctype_space($pnameLogChars[$i]))
			{
				// Clue input field
				echo '<input class="main-input" type="text" name="' . ($i + 1) . '_clue" required';
			
				// If value is already set
				if(isset($_POST[($i + 1) .'_clue']))
				{
					// Display the value
					echo ' value="' . $_POST[($i + 1) .'_clue'] . '" ';
				}
				echo '/>';
			}
			echo '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
		echo '<button class="main-btn" name="create_puzzle">Create Puzzle</button>';
		echo '</form>';
		echo '</div>';
		
		// Include footer file
		include('footer.php');
		
		// If posting
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{	
			// Initialize errors count
			$errors = [];
				
			// Initialize synonym array
			$wordList = "";
				
			// Iterate through each char of the puzzle name
			for($i = 0; $i < count($pnameLogChars); $i++)
			{
				// If is set ~ put here in case word contains a space
				if(isset($_POST[($i + 1) . '_syn']) && isset($_POST[($i + 1) . '_clue']))
				{
					// Get the value of the synonym
					$synonym = $_POST[($i + 1) . '_syn'];
				
					// Get clue value
					$clue = $_POST[($i + 1) . '_clue'];
				
					// Char check boolean
					$charCheck = 0;
				
					// Add the synonym to the array
					$wordList .= strtolower($clue) . "," . strtolower($synonym) . ","; 

					// Synonynm word processor
					$synWP = new wordProcessor($synonym, "Telugu");
				
					// Syn logical chars
					$synLogChars = $synWP->getLogicalChars();

					// Clue word processor
					$clueWP = new wordProcessor($clue, "");
				
					// Clue logical chars
					$clueLogChars = $clueWP->getLogicalChars();

					// Iterate through the chars of the synonym
					for($j = 0; $j < count($synLogChars); $j++)
					{				
						// If the syn char eqauls the pname char
						if($synLogChars[$j] == $pnameLogChars[$i])
						{
							// Set char check to true
							$charCheck = 1;
						}
					}
					// If char check is false ~ char was not in the string
					if($charCheck != 1)
					{ 
						// Increment errors
						array_push($errors, 'Error at line ' . ($i + 1) . '! Character "' . $pnameLogChars[$i] .'" MUST exist at least once in the synonym!');
					}
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
					// Include db config file
					require_once "db_configuration.php";
				
					// Create new database object
					$db = new Database();
				
					// Establish a conenction to the database
					$db->connect();
				
					// Remove the final comma
					$insert = preg_replace('/,$/', '', $wordList);
				
					// Replace whitespaces
					$finalInsert =  preg_replace('/\s+/', '', $insert);
				
					// Selection query
					$query = "INSERT INTO `puzzles`(`word`, `created_by`, `word_list`) VALUES ('" . $pname . "', 'admin', '" . $finalInsert . "')";

					$result = mysqli_query($db->connection, $query);
		
					// If query was successful
					if($result)
					{
						// Explode final insert to array
						$wordPairs  = explode(',', $finalInsert);
					
						// Clues and syns array
						$cluesAndSyns = array();
					
						// Count varaible
						$count = count($wordPairs);

						// Split into seperate array
						for($i = 0; $i < $count; $i++)
						{
							// Push to array
							$cluesAndSyns[] = array($wordPairs[$i], $wordPairs[$i+1]);
						
							// Incrmenet again
							$i++;
						}		
						// Array to hold any words that are already in the DB
						$existingWords = Array();
				
						// Initialize existing rep id
						$existingRepId = 0;

						// Array to later unset index values ~ array $i ant index $j
						$toBeUnset = Array();
				
						// Iterate through array of words entered ~ to assign REP ID
						for($i = 0; $i < count($cluesAndSyns); $i++)
						{
							// Iterate thorugh each sub array
							for($j = 0; $j < count($cluesAndSyns[$i]); $j++)
							{
								// Change to `id`
								// Select rep id query
								$selQuery = "SELECT `rep_id` FROM `synonyms` WHERE `word`='" . $cluesAndSyns[$i][$j] . "'";

								// Run query
								$selResult = mysqli_query($db->connection, $selQuery);
							
								// If successful ~ there was data returned
								if(mysqli_num_rows($selResult) > 0)
								{
									// Fetch the exisiting data
									while($row = mysqli_fetch_array($selResult))
									{
										// Set rep id
										$existingRepId = $row['rep_id'];

										// Push word value to array
										array_push($existingWords, $cluesAndSyns[$i][$j]);
					
										// Store to the unset array ~ array $i at index $j
										$toBeUnset[] = array($i, $j);
									}
								}
							}		
						}
						// If there are values to unset
						if(count($toBeUnset) != 0)
						{	
							// Iterate through values
							for($i = 0; $i < count($toBeUnset); $i++)
							{
								// Iterate through values
								for($j = 0; $j < count($toBeUnset[$i]); $j++)
								{
									$first = $toBeUnset[$i][0];
									$sec = $toBeUnset[$i][1];
									
									// Unset value from word pairs array ~ array toBeUnset $i and index toBeUnset $j
									unset($cluesAndSyns[$first][$sec]);
								}
							}
						}				
						// Reindex multi-dimensional array
						for($i = 0; $i < count($cluesAndSyns); $i++)
						{
							// Reindex sub array
							$cluesAndSyns[$i] = array_values($cluesAndSyns[$i]);
						}
						// If there is data in the exisiting array
						if(count($existingWords) != 0)
						{
							// If there are new words to add to the dataase
							if(count($cluesAndSyns) > 0)
							{
								// Var to keep check of successful inserts
								$insertCount = 0;
						
								// Iterate through the word pairs
								for($i = 0; $i < count($cluesAndSyns); $i++)
								{
									for($j = 0; $j < count($cluesAndSyns[$i]); $j++)
									{
										// Insert new synonyms query
										$insertSynsQuery = "INSERT INTO `synonyms` (`word`, `rep_id`) VALUES ('" . $cluesAndSyns[$i][$j] . "', " . $existingRepId . ")";

										// Run query
										$insertSynsResult = mysqli_query($db->connection, $insertSynsQuery);
							
										// If query was successful
										if($insertSynsResult)
										{
											// Increment
											$insertCount++;
											
											// Temp word processor
											$tempWP = new wordProcessor($cluesAndSyns[$i][$j], "Telugu");
											
											// Add to logical chars
											$tempWP->toLogicalChars($cluesAndSyns[$i][$j], $db);

										}
									}
								}
								// If insertion count is not 0
								if($insertCount != 0)
								{
									// Redirect user
									header("Location: add_puzzle_name.php?anotherOne=true");
								}	
							}
							// Else if there are no new words to add to the database
							else
							{
								// Redirect user
								header("Location: add_puzzle_name.php?anotherOne=true");
							}
						}
						// Else if there is no existing data
						else
						{
							// Var to keep check of successful inserts
							$insertCount = 0;
				
							// Iterate
							for($i = 0; $i < count($cluesAndSyns); $i++)
							{
								// Get the current highest rep id value
								$newRepId = $db->getMax('synonyms', 'rep_id');
			
								// Increment
								$newRepId++;
						
								// Clean array
								$cleanedWordPairs = cleanArray($cluesAndSyns[$i]);			

								// Iterate through word pairs
								for($j = 0; $j < count($cleanedWordPairs); $j++)
								{
									// Insert new synonyms query
									$insertSynsQuery = "INSERT INTO `synonyms` (`word`, `rep_id`) VALUES ('" . $cleanedWordPairs[$j] . "', " . $newRepId . ")";

									// Run query
									$insertSynsResult = mysqli_query($db->connection, $insertSynsQuery);
					
									// If query was successful
									if($insertSynsResult)
									{
										// Increment
										$insertCount++;
										
										// Temp word processor
										$tempWP = new wordProcessor($cleanedWordPairs[$j], "Telugu");
											
										// Add to logical chars
										$tempWP->toLogicalChars($cleanedWordPairs[$j], $db);
									}
								}
								// If insertion count is not 0
								if($insertCount != 0)
								{
									// Redirect user
									header('Location: add_puzzle_name.php?anotherOne=true');
								}
							}	
						}
					}	
				}
		}	
	?>
	</body>
</html>