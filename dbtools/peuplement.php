<?php 
	require_once("base.php");

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
	}

	function PopulateFull()
	{
		PopulateUsers();
		PopulateGames();
		PopulateTracks();
		PopulateReviews();
		PopulateCommunities();
		//additional populations here!
	}

	//Users population
	function PopulateUsers()
	{
		$mysqli = CreateConnection();
		InsertUser($mysqli, 'NULL', 'ramoutz@panus.com', 'encrypted', 'french', 1, 0, 'admin', 1);
		InsertUser($mysqli, 'NULL', 'limoutz@panus.com', 'encrypted', 'french', 1, 0, 'limoutz', 0);
		InsertUser($mysqli, 'NULL', 'serge@panus.com', 'encrypted', 'french', 1, 0, 'serge', 0);
		InsertUser($mysqli, 'NULL', 'robin@panus.com', 'encrypted', 'french', 1, 0, 'robin', 0);
		InsertUser($mysqli, 'NULL', 'barry@panus.com', 'encrypted', 'french', 1, 0, 'barry', 0);
		InsertUser($mysqli, 'NULL', 'moe@panus.com', 'encrypted', 'french', 1, 0, 'moe', 0);
		EchoCount($mysqli, 'User');
	}
	
	function InsertUser($mysqli, $idCommunity, $email, $password, $language, $canStreamMP3, $autoPlay, $userName, $isAdmin)
	{
		EchoQuery($mysqli,"
			INSERT INTO User(idCommunity, email, password, language, canStreamMP3, autoPlay, userName, isAdmin)
			VALUES($idCommunity, '$email', '" . MD5($password) . "', '$language', $canStreamMP3, $autoPlay, '$userName', $isAdmin)
		");
	}
	
	function PopulateGames()
	{
		$mysqli = CreateConnection();
		include("peuplement_games.php");
		EchoCount($mysqli, 'Game');
	}
	
	function InsertGame($mysqli, $titleJap, $titleEng, $screenshotURL, $rsnFileURL)
	{
		EchoQuery($mysqli,"
			INSERT INTO Game(titleJap, titleEng, screenshotURL, rsnFileURL)
			VALUES('" . addslashes($titleJap) . "', '" . addslashes($titleEng) . "', '" . addslashes($screenshotURL) . "', 'rsn/" . addslashes($titleEng) . ".rsn')
		");
	}
	
	function PopulateTracks()
	{
		$mysqli = CreateConnection();
		include("peuplement_tracks.php");
		EchoCount($mysqli, 'Track');
	}
	
	function InsertTrack($mysqli, $title, $length, $fadeLength, $composer, $turnedOffByAdmin, $screenshotURL, $isJingle, $glicko2RD, $glicko2rating, $glicko2sigma, $eloRating, $spcURL, $spcEncodedURL, $gameTitle)
	{
		EchoQuery($mysqli, "
			INSERT INTO Track(idGame, title, length, fadeLength, composer, turnedOffByAdmin, screenshotURL, isJingle, glicko2RD, glicko2rating, glicko2sigma, eloRating, spcURL, spcEncodedURL)
			VALUES((SELECT idGame FROM Game WHERE titleEng = '" . addslashes($gameTitle) . "'), '" . addslashes($title) . "', '$length', '$fadeLength', '" . addslashes($composer) . "', '$turnedOffByAdmin', '" . addslashes($screenshotURL) . "', '$isJingle', '$glicko2RD','$glicko2rating','$glicko2sigma','$eloRating', 'spc/" . addslashes($spcURL) . ".spc', '$spcEncodedURL')
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
?>


<html>
	<head>
	</head>
	<body>
		<p><a href="peuplement.php?action=clear">Vider la base de données</a></p>
		<p><a href="peuplement.php?action=users">Peupler les utilisateurs</a></p>
		<p><a href="peuplement.php?action=games">Peupler les games</a></p>
		<p><a href="peuplement.php?action=tracks">Peupler les tracks</a></p>
		<p><a href="peuplement.php?action=reviews">Peupler les reviews</a></p>
		<p><a href="peuplement.php?action=communities">Peupler les communities</a></p>
		<p><a href="peuplement.php?action=full">Peuplement entier</a></p>
		<p><a href="index.php">Retour à l'outil principal</a></p>
		<?php
			if (isset($_GET['action']))
			{
				if($_GET['action'] == 'clear')
					ClearDB();
				else if($_GET['action'] == 'users')
					PopulateUsers();
				else if($_GET['action'] == 'games')
					PopulateGames();
				else if($_GET['action'] == 'tracks')
					PopulateTracks();
				else if($_GET['action'] == 'reviews')
					PopulateReviews();
				else if($_GET['action'] == 'communities')
					PopulateCommunities();
				else if($_GET['action'] == 'full')
					PopulateFull();
			}
		?>
	</body>

</html>
