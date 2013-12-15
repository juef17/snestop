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
		echo "Count for table $table : " . $data['total'];
	}

	function ClearDB()
	{
		$mysqli = CreateConnection();
		//additional deletions here!
		EchoQuery($mysqli, 'DELETE FROM User;');
		EchoQuery($mysqli, 'DELETE FROM Game;');
		EchoQuery($mysqli, 'DELETE FROM Track;');
	}

	function PopulateFull()
	{
		PopulateUsers();
		PopulateGames();
		PopulateTracks();
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
		echo "<br>Peuplement de tracks... : ";
		EchoCount($mysqli, 'Track');
	}
	
	function InsertTrack($mysqli, $title, $length, $fadeLength, $composer, $turnedOffByAdmin, $screenshotURL, $isJingle, $glicko2RD, $glicko2rating, $glicko2sigma, $eloRating, $spcURL, $spcEncodedURL, $gameTitle)
	{
		EchoQuery($mysqli, "
			INSERT INTO Track(idGame, title, length, fadeLength, composer, turnedOffByAdmin, screenshotURL, isJingle, glicko2RD, glicko2rating, glicko2sigma, eloRating, spcURL, spcEncodedURL)
			VALUES((SELECT idGame FROM Game WHERE titleEng = '" . addslashes($gameTitle) . "'), '" . addslashes($title) . "', '$length', '$fadeLength', '" . addslashes($composer) . "', '$turnedOffByAdmin', '" . addslashes($screenshotURL) . "', '$isJingle', '$glicko2RD','$glicko2rating','$glicko2sigma','$eloRating', 'spc/" . addslashes($spcURL) . ".spc', '$spcEncodedURL')
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
				else if($_GET['action'] == 'full')
					PopulateFull();
			}
		?>
	</body>

</html>
