<?php
	require_once 'sessionCheck.php';
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
		// If the puzzle name is set and is NOT empty
		if (isset($_POST['pname']) && !empty($_POST['pname']))
		{
			// Go to the add puzzle words file
			header('Location: add_a_puzzle.php?pname=' . strtolower($_POST['pname']));
		}
		// Else if the puzzle name has not been set
		else
		{
			// Include header file
			include('header.php') ;

			// Display main content
			echo '<div style="margin-top: 100px;" class="main">';


				// Display header
				echo '<h3><a href="listSyn.php">EDIT Synonyms</a></h3>';
				echo '<h3><a href="fileUpload.php">import the Wordlist</a></h3>';
				echo '<h3><a href="exportSyn.php">Export the Wordlist</a></h3>';
				echo '<h3><a href="export_logicalchars.php">Export the Logical_Chars_list</a></h3>';
				echo '<h3><a href="export_puzzle.php">Export the Puzzle list</a></h3>';

				//<a href="playPuzzle.php" <a/>
			}


			// Include footer file
			include('footer.php');

	?>
	</body>
</html>
