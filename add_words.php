<?php 
	header( 'Content-Type: text/html; charset=utf-8' ); 
	// TODO ~ fix REP_ID issue. When adding new instance AFTER one is deleted
?>
<html>
	<head>
		<title>Add Word Pairs</title>

		<link rel="stylesheet" type="text/css" href="css/style.css">
	
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	</head>
	<body>
	<?php
		// Include header file
		include 'header.php';
		
		// Require helper file
		require 'helper.php';

		// Main content
		echo '<div style="margin-top: 100px;" class="main">';
		
		// If value is set
		if(isset($_GET['anotherOne']))
		{
			// Another entry heading
			echo '<h1>Successfully Added to Database!</h1>';
			echo '<h3>Would you like to add another?</h3>';
		}
		// Else if not set
		else
		{
			// Default heading
			echo '<h3>Enter all the synonyms separated by comma</h3>';
		}
		echo '<form method="post" action="add_words.php">';
		echo '<input  class="main-input" type="text" name="wordPairs" placeholder="Enter all the synonyms separated by comma"';
		
		// If value is set
		if(isset($_POST['wordPairs']))
		{
			// Display value
			echo ' value="' . $_POST['wordPairs'] . '"';
		}
		echo ' />';
		
		// Language dropdown
		//echo '<select name="lang">';
		//echo '<option value="English">English</option>';
		//echo '<option value="Telugu">Telugu</option>';
		//echo '</select>';
		
		echo '<button class="main-btn">Add Word Pairs</button>';
		echo '</form>';
		echo '</div>';
		
		// If posting
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			// Set language 
			//$lang = $_POST['lang'];
			
			// Initailize
			$wordPairs = [];
			
			// Rmeove whitespaces
			$temp = preg_replace('/\s+/', '', $_POST['wordPairs']);
			
			// Split textarea value into an array
			$wordPairs = explode(",", $temp);
		
			// Errors array
			$errors = [];
			
			// If array conatins less than two values
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
				// End error holder
				echo '</ul>';
			}
			// Else if there are no errors
			else
			{	
				// Require db config file
				require 'db_configuration.php';
				
				// Create new database object
				$db = new Database();
				
				// Establish a connection
				$db->connect();
				
				// Require the word processsor fil
				require 'IndicTextAnalyzer/word_processor.php'; 
				
				// Create a new word_processor object
				$wp = new wordProcessor("", "Telugu");
				
				// Array to hold any words that are already in the DB
				$existingWords = Array();
				
				// Initialize existing rep id
				$existingRepId = 0;

				// Array to later unset index values
				$toBeUnset = Array();
				
				// Iterate through all words entered ~ to assign REP ID
				for($i = 0; $i < count($wordPairs); $i++)
				{
					// Select  id query
					$selQuery = "SELECT `id` FROM `synonyms` WHERE `word`='" . $wordPairs[$i] . "'";

					// Run query
					$selResult = mysqli_query($db->connection, $selQuery);

					// If successful ~ there was data returned
					if(mysqli_num_rows($selResult) > 0)
					{
						// Fetch the exisiting data
						while($row = mysqli_fetch_array($selResult))
						{
							// Set id
							$matchingId = $row['id'];

							// Push word value to array
							array_push($existingWords, $wordPairs[$i]);
							
							// Store to unset
							array_push($toBeUnset, $i);
						}
					}
				}			
				// If there are values to unset
				if(count($toBeUnset) != 0)
				{	
					// Iterate through values
					for($i = 0; $i < count($toBeUnset); $i++)
					{
						// Unset value from word pairs array
						unset($wordPairs[$toBeUnset[$i]]);
					}
				}				
				// Reindex array
				$wordPairs = array_values($wordPairs);

				// If there is data in the exisiting array
				if(count($existingWords) != 0)
				{
					// If there are new words to add to the dataase
					if(count($wordPairs) > 0)
					{
						// Var to keep check of successful inserts
						$insertCount = 0;
						
						// Iterate through the word pairs
						for($i = 0; $i < count($wordPairs); $i++)
						{
							// Insert new synonyms query
							$insertSynsQuery = "INSERT INTO `synonyms` (`word`, `rep_id`) VALUES ('" . $wordPairs[$i] . "', " . $matchingId . ")";

							// Run query
							$insertSynsResult = mysqli_query($db->connection, $insertSynsQuery);
							
							// If query was successful
							if($insertSynsResult)
							{
								// Increment
								$insertCount++;
								
								// Set the new word
								$wp->setWord($wordPairs[$i], "Telugu");
								
								// Add to logical char
								$wp->toLogicalChars($wordPairs[$i], $db);
							}
						}
						// If insertion count is not 0
						if($insertCount != 0)
						{
							// Redirect user
							header("Location: add_words.php?anotherOne=true");
						}	
					}
					// Else if there are no new words to add to the database
					else
					{
						echo "ERROR: All of the words are already in the database. Please select new words that are not already in the database. <br />";
					}
				}
				// Else if there is no data in the exisitng array ~ completely new entry
				else
				{	
					// Clean array
					$cleanedWordPairs = cleanArray($wordPairs);			
	
					// Get the current highest rep id value
					$newRepId = $db->getMax('synonyms', 'id');
				
					// Increment
					$newRepId++;

					// Var to keep check of successful inserts
					$insertCount = 0;
					
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
							
							// Set the new word
							$wp->setWord($cleanedWordPairs[$j], "Telugu");
								
							// Add to logical char
							$wp->toLogicalChars($cleanedWordPairs[$j], $db);
						}
					}
					// If insertion count is not 0
					if($insertCount != 0)
					{
						// Redirect user
						header("Location: add_words.php?anotherOne=true");
					}
				}
			}
		}
		// Include footer file
		include('footer.php');
	?>
	</body>
</html>