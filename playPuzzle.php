<!DOCTYPE html>
<html>
	<head>
		<title> Play your Puzzle!</title>
		
		<link rel="stylesheet" type="text/css" href="css/style.css">

		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

	</head>
	<body>
	<?php
		// Include the header file
		include('header.php');
		
		// Require the word processsor filE
		require 'IndicTextAnalyzer/word_processor.php'; 
			
		// If POSTING
		if ($_SERVER['REQUEST_METHOD'] == "POST")
		{
			// Assign value
			$puzzleID = $_GET['puzzleID'];

			// Require the puzzle class
			require 'puzzle.php';

			// Create new puzzle object
			$puzzle =  new Puzzle($puzzleID);
			/*
				NOTE: We will need to add SESSION data later here, if the user is logged in, they can see solution. Otherwise this redirects them to the login page.	
			*/
			// If the "Show Solution" button was pressed	
			if(isset($_POST['show_solution']))
			{
				// Show the puzzle w/ solutions
				showPuzzle($puzzle, true);	
			}
			// Else if the "Submit Solution" button was pressed
			else if(isset($_POST['submit_solution']))
			{
				// Show the puzzle
				showPuzzle($puzzle);	
				
				// Count the number of answers required
				$count = count($puzzle->getSynonymsAsArray());
			
				// Intitialize user answers array ~ length at COUNT
				$userAnswers = array_fill(0, $count, "");
			
				// Create a new word_processor object
				$puzzleWP = new wordProcessor("", "Telugu");
				
				// Iterate through the answers / clues
				for($row = 1; $row < ($count + 1); $row++)
				{	
					// Set the word to the synonym
					$puzzleWP->setWord($puzzle->getOneSynonym($row-1), "Telugu");
					
					// Parse to logical chars
					$synLogChars = $puzzleWP->getLogicalChars();
					
					// Iterate through the number of chars of the synonyms
					for($col = 1; $col < count($synLogChars) + 1; $col++)
					{
						// Set location of selected input [row_col]
						$temp = $row . '_' . $col;

						// Add char to the index of array
						$userAnswers[($row-1)] .= $_POST[$temp];
					}
				}
				// Initialize answers array, populated with the synonyms
				$puzzleAnswers = $puzzle->getSynonymsAsArray();
			
				// Initialize error count
				$errors =0;
			
				// Iterate to compare user answers and stuff
				for($i = 0; $i < count($puzzleAnswers); $i++)
				{
					// If the user's input and the synonym match
					if(strtolower($userAnswers[$i]) != strtolower($puzzleAnswers[$i]))
					{
						// Increment errors
						$errors++;
					}
				}
				// If there are any errors
				if($errors != 0)
				{
					// Display error message
					echo '<script type="text/javascript">document.getElementById("heading").innerHTML = "<h2>Incorrect! Try Again!</h2>"</script>';
					echo '<img class="correct" src="images/incorrect.png" />';
					// Show the form buttons
					showButtons();
				}
				// Else if there are no errors
				else
				{
					
					//	Display the play again option form
					echo "<div class=\"play-again\">";
					echo "<h2>Want to try another name?</h2>";
					echo '<form method="POST" action="index.php">';
					echo '<input class="main-input" type="text" name="word" />';
					echo '<button class="main-btn">Show Me!</button>';
					echo '</form>';
					echo "</div>";
					
					echo '<script type="text/javascript">document.getElementById("heading").innerHTML = "<h2>Congratulations! You have solved it!</h2>"</script>';
					echo '<img class="correct" src="images/correct.png" />';
				}
			}
		}
		// Else if name is set ~ assume first page load
		else if(isset($_GET['puzzleID'])) 
		{
			// Assign value
			$puzzleID = $_GET['puzzleID'];

			// Require the puzzle class
			require 'puzzle.php';

			// Create new puzzle object
			$puzzle =  new Puzzle($puzzleID);
			
			// Show the puzzle
			showPuzzle($puzzle);
			
			// Show the form buttons
			showButtons();
		}
		// Else if neither
		else
		{
			// Error message
			echo "No Puzzle Data selected";
		}
		// Include the footer file
		include('footer.php');
		
		// Function to show the puzzle to the user
		function showPuzzle($puzzle, $solution = false)
		{	
			// Start main content
			echo '<div style="margin-top: 40px;" class="main">';
			
			// Show proper heading
			echo '<h2 id="heading">Here is your “Name in Synonyms”</h2>';

			// Start form
			echo '<form method="POST" action="playPuzzle.php?puzzleID=' . $puzzle->getID() .'">';
			
			// Start table
			echo '<table>';
			echo '<thead>';
			echo '<tr>';
			echo '<th>Clue</th>';
			echo '<th>Synonym</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody  id="words">';
			
			// Iterate through the CLUES / SYNONYMS
			for($i = 0; $i < count($puzzle->synonyms); $i++)
			{
				// Start row
				echo '<tr>';
				
				// Add clue value
				echo '<td> '. $puzzle->getOneClue($i) . '</td>';
				
				// Start synonym section
				echo '<td>';
				
				// Temp count
				$check = false;
				
				// Get the language from the URL
				//$lang = $_GET['lang'];
		
				// Word proccessor for the puzzle name
				$pnameProccessor = new wordProcessor($puzzle->getWord(), "Telugu");
						
				// Word processor for the synonym
				$synProcessor = new wordProcessor($puzzle->getOneSynonym($i), "Telugu");
						
				// Parse to logical chars
				$synLogChars = $synProcessor->getLogicalChars();
						
				// Iterate through the synonym's chars
				for($j = 0; $j < count($synLogChars); $j++)
				{
					// If showing the solution
					if($solution)
					{
						// Display disabled input with value
							echo '<input class="puzz-input disabled" type="text" name="' . ($i + 1) . '_' . ($j + 1) . '" maxlength="1" value="' . $synLogChars[$j] . '" disabled />';
					}
					// Else if showing unsolved puzzle
					else
					{			
						// If is a whitespace
						if(!ctype_space($pnameProccessor->logicalCharAt($i)))
						{
							// If the synonym char matches the puzzle word AND check is false
							if($synLogChars[$j] == $pnameProccessor->logicalCharAt($i) && !$check)
							{
								// Display disabled input with value
								echo '<input class="puzz-input disabled" type="text" name="' . ($i + 1) . '_' . ($j + 1) . '" maxlength="1" value="' .$synLogChars[$j]  . '" readonly />';
								// Set check to true
								$check = true;
							}
							// Else if the synonym char does not match the puzzle word OR check is true
							else
							{
								// Display empty input w/o value
								echo '<input class="puzz-input" type="text" name="' . ($i + 1) . '_' . ($j + 1) . '" maxlength="1"';
						
								// Set location of selected input [row_col]
								$temp = ($i + 1) . '_' . ($j + 1);
								
								// if value was already set
								if(isset($_POST[$temp]))
								{
									// Set the value
									echo ' value="' . $_POST[$temp] . '" ';
								}
								// End input
								echo '/>';
							}
						}
					}
				}
			}
			// End the body and table
			echo '</tbody>';
			echo '</table>';
		}
		// Fucntion to display form buttons
		function showButtons()
		{
			// Display buttons
			echo '<button style="float:left;margin-left: 200px;" name="submit_solution" class="puzz-btn">Submit Solution</button>';
			echo '<button style="float:right;margin-right: 200px;"  name="show_solution" class="puzz-btn">Show Solution</button>';
			
			// End form and main holder
			echo '</form>';
			echo '</div>';
		}
	?>
	</body>
</html>