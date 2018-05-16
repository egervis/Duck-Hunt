<!DOCTYPE HTML>
<!-- Edward Gervis -->
<?php
session_start();
?>
<html onkeydown = "enterKey(event);">
	<head>
		<title>Duck Hunt</title>
		<link rel="icon" type = "image" href="favicon.png">
		<style>

		#container
		{
			width: 20%;
			box-shadow: 10px 10px 5px #888888;
			margin: auto;
			border: thin solid #888888;
			margin-top: 20%;
			background-color: white;
		}
		form
		{
			padding: 5px;
		}
		p
		{
			color: #b3b3b3;
			text-align: center;
		}
		input
		{
			background-color: #f2f2f2;
			border: none;
			padding: 5px;
			margin: 5px;
			width: 90%;
			height: 30px;
			font-size: 15pt;
		}
		#register
		{
			display: none;
		}
		button
		{
			margin: 10px;
			margin-right: 0px;
			width: 43%;
			border: none;
			background-color: lightblue;
			font-size: 15pt;
			color: white;
			padding: 5px;
		}
		select
		{
			margin: 10px;
			margin-left: 10px;
			width: 43%;
			border: none;
			font-size: 15pt;
			color: white;
			padding: 5px;
		}
		span
		{
			text-decoration: underline;
			cursor:pointer;
		}
		@keyframes animate
		{
			from {font-size: 50pt;}
			to {font-size: 100pt;}
		}
		h1
		{
			position: absolute;
			width: 100%;
			color: white;
			text-align: center;
			margin-top: -200px;
			animation-name: animate;
    		animation-duration: 3s;
    		animation-iteration-count: infinite;
    		animation-direction: alternate;
		}

		</style>
		<script>
		
		<?php
		if(isset($_SESSION['username']))
			echo "sessionUsername = '".$_SESSION['username']."';\n";
			
		if(isset($_POST['session']))
			echo "endSession = true;\n";
		else
			echo "endSession = false;\n";
			
		if(isset($_COOKIE['color']))
		{
			echo "cookieColor='".$_COOKIE['color']."';\n";
		}
		else
		{
			setcookie('color', 'lightblue', time() + (86400 * 30), "/");
			header('Location: index.php');
		}
		?>
		function initialize()
		{
			registerForm = document.getElementById("register");
			loginForm = document.getElementById("login");
			sessionForm = document.getElementById("sessionForm");
			sessionInput = document.getElementById("sessionInput");
			signInSpan = document.getElementById("signIn");//cookie
			createAccountSpan = document.getElementById("createAccount");//cookie
			buttons = document.getElementsByClassName("buttons");//cookie
			myBody = document.getElementById("bdy");//cookie
			selectTags = document.getElementsByClassName("colorSelect");//cookie
			onSelectChange = true;
			whichKey = "login";
			checkSession();
			if(endSession == true)
				destroySession();
			setColors();
		}
		function changeCookie(num)
		{
			if (onSelectChange == false)
				return;
			cookie(true, selectTags[num].value);
			
		}
		function setColors()
		{
			myBody.style.backgroundColor = cookieColor;
			signInSpan.style.color = cookieColor;
			createAccountSpan.style.color = cookieColor;
			onchange = false;
			for(var i=0; i<buttons.length; i++)
			{
				buttons[i].style.backgroundColor = cookieColor;
				selectTags[i].style.backgroundColor = cookieColor;
				selectTags[i].value = cookieColor;
			}
			onchange = true;
		}
		function cookie(cmd, color)
		{
			var request = new XMLHttpRequest();
			request.onreadystatechange = function()
				{
					if (request.readyState == 4)
					{
						location.href = "index.php";
					}
				}
			var url = "cookie.php?set="+cmd+"&color="+color;
			request.open("GET", url, false);
			request.send(null);
		}
		function destroySession()
		{
			form = document.getElementById("form");
			sessionInput = document.getElementById("input");
			var request = new XMLHttpRequest();
			request.onreadystatechange = function()
				{
					if (request.readyState == 4)
					{
						sessionInput.value = false;
						form.submit();
					}
				}
			var url = "destroySession.php";
			request.open("GET", url, false);
			request.send(null);
		}
		function checkSession()
		{
			if(typeof sessionUsername == "undefined")
				return;
				
			sessionInput.value = sessionUsername;
			sessionForm.submit();
		}
		function switchDisplay(span)
		{
			if (span == signInSpan)
			{
				registerForm.style.display = "none";
				loginForm.style.display = "inline-block";
				whichKey = "login";
			}
			if (span == createAccountSpan)
			{
				registerForm.style.display = "inline-block";
				loginForm.style.display = "none";
				whichKey = "register";
			}
		}
		function submitForm(type)
		{
			if (type == "register")
			{
				username = document.getElementById("username1");
				password = document.getElementById("password1");
				email = document.getElementById("email");
				form = document.getElementById("form1");
				if (username.value == "" || password.value == "" || email.value == "")
				{
					alert("It looks like you missed something. Please fill everything in before continuing.");
				}
				else
				{
					form.submit();
				}
			}
			if (type == "login")
			{
				username = document.getElementById("username2");
				password = document.getElementById("password2");
				form = document.getElementById("form2");
				if (username.value == "" || password.value == "")
				{
					alert("It looks like you missed something. Please fill everything in before continuing.");
				}
				else
				{
					form.submit();
				}
			}
		}
		function enterKey(e)
		{
			if (e.keyCode == 13)
			{
				submitForm(whichKey);
			}
		}

		</script>
	</head>
	
	<body id = "bdy" onload = "initialize()">
		<h1>Duck Hunt</h1>
		<div id = "container">
			<div id = "createForm">
				<div id = "register">
					<form id = "form1" method = "post" action = "check.php">
						<input type="hidden" name = "type" value = "register"/>
						<input id = "username1" type="text" name = "username" placeholder="username"/>
						<input id = "password1" type="password" name = "password" placeholder="password"/>
						<input id = "email" type="text" name = "email" placeholder="email address"/>
					</form>
					<button class = "buttons" style = "cursor:pointer;" onclick = "submitForm('register')">CREATE</button>
					<select class = "colorSelect" onchange = "changeCookie(0);">
						<option value = "lightblue">Lightblue</option>
						<option value = "lightgreen">Lightgreen</option>
						<option value = "orange">Orange</option>
						<option value = "pink">Pink</option>
					</select>
					<p>Already registered? <span id = "signIn" onclick = "switchDisplay(this)">Sign In</span></p>
				</div>
				<div id = "login">
					<form id = "form2" method = "post" action = "check.php">
						<input type="hidden" name = "type" value = "login"/>
						<input id = "username2" type="text" name = "username" placeholder="username"/>
						<input id = "password2" type="password" name = "password" placeholder="password"/>
				</form>
				<button class = "buttons" style = "cursor:pointer;" onclick = "submitForm('login')">LOGIN</button>
				<select class = "colorSelect" onchange = "changeCookie(1);">
						<option value = "lightblue">Lightblue</option>
						<option value = "lightgreen">Lightgreen</option>
						<option value = "orange">Orange</option>
						<option value = "pink">Pink</option>
				</select>
				<p>Not registered? <span id = "createAccount" onclick = "switchDisplay(this)">Create an account</span></p>
				</div>
			</div>
		</div>
		<form id = "sessionForm" method = "post" action = "leaderboard.php"><input id = "sessionInput" type = "hidden" name = "username"/></form>
	</body>
</html>