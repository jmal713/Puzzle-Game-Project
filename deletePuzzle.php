<html>
<head>
	<title>
		List Puzzles
	</title>

	<link rel="stylesheet" type="text/css" href="css/style.css">
	
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

</head>
<body>
<?php
	// Include header file
	include "header.php";
	
	// If id is set
	if(isset($_GET['id']))
	{
		require 'temp_config.php';
		
		$q = "DELETE FROM `name_and_synonym`.`puzzles` WHERE `puzzles`.`id` =".$_GET['id'];
		
		$r = mysqli_query($dbc, $q);
		
		if($r)
		{
			echo "<h1>Successfully removed the puzzle from the database</h1>";
		}
		else
		{
			echo "<h1>There was an error removing the puzzle!</h1>";
		}
	}
	
	// Include footer file
	include 'footer.php';
?>
</body>
</html>