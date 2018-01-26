<?php
	// Establish a database connection
	require "db_configuration.php";
	
	// Create the query
	$query = "SELECT * FROM `synonyms`";
	$db = new Database();
	
	$db->connect();
	
	// Run the query
	$result = mysqli_query($db->connection, $query);
	
	// If successful
	if($result)
	{
		// Require the PHPExcel Library
		require "PHPExcel.php";
		
		// Instantiate a new PHPExcel object
		$objPHPExcel = new PHPExcel(); 
		
		// Set the active Excel worksheet to sheet 0
		$objPHPExcel->setActiveSheetIndex(0);

		// Row count
		$rowCount = 1;
		
		// Set the headers
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'id' );
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, 'word' );
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'rep_id' );
			
		// Increment row 
		$rowCount++;
		
		// Output data of each row
		while($row = $result->fetch_assoc()) 
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['id'] );
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['word'] );
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['rep_id']);

			// Increment the Excel row counter
			$rowCount++; 
		}
		// Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
		$excel_file = new PHPExcel_Writer_Excel2007($objPHPExcel); 
		
		// Write and save the file
		$excel_file->save('downloads/current_synonyms.xlsx'); 
		
		echo "<h5>Database Successfully Converted To A Spreadsheet.</h5>";
		echo '<a href="admin.php">Go Back</a>';
		echo '<form method="GET" action="downloads/current_synonyms.xlsx">';
		echo '<button type="submit">Download</button>';
		echo '</form>';
	}
	// Else if not successful
	else
	{
		echo "failed";
	}
?>