<?php 
	/* MAKE IT SO WHEN CHARACTER IS NOT FOUND, SET EMPTY CLUE BUT STILL SET CHAR AS SYNONYM*/
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
			// If word is set and not empty
			if(isset($_POST['word']) && !empty($_POST['word']))
			{
				// Require db config file
				require 'db_configuration.php';
		
				// Create new database object
				$db = new Database();
		
				// Establish a conenction to the database
				$db->connect();
		
				// Require the word processsor file
				require 'IndicTextAnalyzer/word_processor.php'; 
				
				// Create a new word_processor object
				$wp = new wordProcessor("", "English");
				
				// Set language
				//$lang = $_POST['lang'];
				
				// Selection query
				$query = "SELECT `id` FROM `puzzles` WHERE `word`='" . $_POST['word'] ."'";
		
				// Run the query
				$result = mysqli_query($db->connection, $query);
		
				// If query was successful
				if($result)
				{
					// If there is data to fetch ~ the puzzle already exists
					if(mysqli_num_rows($result) != 0)
					{
						// Fetch the data
						while($row = mysqli_fetch_assoc($result))
						{
							// Go to the play puzzle page
							header("Location: playPuzzle.php?puzzleID=" . $row['id']);
						}
					}
					// Else if creating a new puzzle
					else
					{
						// Initialize clues and syns array
						$clues = [];
						$syns = [];
						
						// Set the word and language
						$wp->setWord($_POST['word'], $_POST['lang']);
						
						// Assign puzzle name
						$pname = $wp->getWord();
						
						// Assign logical Chars
						$logicalChars = $wp->getLogicalChars();
						
						// Iterate through each char of the array
						for($i = 0; $i < count($logicalChars); $i++)
						{
							// Selection query ~ get all chars that match
							$charQuery = "SELECT `word` FROM `logical_chars` WHERE `logical_char`='" . $logicalChars[$i] ."' ORDER BY rand() LIMIT 1";

							// Run the query
							$charResult = mysqli_query($db->connection, $charQuery);
							
							// If successful
							if($charResult)
							{
								// If there is data to fetch
								if(mysqli_num_rows($charResult) > 0)
								{
									// Fetch the data
									while($row = mysqli_fetch_assoc($charResult))
									{
										// Push the value to the array
										array_push($syns, $row['word']);
										
										// Push empty value to the clues
										array_push($clues, "");
									}

								}
								// Else if there is no data to fetch
								else
								{
									// Push empty value to the clues
									array_push($clues, "");
									
									// Push char to the syns
									array_push($syns, $logicalChars[$i]);
								}
							}
						}
						// Iterate through the array of syns
						for($i = 0; $i < count($syns); $i++)
						{
							// Query to get the rep_id of this synonym
							$repIdQuery = "SELECT `rep_id` FROM `synonyms` WHERE `word`='" . $syns[$i] . "'";
							
							// Run the query
							$repIdResult = mysqli_query($db->connection, $repIdQuery);
							
							// If successful
							if($repIdResult)
							{
								// Fetch the data
								while($row = mysqli_fetch_assoc($repIdResult))
								{
									// Selection query ~ get all syns that have same rep_id
									$clueQuery = "SELECT `word` FROM `synonyms` WHERE `rep_id`='" . $row['rep_id'] ."' AND `word` <>  '" . $syns[$i]  . "' ORDER BY rand() LIMIT 1";
																		
									// Run query
									$clueResult = mysqli_query($db->connection, $clueQuery);
									
									// If successful
									if($clueResult)
									{
										// If there is data to fetch
										if(mysqli_num_rows($clueResult) > 0)
										{
											// Fetch the data
											while($row2 = mysqli_fetch_assoc($clueResult))
											{
												// Push the value to the array
												$clues[$i] = $row2['word'];
											}
										}
									}
								}
							}
						}
						// If synonyms is not empty
						if(count($syns) != 0)
						{
							// Initailize word list
							$wordList = "";
							
							// Iterate through syns/clues
							for($i = 0; $i < count($syns); $i++)
							{
								// Add to word list
								$wordList .= $clues[$i] . "," . $syns[$i] . ",";
							}
							// Remove final comma
							$wordList = rtrim($wordList, ',');
							
							// Query to create new puzzle
							$createPuzzleQuery = "INSERT INTO `puzzles`(`word`, `word_list`) VALUES ('" . $pname . "', '" . $wordList . "')";
							
							// Run the query
							$creationResult = mysqli_query($db->connection, $createPuzzleQuery);
							
							// If successful
							if($creationResult)
							{
								// Selection Query
								$maxQuery = "SELECT max(`id`) FROM `puzzles`";
							
								// Run the query
								$maxResult = mysqli_query($db->connection, $maxQuery);
								
								// If successful
								if($maxResult)
								{
									// If there is data to fetch
									if(mysqli_num_rows($maxResult) != 0)
									{
										// Fetch the data
										while($row = mysqli_fetch_assoc($maxResult))
										{
											// Go to the play puzzle page
											header("Location: playPuzzle.php?puzzleID=" . $row['max(`id`)']);
										}
									}
								}
							}
						}
					}
				}
			}
			else
			{
				// Include the header file
				include('header.php');

				// Create main page
				echo '<div style="margin-top: 100px;" class="main">';
				echo '<form method="post" action="index.php">';
				echo '<input class="main-input" type="text" name="word" placeholder="Enter your name to see the puzzle" required/>';
				
				// Language dropdown
				//echo '<select name="lang">';
				//echo '<option value="English">English</option>';
				//echo '<option value="Telugu">Telugu</option>';
				//echo '</select>';
				
				echo '<button class="main-btn">Show Me!</button>';
				echo '</form>';
				echo '</div>';

				// Include the footer file
				include('footer.php');
			}
		?>
	</body>
</html>