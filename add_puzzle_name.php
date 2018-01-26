<?php 
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
			
			// If prev redirected
			if(isset($_GET['anotherOne']))
			{
				// Display header
				echo '<h2>Successfully created the puzzle!</h2>';
				echo '<h4>Want to create another?</h4>';
			}
			// Else if not
			else
			{
				// Display header
				echo '<h3>Enter a name</h3>';
			}
			echo '<form method="post">';
			echo '<input class="main-input" type="text" name="pname" placeholder="Enter a name to create a puzzle for that" />';
			
			// Language dropdown
			//echo '<select name="lang">';
			//echo '<option value="English">English</option>';
			//echo '<option value="Telugu">Telugu</option>';
			//echo '</select>';
		
			echo '<button class="main-btn">Next..</button>';
			echo '</form>';
			echo '</div>';
			
			// Include footer file		
			include('footer.php');
		}
	?>
	</body>
</html>