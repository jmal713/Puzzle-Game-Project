<?php
	// Function to convert to string
	function arrayToString($array)
	{
		// Initialize string
		$string = "";
		
		// Iterate through array
		for($i = 0; $i < count($array); $i++)
		{
			// Add to string
			$string .= $array[$i] . ',';
			
		}
		// Remove the final comma
		$string = preg_replace('/,$/', '', $string);
		
		// Display string
		return $string;
	}
	// Funtion to clean up array of empty and duplicate values
	function cleanArray($inArray)
	{
		// Elminate duplicate array values
		$unique = array_unique($inArray);
	
		// Impode to string
		$toString = implode(",", $unique);
		
		// Remove whitespace
		$noWhitespace = preg_replace('/\s+/', '', $toString);
		
		// Remove any duplicate commas
		$noCommas = preg_replace('/\,\,/', ',', $noWhitespace);
		
		// Explode back to array
		$returnArray = explode(",", $noCommas);
		
		// Return array
		return $returnArray;
	}
	// Function to upload a file
function fileUpload($file, $allowed_types, $dir, $name, $max_size)
{
	// Get the type of the file
	$file_type = $file['type'];
	
	// If the file type is allowed
	if(in_array($file_type, $allowed_types))
	{  		
		// Get the file size
		$file_size = $file['size'];
		
		// If the file size acceptable
		if($file_size <= $max_size)
		{			
			// Temp name
			$temp = $file['tmp_name'];
			
			// Move the file ~ if successful
			if(move_uploaded_file($temp, $dir . '/' . $name))
			{
				// Display success message
				return "Success";
			}
			// Else if not successful
			else
			{
				// Display error
				return "There was an error uploading the file to the server.";
			}
			
		}
		// Else if the file size is not acceptable
		else
		{
			// Display error
			return 'The file is too large!';
		}
	}
	else
	{
		return "Select a the correct file format!";
	}
}
	// Function to add to logical chars table
	/*function toLogicalChars($word, $db)
	{		
		// Iterate through each character of the word
		for($i = 0; $i < strlen($word); $i++)
		{
			// Create insertion query
			$query = "INSERT INTO `logical_chars` (`word`, `logical_char`, `position`) VALUES ('" . $word ."', '". $word[$i] ."', '". $i ."')";

			// Run the query
			$result = mysqli_query($db->connection, $query);
		}
	}*/
?>