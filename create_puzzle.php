<?php 
/* this is very important in order to set php to render languages characters like hindi */

header( 'Content-Type: text/html; charset=utf-8' ); 

$message = "";




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
						<h1>Thank you.</h1> 
						<br/>
						<h3>
The puzzle is added to the database.</h3>
						<h3>
You can access your puzzle in the “List”.</h3>
						
					</div>
					
<?php include('footer.php') ?>

				</body>
				</html>