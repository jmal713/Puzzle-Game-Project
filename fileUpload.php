<?php
		// Require the helper file
		require 'helper.php';
		
		require 'IndicTextAnalyzer/word_processor.php';
		
		// Allowed file types
		$allowed_spreadsheets = array("application/vnd.ms-excel","application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		
	// If posting
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		// Upload the file first
		if(isset($_FILES['file_to_upload']))
		{
			// Set the file
			$file = $_FILES['file_to_upload'];
			
			// Max file size
			//$max_size =  $_POST['MAX_FILE_SIZE'];
			$max_size = 35000;
			// Upload the file
			$isUploaded = fileUpload($file, $allowed_spreadsheets, 'uploads', 'synonyms.xlsx', $max_size);

			// If successfully uplaoded the file
			if($isUploaded == "Success")
			{
				// Dectect lin endings ~ for Mac OSX purposes
				ini_set('auto_detect_line_endings', TRUE);

				// If file succesfully opened
				if($file = fopen('uploads/synonyms.xlsx', 'r'))
				{
					// Connect to the database
					//require 'local_config.php';
					require 'db_configuration.php';
						
					$db = new Database();
					
					$db->connect();
					// Create truncate query
					// CHANGE THIS ~ truncate synonyms table
					$truncate_query = "TRUNCATE `synonyms`";
					$truncate_query2 = "TRUNCATE `logical_chars`";
			
					// Run the query
					$result = mysqli_query($db->connection, $truncate_query);
					$result2 = mysqli_query($db->connection, $truncate_query2);
		
					// If successfully truncated table
					if($result && $result2)
					{
							// Require the PHPExcel file
							require 'PHPExcel.php';
					
							// File to open
							$file_name = 'uploads/synonyms.xlsx';
					
							// Errors count
							$errors = 0;
					
							// Attempt to load the file
							try 
							{
								// Determine the file type
								$file_type = PHPExcel_IOFactory::identify($file_name);
						
								// Create the reader
								$reader = PHPExcel_IOFactory::createReader($file_type);
						
								// Load the file
								$file_opened = $reader->load($file_name);
							}
							// Catch any error 
							catch (Exception $e) 
							{
								// Error message
								die('Error loading the file!');
							}
							//  Get the initial sheet ~ assuming this is what we want
							$sheet = $file_opened->getSheet(0);
					
							// Get sheet's row dimension
							$rows = $sheet->getHighestRow();
							$columns = $sheet->getHighestColumn();
							
							//Declare rep_id//
							$rep_id = 0;
	
							//  Loop through each row of the sheet
							for ($row = 2; $row <= $rows; $row++) 
							{
								//  Read a row of data into an array
								$rowData = $sheet->rangeToArray('A' . $row . ':' . $columns . $row, NULL, TRUE, FALSE);
								
						   
								// Iterate through the array of data
								foreach($rowData[0] as $key => $value)
								{	
									$wp = new wordProcessor("", "Telugu");
									
									if(!empty($value))
     								{
         								// Check if rep_id
         								if($key != 0)
         								{	
         								
         									// check if ther eis a comma in the word
         									if(preg_match('/,/', $value))
         									{
         										// Explode to an array
         										$wordArray = explode(',', $value);
         										
         										// Iterate through the array
         										for($i = 0; $i < count($wordArray); $i++)
         										{	
         											$wp->setWord($wordArray[$i], "Telugu");
         											$wp->stripSpaces();
         											$insertionQuery = "INSERT INTO `synonyms`(`word`, `rep_id`) VALUES ('".$wp->getWord()."', ".$rep_id.")";
         											$insertResult = mysqli_query($db->connection, $insertionQuery);
         											
         											// If successful
         											if($insertResult)
         											{
         												$wp->setWord($wordArray[$i], "Telugu");	
         												$wp->toLogicalChars($wordArray[$i], $db);
         											}
         										}
         									}
         									else
         									{
         										$temp = $value;
         										$temp2 = preg_replace('/\s+/', '', $temp);
         										$insertionQuery = "INSERT INTO `synonyms`(`word`, `rep_id`) VALUES ('".$temp2."', ".$rep_id.")";
         										$insertResult = mysqli_query($db->connection, $insertionQuery);
         										
         										
         										
         											
         										// If successful
												if($insertResult)
												{
													$wp->setWord($value, "Telugu");	
													$wp->toLogicalChars($value, $db);
												}
         									}
         									
         								}
         								// Else if key is 0
         								else
         								{
         									// Set rep id
         									$rep_id = $value;
         								}
									
									}

								}
								// Remove the final comma
								//$insertion_query = substr($insertion_query, 0, -1);

								// Run the query
								//$result = mysqli_query($dbc, $insertion_query);
						
								// If not successful
								//if(!$result)
								//{
									// Increment error coutn
								//	$errors++;
								//}
							}
							// Close the database connection
							//mysqli_close($dbc);
					
							// If there are errors
							if($errors != 0)
							{
								// Display error message
								echo "There was an error importing some of the file!";
							}
							else
							{
								// Display Success mesage
								echo "File successfully imported to the database.";
							}
						}
					}
					// Else if failed in truncating table
					else
					{
						// Display error
						echo "There is an error performing something on the database. Please contact the webmaster.";
					}
				}
			}
			// Else if file failed to upload
			else
			{
				// Display error
				echo "File failed to upload! ERROR: " . $isUploaded;
			}
		}
		// Else if the file is not set
		else
		{
			// Display error
			echo "Select a file to import!";
		}
	
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Import Spreadsheet File</title>
		
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>	
	<body>
        <?php // Include the header file
				include('header.php');
        ?>
		<a class="button" href="index.php">Go Back</a>
		<form action="fileUpload.php" method="POST" enctype="multipart/form-data">
			<label>Select Excel File To Upload</label>
			
			<input type="file" name="file_to_upload" value="Browse..." />
			<input type="hidden" name="MAX_FILE_SIZE" value="35000" />
			
			<input type="submit" value="Import Spread Sheet" />
		</form>
				<?php // Include the footer file
				include('footer.php');
        ?>
	</body>
</html>