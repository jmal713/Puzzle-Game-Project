<?php
	// Establish a database connection
	require "db_configuration.php";
	
	// Create the query
	$query = "SELECT * FROM `logical_chars`";
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
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'logical_char' );
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, 'position' );
			
		// Increment row 
		$rowCount++;
		
		// Output data of each row
		while($row = $result->fetch_assoc()) 
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['id'] );
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['word'] );
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['logical_char']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['position']);

			// Increment the Excel row counter
			$rowCount++; 
		}
		// Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
		$excel_file = new PHPExcel_Writer_Excel2007($objPHPExcel); 
		
		// Write and save the file
		$excel_file->save('downloads/current_logicalchars.xlsx'); 
		
		echo "<h5>Database Successfully Converted To A Spreadsheet.</h5>";
		echo '<a href="admin.php">Go Back</a>';
		echo '<form method="GET" action="downloads/current_logicalchars.xlsx">';
		echo '<button type="submit">Download</button>';
		echo '</form>';
	}
	// Else if not successful
	else
	{
		echo "failed";
	}
?>