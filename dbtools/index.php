<?php
	
	if(!isset($_POST['dbhost'])) require_once("config.php");
	else
	{
		$dbhost = $_POST['dbhost'];
		$dbusername = $_POST['dbusername'];
		$dbpass = $_POST['dbpass'];
		$dbport = $_POST['dbport'];
	}
	
	/*$db = new mysqli($dbhost, $dbusername, $dbpass, "snestop", $dbport);

	if(mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}*/
	
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<script>
			function clearForm(oForm)
			{
				var elements = oForm.elements; 
				oForm.reset();
				for(i=0; i<elements.length; i++)
				{
					field_type = elements[i].type.toLowerCase();
					switch(field_type)
					{
						case "text": 
						case "password": 
						case "textarea":
						case "hidden":	
							elements[i].value = "";
							break;
						case "radio":
						case "checkbox":
							if (elements[i].checked) elements[i].checked = false; 
							break;
						case "select-one":
						case "select-multi":
							elements[i].selectedIndex = -1;
							break;
						default: 
							break;
					}
				}
			}

			function FocusOnInput(id)
			{
				document.getElementById(id).focus();
			}
		</script>
	</head>
	<body onload="FocusOnInput('query');">
		<form action="index.php" method="post" enctype="multipart/form-data">
			<table border=1>
				<tr valign="top" cellpadding=5>
					<td align="center">
						<table>
							<tr>
								<td>Host:</td>
								<td>
									<input type="text" size=10 maxlength=255 name="dbhost" id="dbhost" value="<?php echo $dbhost; ?>">
								</td>
							</tr>
							<tr>
								<td>User:</td>
								<td>
									<input type="text" size=10 maxlength=255 name="dbusername" id="dbusername" value="<?php echo $dbusername; ?>">
								</td>
							</tr>
							<tr>
								<td>Pass:</td>
								<td>
									<input type="text" size=10 maxlength=255 name="dbpass" id="dbpass" value="<?php echo $dbpass; ?>">
								</td>
							</tr>
							<tr>
								<td>Port:</td>
								<td>
									<input type="text" size=10 maxlength=255 name="dbport" id="dbport" value="<?php echo $dbport; ?>">
								</td>
							</tr>
						</table>
						<br><br>
						<input type="button" value="Clear" onclick="clearForm(this.form);"><br><br>
						<input type="reset" value="Reset"><br><br>
						Submit à venir
						<!--<input type="submit" value="Submit">-->
					</td>
					<td>
						<textarea cols=70 rows=20 name="query" id="query"><?php echo file_get_contents("DDL.sql"); ?></textarea>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>

<?php
	
	//mysqli_close($db);
	
?>