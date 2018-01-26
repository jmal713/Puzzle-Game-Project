<html>
<head>
	<title>
		Name in Synonyms
	</title>

	<link rel="stylesheet" type="text/css" href="css/style.css">
	
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

</head>
<body>


<?php 
	include('header.php');
	
	if(isset($_GET['message']))
	{
		echo '<div class="message">';
		echo '<h2>' . $_GET['message']. '</h2>';
		echo '</div>';	
	}

?>	

	<div style="margin-top: 100px;" class="main">
		<form method="post" action="show.php">
			<input class="main-input" type="text" name="username" placeholder="Enter your username" required />
			<input class="main-input" type="password" name="password" placeholder="Enter your password" required />
			<button class="main-btn">login</button>
		</form>
	

	</div>


					
<?php include('footer.php') ?>

				</body>
				</html>