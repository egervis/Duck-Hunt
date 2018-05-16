<!DOCTYPE HTML>
<!-- Edward Gervis -->
<?php
session_start();
?>
<html>
	<head>
		<title>Duck Hunt</title>
		<link rel="icon" href="favicon.png">
		<style>
		
		table
		{
			border: thin solid white;
			margin: auto;
			width: 50%;
			margin-top: 8%;
			background-color: #b3b3b3;
			color: white;
			text-align: center;
			font-size: 30pt;
		}
		input
		{
			margin: auto;
			display: block;
			box-shadow: 10px 10px 5px #888888;
			border: thin solid #888888;
			background-color: #b3b3b3;
			padding: 5px;
			font-size: 30px;
			color: white;
			margin-top: 2%;
		}
		td, th
		{
			border: thin solid white;
			height: 60px;
		}
		p
		{
			position: absolute;
			top: 0px;
			right: 0px;
			margin-right: 5%;
			margin-top: 4%;
			padding: 10px;
			background-color: red;
			color: white;
			box-shadow: 10px 10px 5px #888888;
			border: medium solid #888888;
			font-size: 30px;
		}
		#log_off
		{
			text-decoration: underline;
		}
		
		</style>
		<script>

		<?php
			if(isset($_SESSION['username']) == false)
			{
				header('Location: index.php');
			}
			$connection = new PDO("mysql:hostname=localhost;dbname=c9","frogsteel1","");
			$command = "SELECT * FROM `users`";
			$result = $connection->prepare($command);
			$result->execute();
			$numRows = $result->rowCount();
			$intNumRows = (int)$numRows;
			if($intNumRows>5)
			{
				$intNumRows = 5;
			}
			$command = "SELECT `username`,`score` FROM `users` ORDER BY `score` DESC LIMIT $intNumRows";
			$result = $connection->prepare($command);
			$result->execute();
			$data = $result->fetchAll(PDO::FETCH_ASSOC);
			$ctr = 0;
			$players = array();
			$scores = array();
			for ($i = 0; $i<$intNumRows; $i++)
			{
				$players[$i] = $data[$i]['username'];
				$scores[$i] = $data[$i]['score'];
				$ctr = $i;
			}
			$username = $_POST['username'];
			$command = "SELECT `score` FROM `users` WHERE `username` = '$username'";
			$result = $connection->prepare($command);
			$result->execute();
			$data = $result->fetch(PDO::FETCH_ASSOC);
			$score = $data['score'];
			echo "ctr = ".$ctr.";\n";
			echo "username = '".$username."';\n";
			echo "score = ".$score.";\n";
			echo "playersArray = ".json_encode($players).";\n";
			echo "scoresArray = ".json_encode($scores).";\n";
			echo "cookieColor = '".$_COOKIE['color']."';\n";
		?>

		function initialize()
		{
			myBody = document.getElementById("bdy");//cookie
			displayScores();
			setColors()
			setTimeout(destroySession, 300000);
		}
		function setColors()
		{
			myBody.style.backgroundColor = cookieColor;
		}
		function displayScores()
		{
			tdU = document.getElementsByClassName("username");
			tdS = document.getElementsByClassName("score");
			for(var i=0; i<ctr+1; i++)
			{
				tdU[i].innerHTML = playersArray[i];
				tdS[i].innerHTML = scoresArray[i];
			}
			userScore = document.getElementById("userScore");
			userScore.innerHTML = score;
			usernameInput = document.getElementById("usernameInput");
			scoreInput = document.getElementById("scoreInput");
			usernameInput.value = username;
			scoreInput.value = score;
		}
		function destroySession()
		{
			var request = new XMLHttpRequest();
			request.onreadystatechange = function()
				{
					if (request.readyState == 4)
					{
						location.href = "index.php";
					}
				}
			var url = "destroySession.php";
			request.open("GET", url, true);
			request.send(null);
		}

		</script>
	</head>
	
	<body id = "bdy" onload = "initialize();">
	<table>
		<tr>
			<th colspan = "2">
				Leaderboard
			</th>
		</tr>
		<tr>
			<th>
				username
			</th>
			<th>
				score
			</th>
		</tr>
		<tr>
			<td class = "username">----</td>
			<td class = "score">----</td>
		</tr>
		<tr>
			<td class = "username">-----</td>
			<td class = "score">-----</td>
		</tr>
		<tr>
			<td class = "username">-----</td>
			<td class = "score">-----</td>
		</tr>
		<tr>
			<td class = "username">-----</td>
			<td class = "score">-----</td>
		</tr>
		<tr>
			<td class = "username">-----</td>
			<td class = "score">-----</td>
		</tr>
		<tr>
			<td colspan = "2">Your high score: <span id = "userScore">-----</span></td>
		</tr>
	</table>
	<form method = "post" action = "game.php">
		<input id = "usernameInput" type = "hidden" name = "username"/>
		<input id = "scoreInput" type = "hidden" name = "score"/>
		<input type = "submit" style = "cursor:pointer;" value = "Start"/>
	</form>
	<p><span id = "log_off" style = "color:white;display:inline-block;cursor:pointer;" onclick = "destroySession();">Log Off</span></p>
	</body>
</html>