<?php 
	require_once("base.php");

	function EchoQuery($mysqli, $query)
	{
		if ($mysqli->query($query) === TRUE)
			echo 'Query Success: ' . $query . '<br />';
		else
			echo 'Query failed: ' . $mysqli->error . '<br />';
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
	}

	function PopulateFull()
	{
		PopulateUsers();
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
			VALUES($idCommunity, '$email', '$password', '$language', $canStreamMP3, $autoPlay, '$userName', $isAdmin)
		");
	}
?>


<html>
	<head>
	</head>
	<body>
		<p><a href="peuplement.php?action=clear">Vider la base de données</a></p>
		<p><a href="peuplement.php?action=users">Peupler les utilisateurs</a></p>
		<p><a href="peuplement.php?action=full">Peuplement entier</a></p>
		<?php
			if (isset($_GET['action']))
			{
				if($_GET['action'] == 'clear')
					ClearDB();
				else if($_GET['action'] == 'users')
					PopulateUsers();
				else if($_GET['action'] == 'full')
					PopulateFull();
			}
		?>
	</body>

</html>
