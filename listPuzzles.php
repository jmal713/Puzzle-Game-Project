<?php
	header( 'Content-Type: text/html; charset=utf-8' );
	/*
		1. We need to add SESSION data to allow only the creator of the user to edit and delete a puzzle
	*/
?>
<html>
	<head>
		<title>List Puzzles</title>

		<link rel="stylesheet" type="text/css" href="css/style.css">

		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	</head>
	<body>
	<?php
		// Include header file
		include('header.php');

		// Start main content
		echo '<div style="margin-top: 40px;" class="main">';
		echo '<h2>Select a puzzle to play</h2>';
		echo '<table>';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Puzzle Name</th>';
		echo '<th>Actions</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody  id="words">';

		// Require database config
		require 'db_configuration.php';

		// Create new database object
		$db = new Database();

		// Establish a connection to the database
		$db->connect();

		// Selection query
		$query = "SELECT `id`, `word`, `created_by` FROM `puzzles`";

		// Run the query
		$result = mysqli_query($db->connection, $query);

		// If query was successful
		if($result)
		{
			// While there is data to fetch
			while($row=mysqli_fetch_assoc($result))
			{
				// Add data
				echo '<tr>';
				echo '<td style="width: 70%;">';

				// Add puzzle name
				echo '<a href="playPuzzle.php?puzzleID=' . $row['id'] . '">';
				echo $row['word'];
				echo '</td>';

				// Play puzzle link
				echo '<td><a href="playPuzzle.php?puzzleID=' . $row['id'] . '">';
				echo '<img src="images/play.png" height="50px" width="50px"></a>';

				// Delete puzzle link
				echo '<a href="deletePuzzle.php?id=' . $row['id'] . '">';
				echo '<img src="images/remove.jpg" height="50px" width="50px"></a>';

				// Edit puzzle link
				echo '<a href="editPuzzle.php?id=' . $row['id'] .'&pname='.$row['word'].'">';
				echo '<img src="images/edit.png" height="50px" width="50px"></a>';
				echo '</td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
		}
		// Include footer file
		include('footer.php');
	?>
	</body>
</html>
