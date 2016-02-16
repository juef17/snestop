<?php 
	require_once("base.php");
	require_once("PasswordHash.php");

	function EchoQuery($mysqli, $query)
	{
		if ($mysqli->query($query) === TRUE)
			echo 'Query Success: ' . $query . '<br />';
		else {
			echo 'Query failed: ' . $mysqli->error . '<br />';
			echo 'Query was: ' . $query . '<br>';
		}
	}

	function EchoCount($mysqli, $table)
	{
		$result = $mysqli->query("SELECT count(*) as total from $table;");
		$data = $result->fetch_assoc();
		echo "Count for table $table : " . $data['total'] . "<br>";
	}

	function ClearDB()
	{
		$mysqli = CreateConnection();
		//additional deletions here!
		EchoQuery($mysqli, 'DELETE FROM User;');
		EchoQuery($mysqli, 'DELETE FROM Game;');
		EchoQuery($mysqli, 'DELETE FROM Track;');
		EchoQuery($mysqli, 'DELETE FROM Review;');
		EchoQuery($mysqli, 'DELETE FROM Community;');
		EchoQuery($mysqli, 'DELETE FROM DuelResult;');
	}

	function PopulateFull()
	{
		PopulateUsers();
		PopulateReviews();
		PopulateCommunities();
		PopulateDuels(10);
		//additional populations here!
	}

	//Users population
	function PopulateUsers()
	{
		$mysqli = CreateConnection();
		InsertUser($mysqli, 'NULL', 'ramoutz@panus.com', 'encrypted', 'French', 1, 0, 'admin', 1);
		InsertUser($mysqli, 'NULL', 'limoutz@panus.com', 'encrypted', 'French', 1, 0, 'limoutz', 0);
		InsertUser($mysqli, 'NULL', 'serge@panus.com', 'encrypted', 'French', 1, 0, 'serge', 0);
		InsertUser($mysqli, 'NULL', 'robin@panus.com', 'encrypted', 'French', 1, 0, 'robin', 0);
		InsertUser($mysqli, 'NULL', 'barry@panus.com', 'encrypted', 'French', 1, 0, 'barry', 0);
		InsertUser($mysqli, 'NULL', 'moe@panus.com', 'encrypted', 'French', 1, 0, 'moe', 0);
		EchoCount($mysqli, 'User');
	}
	
	function InsertUser($mysqli, $idCommunity, $email, $password, $language, $canStreamMP3, $autoPlay, $userName, $isAdmin)
	{
		$passwordEncoding = create_hash($password);
		EchoQuery($mysqli,"
			INSERT INTO User(idCommunity, email, password, passwordSalt, language, canStreamMP3, autoPlay, userName, isAdmin)
			VALUES($idCommunity, '$email', '" . $passwordEncoding['hash'] . "', '" . $passwordEncoding['salt'] . "', '$language', $canStreamMP3, $autoPlay, '$userName', $isAdmin)
		");
	}
	
	function PopulateReviews()
	{
		$mysqli = CreateConnection();
		InsertReview($mysqli, '', '', 'This is my goddamned review!', FALSE);
		InsertReview($mysqli, '', '', 'This track is so WHANNE!', TRUE);
		InsertReview($mysqli, '', '', 'Good schtuff!', FALSE);
		InsertReview($mysqli, '', '', addslashes('This track, granny, how does it feel? It\'s so good!'), TRUE);
		InsertReview($mysqli, '', '', 'This track. I want to lick its ballz!', FALSE);
		InsertReview($mysqli, '', '', 'My review is quite short!', TRUE);
		EchoCount($mysqli, 'Review');
	}
	
	function InsertReview($mysqli, $idUser, $idTrack, $text, $approved)
	{
		EchoQuery($mysqli,"
			INSERT INTO Review(idUser, idTrack, text, approved)
			VALUES((SELECT idUser FROM User ORDER BY RAND() LIMIT 1), (SELECT idTrack FROM Track ORDER BY RAND() LIMIT 1), '$text', '$approved')
		");
	}

	function PopulateCommunities()
	{
		$mysqli = CreateConnection();
		InsertCommunity($mysqli, 'The Shizz', 'ohhhShizz, why u always down?', 'http://theshizz.org/forum/index.php?/forum/8-minibosses-message-board/');
		InsertCommunity($mysqli, 'LPG', 'LanPartyGuys!', 'jerther.com/~baldho/snestop/site/');
		EchoCount($mysqli, 'Community');
	}
	
	function InsertCommunity($mysqli, $name, $token, $url)
	{
		EchoQuery($mysqli,"
			INSERT INTO Community(name, token, URL)
			VALUES('$name', '$token', '$url')
		");
	}

	function PopulateDuels($n) // doesn't update ratings
	{
		$mysqli = CreateConnection();
		while($n-- > 0) InsertDuel($mysqli, '', '', '');
		EchoCount($mysqli, 'DuelResult');
	}
	
	function InsertDuel($mysqli, $idUser, $idTrackWon, $idTrackLost)
	{
		EchoQuery($mysqli,"
			INSERT INTO DuelResult(idUser, idTrackWon, idTrackLost)
			VALUES((SELECT idUser FROM User ORDER BY RAND() LIMIT 1), (SELECT idTrack FROM Track ORDER BY RAND() LIMIT 1), (SELECT idTrack FROM Track ORDER BY RAND() LIMIT 1))
		");
	}
?>


<html>
	<head>
	</head>
	<body>
		<p><a href="peuplement.php?action=clear">Vider la base de données</a></p>
		<p><a href="peuplement.php?action=users">Peupler les utilisateurs</a></p>
		<p>Peupler les tracks et des games (Complet) - voir dossier games_tracks_sql_generator pour générer un .sql de peuplement à partir d'une archive de RSN</p>
		<p><a href="peuplement.php?action=reviews">Peupler les reviews</a></p>
		<p><a href="peuplement.php?action=communities">Peupler les communities</a></p>
		<p><a href="peuplement.php?action=duels">Peupler les duels (n'update pas les ratings)</a></p>
		<p><a href="peuplement.php?action=full">Peuplement entier</a></p>
		<p><a href="index.php">Retour à l'outil principal</a></p>
		<?php
			if (isset($_GET['action']))
			{
				if($_GET['action'] == 'clear')
					ClearDB();
				else if($_GET['action'] == 'users')
					PopulateUsers();
				else if($_GET['action'] == 'reviews')
					PopulateReviews();
				else if($_GET['action'] == 'communities')
					PopulateCommunities();
				else if($_GET['action'] == 'duels')
					PopulateDuels(10);
				else if($_GET['action'] == 'full')
					PopulateFull();
			}
		?>
	</body>

</html>
