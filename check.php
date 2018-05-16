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

		span
		{
			display: inline-block;
			box-shadow: 10px 10px 5px #888888;
			border: thin solid #888888;
			margin-top: 25%;
			background-color: white;
			padding: 5px;
			font-size: 30px;
			color: #b3b3b3;
		}
		body
		{
			text-align: center;
		}

		</style>
		<script src = "utilities1.js"></script>
		<script>

		<?php
			$connection = new PDO("mysql:hostname=localhost;dbname=c9","frogsteel1","");
			if ($_POST['type'] == "register")
			{
				$username = $_POST['username'];
				$password = $_POST['password'];
				$email = $_POST['email'];
				$score = 0;

				$command = "SELECT * FROM `users` WHERE `username` = '$username'";
				$result = $connection->prepare($command);
				$result->execute();
				$data = $result->fetch(PDO::FETCH_ASSOC);
				if (empty($data))
				{
					$command = "INSERT INTO `users`(`username`, `password`, `email`, `score`) VALUES ('$username','$password','$email','$score')";
					$result = $connection->prepare($command);
					$result->execute();
					echo "result = 'created';\n";
					$_SESSION['username'] = $username;
				}
				else
				{
					echo "result = 'taken';\n";
					session_unset(); 
					session_destroy();
				}
			}
			if ($_POST['type'] == "login")
			{
				$username = $_POST['username'];
				$password = $_POST['password'];
				$command = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password'";
				$result = $connection->prepare($command);
				$result->execute();
				$data = $result->fetch(PDO::FETCH_ASSOC);
				if (empty($data))
				{
					echo "result = 'invalid';\n";
					session_unset(); 
					session_destroy();
				}
				else
				{
					echo "result = 'good';\n";
					$_SESSION['username'] = $username;
				}
			}
			echo "username = '".$username."';\n";
			echo "cookieColor = '".$_COOKIE['color']."';\n";
		?>
		
		function initialize()
		{
			mybody = document.getElementById("bdy");//cookie
			anchors = document.getElementsByClassName("anchor");//cookie
			check();
			setColors();
		}
		function setColors()
		{
			mybody.style.backgroundColor = cookieColor;
			for(var i=0; i<anchors.length; i++)
			{
				anchors[i].style.color = cookieColor;
			}
		}
		function check()
		{
			form = document.getElementById("form");
			input = document.getElementById("name");
			input.value = username;
			span = document.getElementById(result);
			span.style.display = "inline-block";
			if(result == "good"||result == "created")
				setTimeout(submitForm, 2000);
		}
		function submitForm()
		{
			form.submit();
		}

		</script>
	</head>
	
	<body id = "bdy" onload = "initialize()">
		<span id = "created" style = "display:none;">Account Created. The game is loading...</span>
		<span id = "taken" style = "display:none;">This username is already taken. <a class = "anchor" href = 'index.php'>Go Back</a> to select another one.</span>
		<span id = "invalid" style = "display:none;">Invalid username or password. <a class = "anchor" href = 'index.php'>Go Back</a></span>
		<span id = "good" style = "display:none;">Loading...</span>

		<form id = "form" method = "post" action = "leaderboard.php">
			<input id = "name" type = "hidden" name = "username">
		</form>
	</body>
</html>