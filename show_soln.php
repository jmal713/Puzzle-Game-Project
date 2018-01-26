<?php 
/* this is very important in order to set php to render languages characters like hindi */

header( 'Content-Type: text/html; charset=utf-8' ); 

$message = "";
$puzzle_data = array();

if (isset($_POST['word-name'])) {
	$word_name = $_POST['word-name'];
	include('puzzle.php');
	$puzzle =  new Puzzle();
	$puzzle_data =	$puzzle->get_puzzle($word_name);
	print_r($puzzle_data);
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

if(isset($_GET["added"])){
	$message = $_GET["added"];
}
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
		Name in Synonyms
	</title>

	<link rel="stylesheet" type="text/css" href="css/style.css">
	
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

</head>
<body>

		<div>
		<nav>
			<ul>

					<li style="margin-left: 20px;"><img src="logo.png"></li>
					<li><h2>Name in Synonyms</h2></li>

					<li style="float:right"><a style="background-color: #00B0F0;" href="index.php">Login</a></li>
					<li style="float:right"><a style="background-color: #C6DFEA;" href="index.php">Add Words Pair</a></li>
					<li style="float:right"><a style="background-color: #FFCFE7;" href="index.php">Add A Puzzle</a></li>
				
					<li style="float:right"><a style="background-color: #F1FC54;" href="index.php">List</a></li>
				</ul>
			</nav>
		</div>


	
		<?php 



		if(!empty($message)){				
			?>	

			<div class="message">
				<?php 

				echo $message;
				?>	
			</div>	


			<?php 

		}		
		?>	

					<div style="margin-top: 40px;" class="main">

					<h2>Here is your “Name in Synonyms”</h2>

						<form method="post" action="showme.php">
							

						<table>
				<thead>
					<tr>
						<th>Clue</th>
						<th>Synonym</th>
						
					</tr>
				</thead>
				<tbody  id="words">

				<?php foreach ($puzzle_data as $key => $value) {
					if ($key === "created_by") {
						continue;
					}

				?>

				

					<tr>
						<td><?php echo $value[0]; ?></td>
						<td>
							<?php 
								$len = strlen($value[1]);
								$disabled_char = rand(0,($len-1));

								for ($i=0; $i < $len; $i++) { 
								if ($i == $disabled_char) {
									
							?>

							<input class="puzz-input" type="text" name="<?php echo $key.'_'.$i ?>" maxlength="1" value="<?php echo $value[1][$i]; ?>" disabled="">

							<?php }else{ ?>

							<input class="puzz-input" type="text" name="<?php echo $key.'_'.$i ?>" maxlength="1" >
							<?php } } ?>
							
						</td>
					</tr>
				<?php } ?>


				</tbody>

			
			</table>

							<button style="float:left;margin-left: 200px;" name="submit_solution" class="puzz-btn">submit solution</button>

							<button style="float:right;margin-right: 200px;"  name="show_solution" class="puzz-btn">show solution</button>
						</form>
						
					</div>
				</body>
				</html>