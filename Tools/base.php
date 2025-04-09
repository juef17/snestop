<?php
	header('Content-type: text/html; charset=utf-8');
	require_once('config.php');

	function CreateConnection()
	{
		global $dbhost, $dbusername, $dbpass, $dbname, $dbport;
		$mysqli = new mysqli($dbhost, $dbusername, $dbpass, $dbname, $dbport);
		if ($mysqli->connect_error)
			die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
		else
			return $mysqli;
	}
?>
