<?php 
/* this is very important in order to set php to render languages characters like hindi */

header( 'Content-Type: text/html; charset=utf-8' ); 

$message = "";
$puzzle_data = array();

if (isset($_POST['submit_solution'])) {



	$total_chars = $_POST['total_chars'];
	/*
	0_6
	1_6
	2_9
	3_7
	4_8
	*/
	$each_word = explode(",", $total_chars);
	$word_name = $each_word[count($each_word) - 1];

	for ($j=0; $j < count($each_word) - 1; $j++) {
		$temp = explode("_", $each_word[$j]);
		$char_idx = $temp[0];
		$syn_len = (int) $temp[1];

		$syn = "";

		for ($i=0; $i < $syn_len; $i++) { 
			$syn .= $_POST[$char_idx.'_'.$i];
		}

		$puzzle_data[$j] = $syn;

	}

	include('puzzle.php');
	$puzzle =  new Puzzle();
	$puzzle_result = $puzzle->check_puzzle($word_name,$puzzle_data);
	if($puzzle_result == true){
		$message = "Congratulations! You have solved it!";
		$puzzle_data =	$puzzle->get_puzzle($word_name);

	}else if($puzzle_result == false){
		header("Location: show.php?incorrect=Incorrect! Try Again!&word_name=".$word_name);
	}

}else{
	header("Location: index.php");
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
		Submit Solution
	</title>

	<link rel="stylesheet" type="text/css" href="css/style.css">
	
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

</head>
<body>

<?php include('header.php') ?>


		

					<div style="margin-top: 40px;" class="main">

					<?php 



		if(!empty($message)){				
			?>	

			<h1>
				<?php 

				echo $message;
				?>	
			</h1>

			<?php 

		}		
		?>



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

							<input style="background-color: grey;border:0;" class="puzz-input" type="text" name="<?php echo $key.'_'.$i ?>" maxlength="1" value="<?php echo $value[1][$i]; ?>" >

							<?php }else{ ?>

							<input class="puzz-input" type="text" name="<?php echo $key.'_'.$i ?>"    value="<?php echo $value[1][$i]; ?>" maxlength="1" >
							<?php } } ?>
							
						</td>
					</tr>
				<?php } ?>


				</tbody>

			
			</table>	

							<h1 style="margin-top: 20px;">Want to try another name?</h1>


							<form method="post" action="show.php">
							<input class="main-input" type="text" name="word-name" placeholder="Enter your name to see the puzzle">
							<button class="main-btn">show me</button>
						</form>

						
						
					</div>

					
<?php include('footer.php') ?>

				</body>
				</html>