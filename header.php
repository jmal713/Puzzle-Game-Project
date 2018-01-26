<div> 
		<nav>
			<ul>
          
					<li style="margin-left: 20px;"><a href="index.php"><img src="images/logo.jpg" height="70px" width="150px"></a></li>
					<li><a href="index.php"><h2>Name in Synonyms</h2></a></li>
          
          <!-- Session start -->
          <?php
          // require 'sessionCheck.php';
          error_reporting(0);
          session_start();
          ?>

          <!-- login & logout button -->
          <?php
          if (isset($_SESSION['display_name'])) {
          ?>
            <li style="float:right"><a style="background-color: #00B0F0; border: 2px solid white;" href="logout2.php">Logout</a></li>
          <?php
          } else {
          ?>
            <li style="float:right"><a style="background-color: #00B0F0; border: 2px solid white;" href="login.php">Login</a></li>
          <?php
          }
          ?>
          <!-- list, add word pairs, add a puzzle  -->
					<li style="float:right"><a style="background-color: #C6DFEA; border: 2px solid white;" href="add_words.php">Add<br />Word<br />Pairs</a></li>
					<li style="float:right"><a style="background-color: #FFCFE7; border: 2px solid white;" href="add_puzzle_name.php">Add<br />A<br />Puzzle</a></li>
				
					<li style="float:right"><a style="background-color: #F1FC54; border: 2px solid white;" href="listPuzzles.php">List</a></li>
          <!-- Admin button placeholder -->
          <?php
          
          if (isset($_SESSION['display_name'])) {
          ?>
            <li style="float:right"><a style="background-color: red; border: 2px solid white;" href="admin.php">Admin</a></li>
          <?php
          } else {
          ?>
          <!-- nothing because admin is not logged in -->
          <?php
          }
          ?>
				</ul>
			</nav>
		</div>
