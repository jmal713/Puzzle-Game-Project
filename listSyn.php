<?php 

	require_once 'sessionCheck.php';
	header( 'Content-Type: text/html; charset=utf-8' ); 
	/*
		1. We need to add SESSION data to allow only the creator of the user to edit and delete a puzzle
	*/
?>
<html>
	<head>
		<title>List Synonyms</title>
		
		<link rel="stylesheet" type="text/css" href="css/style.css">
	
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	</head>
	<body>
	<?php 
		// Include header file
		include('header.php');
		
		// If viewtype is set
		if(isset($_GET['viewType']))
		{
			// Set the view
			$view = $_GET['viewType'];	
		}
		// Else if not set
		else
		{
			// Set default
			$view = "single";
		}
		
		// Start main content
		echo '<div style="margin-top: 40px;" class="main">';
		
		// If view is pair
		if($view == "pair")
		{
			// Echo pair header
			echo '<h2>Select  Word Pair to Edit</h2>';
		}
		// Else if view is single
		else
		{
			// Echo single header
			echo '<h2>Select  Word to Edit</h2>';
		}
		
		// View type form
		echo '<div class="view-holder">';
		echo '<a class="main-btn viewtype" href= "listSyn.php?viewType=single">By Word</a>';
		echo '<a class="main-btn viewtype" href= "listSyn.php?viewType=pair">By Pairs</a>';
		echo '</div>';
		
		// Start table
		echo '<table>';
		echo '<thead>';
		echo '<tr>';
		
		// If view is pair
		if($view == "pair")
		{
			// Echo pair header
			echo '<th>Word Pairs</th>';
		}
		// Else if view is single
		else
		{
			// Echo single header
			echo '<th>Word</th>';
		}
		
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
	
		// If view is pair
		if($view == "pair")
		{	
			// Count the number of differnt rep_ids
			$countQuery = "SELECT DISTINCT `rep_id` FROM `synonyms`";
			
			// Run the query
			$countResult = mysqli_query($db->connection, $countQuery);
			
			// If successful
			if($countResult)
			{
				// Fetch the data
				while($row = mysqli_fetch_assoc($countResult))
				{
					// Query to get all of the values associated with that rep_id
					$listQuery = "SELECT * FROM `synonyms` WHERE `rep_id`=" . $row['rep_id'];
					
					// Run that query
					$listResult = mysqli_query($db->connection, $listQuery);
						
					// If successful
					if($listResult)
					{
						// Add data
						echo '<tr>';
						echo '<td style="width: 70%;">';

						// While there is data to fetch
						while($row2 = mysqli_fetch_assoc($listResult))
						{
							// Add puzzle name
							echo $row2['word'] . " ";
			
						}
						echo '</td>';
			
						echo '<td>';
						
						// Delete puzzle link
						echo '<a href="deleteSyn.php?rep_id=' . $row['rep_id'] . '">';
						echo '<img src="images/remove.jpg" height="50px" width="50px"></a>';
			
						// Edit puzzle link
						echo '<a href="editSyn.php?rep_id=' . $row['rep_id'] . '">';
						echo '<img src="images/edit.png" height="50px" width="50px"></a>';
						echo '</td>';
						echo '</tr>';
					}
				}
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
		}
		// Else if view is single
		else
		{
			// Selection query
			$query = "SELECT `id`, `word` FROM `synonyms` ORDER BY `rep_id`";
	
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
					echo $row['word'];
					echo '</td>';
				
					echo '<td>';
					// Delete puzzle link
					echo '<a href="deleteSyn.php?id=' . $row['id'] . '">';
					echo '<img src="images/remove.jpg" height="50px" width="50px"></a>';
				
					// Edit puzzle link
					echo '<a href="editSyn.php?id=' . $row['id'] . '">';
					echo '<img src="images/edit.png" height="50px" width="50px"></a>';
					echo '</td>';
					echo '</tr>';
				}
				echo '</tbody>';
				echo '</table>';
				echo '</div>';
			}
		}
		
		// Include footer file
		include('footer.php');
	?>
	</body>
</html>