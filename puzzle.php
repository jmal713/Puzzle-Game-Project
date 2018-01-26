<?php 
	// Include config file
	include('db_configuration.php');
	
	// Puzzle class
	class Puzzle
	{
		// Parameter ~ puzzle name
		public $puzzleID;
		
		// Constructor function
		public function __construct($puzzleID)
		{		
			// Create new database object
			$this->database = new Database();
		
			// Establish a connection to the database
			$this->database->connect();
		
			// Selection query
			$query = "SELECT * FROM `puzzles` WHERE `id`='" . $puzzleID ."'";
		
			// Run the query
			$result = mysqli_query($this->database->connection, $query);
		
			// If query was successful
			if($result)
			{
				// While there is data to fetch
				while($row=mysqli_fetch_assoc($result))
				{
					// Assign ID property
					$this->id = $puzzleID;
					
					// Assign word property
					$this->word = $row['word'];
					
					// Assign clues property ~ empty array
					$this->clues = [];
					
					// Assign synonyms property ~ empty array
					$this->synonyms = [];
					
					// Turn the word list into an array
					$list = explode(",", $row['word_list']);
					
					// Iterate through the word list
					for($i = 0; $i < count($list); $i++)
					{
						// If first index
						if($i == 0)
						{
							// Push value to the clues array
							array_push($this->clues, $list[$i]);
						}
						// Else if index is even
						else if ($i % 2 == 0)
						{
							// Push value to the clues array
							array_push($this->clues, $list[$i]);
						}
						// Else it is odd
						else
						{
							// Push value to the synonyms array
							array_push($this->synonyms, $list[$i]);
						}
					}
					
					// Assign createdBy property
					$this->createdBy =  $row['created_by'];
				}
			}
			// Else if query failed, assume no puzzle exists
			else
			{
				// Create puzzle
				die("If puzzle creation has failed");
			}
		
		}
		// Function to get id of puzzle
		function getID()
		{
			return $this->id;
		}
		// Function to get word from puzzle
		function getWord()
		{
			return $this->word;
		}
		// Function to get char value from word
		function getWordChar($index)
		{
			return $this->word[$index];
		}
		// Function to get one clue from puzzle
		function getOneClue($index)
		{
			return $this->clues[$index];
		}
		// Function to get clues from puzzle
		function getAllClues()
		{
			return implode(" ", $this->clues);
		}
		// Function to get one synonym from puzzle
		function getOneSynonym($index)
		{
			return $this->synonyms[$index];
		}
		// Function to get all synonyms from puzzle as a string
		function getAllSynonymsAsString()
		{
			return implode(" ", $this->synonyms);
		}
		// Function to get all synonyms from puzzle as an array
		function getSynonymsAsArray()
		{
			return $this->synonyms;
		}
		// Function to get synonym char
		function getSynChar($synIndex, $charIndex)
		{
			return $this->getOneSynonym($synIndex)[$charIndex];
		}
		// Function to get created by from puzzle
		function getCreator()
		{
			return $this->createdBy;
		}
		public function get_puzzles_list()
		{
			$sql = "SELECT * FROM `puzzles`";

			$result = mysqli_query($this->database->connection,$sql);
			$puzzle_data = array();
			if($result){
				$i = 0;
				while($row=mysqli_fetch_assoc($result)){
					$id = $row["id"];
					$word = $row["word"];
					$created_by = $row["created_by"];

					$puzzle_data[$i] = array($id,$word , $created_by);
					$i++;
				}
				return $puzzle_data;
			}
		}

		public function create_puzzle($pname,$list)
		{
			$sql = "INSERT INTO `name_and_synonym`.`puzzles` (`id`, `word`, `word_list`, `created_by`) VALUES (NULL, '".$pname."', '".$list."', '');";
				$result = mysqli_query($this->database->connection,$sql);
				if ($result) {
					return true;
				} else {
					return false;
				}
			
		}

		public function add_word_pairs($values='')
		{
			foreach ($values as $key => $value) {
				$sql = "INSERT INTO `synonyms` (`id`, `word`, `rep_id`) VALUES (NULL, '".$value[0].",".$value[1]."', '1');";
				$result = mysqli_query($this->database->connection,$sql);
			
			}

			if($result){
				return true;
			}else{
				return false;
			}
		}

		public function check_puzzle($name,$data)
		{
			$db_data = $this->get_puzzle($name);
			$result = false;
			foreach ($db_data as $key => $value) {
				if ($key === "created_by") {
					continue;
				}


				if($value[1] == $data[$key]){
					$result = true;
				
				}else{
				
					$result = false;
					break;
				}
			}

			return $result;	

		
		}
	}


?>