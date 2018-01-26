<?php 
/* this is very important in order to set php to render languages characters like hindi */

header( 'Content-Type: text/html; charset=utf-8' ); 

$message = "";
$topic="animal";
$lang = "Telugu";

$data = array();
$idx = 0;


if (isset($_POST['word-pairs'])) {
	$word_pairs = $_POST['word-pairs'];
	
	$word_data = array_chunk(explode(",", $word_pairs), 2);

	include('puzzle.php');
	$puzzle =  new Puzzle();
	$puzzle_data =	$puzzle->add_word_pairs($word_data);
	
}


if(isset($_GET["lang"])){
	$lang = $_GET["lang"];

	if(isset($_GET["topic"])){
		$topic = $_GET["topic"];

		
		if(isset($_GET["idx"])){
			$idx=$_GET["idx"];
		}

	}

}




/* these are get variable when page request is in get method. these are displayed in address bar and are used to send information to page*/


if(isset($_GET["imported"])){
	$message = $_GET["imported"];
}
if(isset($_GET["updated"])){
	$message = $_GET["updated"];
}


if(isset($_GET["del_no"])){
	$message = $_GET["del_no"];
}


if(isset($_GET["del_yes"])){
	$message = $_GET["del_yes"];
}




?>
<html>
<head>
	<title>
		Add Words
	</title>

	<link rel="stylesheet" type="text/css" href="css/style.css">
	
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

</head>
<body>


<?php include('header.php') ?>

	

	
		<?php 



		if(!empty($message)){				
			?>	

			<div class="message">
				<h2><?php 

				echo $message;
				?>	
				</h2>
			</div>	


			<?php 

		}		
		?>	

					<div style="margin-top: 100px;" class="main">
						<h3>Thank you. The synonym list is added to the database.
<br/><br/>
Would you like to add another set of synonyms?
</h3>
						<form method="post">
							<textarea  class="main-input" type="text" name="word-pairs" placeholder="Enter all the synonyms separated by comma" rows="3"></textarea>
							<button class="main-btn">Add Word Pairs</button>
						</form>
					

					</div>
					
<?php include('footer.php') ?>

				</body>
				</html>