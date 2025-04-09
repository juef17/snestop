<?php
	
	require_once("base.php");
	
	if(isset($_POST['dbhost']))
	{
		$dbhost = $_POST['dbhost'];
		$dbusername = $_POST['dbusername'];
		$dbpass = $_POST['dbpass'];
		$dbport = $_POST['dbport'];
	}
	
	$db = CreateConnection();
	
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
									<input type="text" size=10 maxlength=255 name="dbhost" id="dbhost" value="<?= $dbhost ?>">
								</td>
							</tr>
							<tr>
								<td>User:</td>
								<td>
									<input type="text" size=10 maxlength=255 name="dbusername" id="dbusername" value="<?= $dbusername ?>">
								</td>
							</tr>
							<tr>
								<td>Pass:</td>
								<td>
									<input type="text" size=10 maxlength=255 name="dbpass" id="dbpass" value="<?= $dbpass ?>">
								</td>
							</tr>
							<tr>
								<td>Port:</td>
								<td>
									<input type="text" size=10 maxlength=255 name="dbport" id="dbport" value="<?= $dbport ?>">
								</td>
							</tr>
						</table>
						<p><a href="peuplement.php">Peuplement</a></p>
						<p><input type="button" value="Clear" onclick="clearForm(this.form);"></p>
						<p><input type="reset" value="Reset"></p>
						<p><input type="submit" value="Submit"></p>
					</td>
					<td>
						<textarea cols=70 rows=20 name="query" id="query"><?= (isset($_POST['query']) ? $_POST['query'] : file_get_contents("DDL.sql")) ?></textarea>
					</td>
				</tr>
			</table>
		</form>
		<?php

			if(isset($_POST['query']))
			{
				echo "<h2>Executed query:</h2>" . $_POST['query'] . "<br><br>";
				$result = $db->multi_query($_POST['query']);
				if($db->error)
					echo "<h2>ERREUR!</h2>" . $db->error;
				else
				{
					echo "<h2>OK!</h2>";
					if($db->affected_rows > 0) echo $db->affected_rows . " lignes traitées par le dernier query.<br>";
					do
					{
						if($result = $db->store_result())
						{
							echo "<table style='border: 1px solid black;'>";
							while ($row = $result->fetch_row())
							{
								echo "<tr>";
								foreach($row as $colonne) echo "<td style='border: 1px solid black; padding:3px;'>" . $colonne . "</td>";
								echo "</tr>";
							}
							$result->free();
							echo "</table>";
						}
					} while($db->more_results() && $db->next_result());
				}
			}
	
			mysqli_close($db);
			
		?>
	</body>
</html>