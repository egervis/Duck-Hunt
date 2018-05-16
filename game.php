<!DOCTYPE HTML>
<!-- Edward Gervis -->
<?php
session_start();
?>
<html id = "htm">
	<head>
		<title>Duck Hunt</title>
		<link rel="icon" href="favicon.png">
		<style>

		#gameContainer
		{
			display: block;
			width: 100%;
			margin: auto;
			position: relative;
			z-index: 0;
		}
		#scoreDiv
		{
			display: inline-block;
			position: absolute;
			font-size: 20pt;
			background-color: black;
			color: white;
			border-radius: 12pt;
			margin: 2%;
			padding: 5px;
			top: 0px;
			left: 0px;
			border: medium solid #888888;
		}
		#winner
		{
			display: none;
			position: absolute;
			font-size: 20pt;
			background-color: black;
			color: white;
			border-radius: 12pt;
			padding: 5px;
			border: medium solid #888888;
			left: 10%;
			right: 10%;
		}
		#livesDiv
		{
			display: inline-block;
			position: absolute;
			font-size: 20pt;
			background-color: black;
			color: white;
			border-radius: 12pt;
			padding: 5px;
			border: medium solid #888888;
			top: 0px;
			right: 0px;
			margin: 2%;
		}
		.borders
		{
			height: 1000px;
			z-index: 2;
			position: relative;
		}
		table
		{
			width:101%;
			position:fixed;
			top: -5px;
			left:-5px;
		}
		img.bg
		{	
			position:relative;
			top:0px;
			left:0px;
			width:105%;
			z-index:-1;
			height:105%;
		}
		body
		{
			text-align: center;
		}
		#duck1
		{
			position: absolute;
			display: block;
			width: 8%;
			z-index: 1;
			top: 10%;
			left: -20%;
		}
		#duck2
		{
			position: absolute;
			display: block;
			width: 8%;
			z-index: 1;
			top: 20%;
			left: -20%;
		}
		#duck3
		{
			position: absolute;
			display: block;
			width: 8%;
			z-index: 1;
			top: 30%;
			left: -20%;
		}

		</style>
		<script src = "utilities1.js"></script>
		<script>

		<?php
			if(isset($_SESSION['username']))
			{
			$username = $_POST['username'];
			$score = $_POST['score'];
			echo "username = '".$username."' ;\n";
			echo "topScore = ".$score.";\n";
			echo "cookieColor = '".$_COOKIE['color']."';\n";
			}
			else
				header('Location: index.php');
		?>
		function initialize()
		{
			var mainContainer = document.getElementById("gameContainer");
			bg = document.getElementById("BG");
			scoreDiv = document.getElementById("scoreDiv");
			scoreSpan = document.getElementById("score");
			winDiv = document.getElementById("winner");
			livesSpan = document.getElementById("lives");
			form = document.getElementById("form");
			usernameInput = document.getElementById("usernameInput");
			body = document.getElementById("bdy");//cookie
			borders = document.getElementsByClassName("borders");//cookie
			html = document.getElementById("htm");
			environment = new Object();
			environment.mainContainer = mainContainer;
			environment.mainBottom = environment.mainContainer.clientHeight;
			environment.mainRight = environment.mainContainer.clientWidth;
			looselife = 0;
			frameRate = 1000/60;
			gameOver = false;
			waveStart = true;
			score = 0;
			wave = 0;
			readyArray = [true, true, true];
			duck1 = new ducks((document.getElementById("duck1")), 1);
			duck2 = new ducks((document.getElementById("duck2")), 2);
			duck3 = new ducks((document.getElementById("duck3")), 3);
			positionElements();
			winDiv.style.display = "inline-block";
			winDiv.innerHTML = "Ready...";
			setTimeout(nextRound, 2000);
			resize();
			display();
			setColors();
		}
		function setColors()
		{
			body.style.backgroundColor = cookieColor;
			for(var i=0; i<borders.length; i++)
			{
				borders[i].style.backgroundColor = cookieColor;
			}
		}
		function resize()
		{
			environment.mainContainer.style.top = -1*(screen.availHeight/4)+"px";
			if(window.outerHeight == screen.availHeight)
			{
				environment.mainContainer.style.top = -100+"px";
			}
			environment.mainBottom = environment.mainContainer.clientHeight;
			environment.mainRight = environment.mainContainer.clientWidth;
			checkDucksDuringResize();
			positionElements();
		}
		function checkDucksDuringResize()
		{
			if(waveStart==false)
			{
				if(duck1.transition==false&&duck2.transition==false&&duck3.transition==false)	
				{
					duck1.xBottomPosition = environment.mainRight/2;
					duck1.yPosition = getRandomInteger(0,environment.mainBottom-duck1.img.clientHeight);
					duck1.yBottomPosition = duck1.yPosition + duck1.img.clientHeight;
					duck1.xPosition = duck1.xBottomPosition - duck1.img.clientWidth;

					duck2.xBottomPosition = environment.mainRight/2;
					duck2.yPosition = getRandomInteger(0,environment.mainBottom-duck2.img.clientHeight);
					duck2.yBottomPosition = duck2.yPosition + duck2.img.clientHeight;
					duck2.xPosition = duck2.xBottomPosition - duck2.img.clientWidth;

					duck3.xBottomPosition = environment.mainRight/2;
					duck3.yPosition = getRandomInteger(0,environment.mainBottom-duck3.img.clientHeight);
					duck3.yBottomPosition = duck3.yPosition + duck3.img.clientHeight;
					duck3.xPosition = duck3.xBottomPosition - duck3.img.clientWidth;
				}
			}
			display();
		}
		function positionElements()
		{
			winDiv.style.top = environment.mainBottom/2+"px";
		}
		function nextRound()
		{
			if (gameOver == true)
			{
				winDiv.style.display = "inline-block";
				if(score>topScore)
				{
					winDiv.innerHTML = "New High Score! Your score is "+score+". Redirecting to leaderboard...";
					sendRequest();
				}
				else
				{
					winDiv.innerHTML = "You lose. Your score is "+score+". Redirecting to leaderboard...";
					setTimeout(redirect, 2000);
				}
			}
			for(i=0; i<readyArray.length; i++)
			{
				if(readyArray[i]==false)
					return;
			}
			if(gameOver == false)
			{
				waveStart = true;
				wave++;
				winDiv.style.display = "inline-block";
				winDiv.innerHTML = "Wave "+wave;
				setTimeout(sendWave, 1000)
			}
		}
		function redirect()
		{
			usernameInput.value = username;
			form.submit();
		}
		function sendRequest()
		{
			var request = new XMLHttpRequest();
			request.onreadystatechange = function()
				{
					if (request.readyState == 4)
					{
						setTimeout(redirect, 2000);
					}
				}
			var url = "highscores.php?username="+username+"&score="+score;
			request.open("GET", url, true);
			request.send(null);
		}
		function sendWave()
		{
			waveStart = false;
			winDiv.style.display = "none";
			winDiv.innerHTML = "";
			duck1.enter();
			duck2.enter();
			duck3.enter();
		}
		function shootDown(num)
		{
			if(gameOver == true)
				return;
			if(num == 1)
			{
				if(duck1.rip == false)
					duck1.shot();
			}
			if(num == 2)
			{
				if(duck2.rip == false)
					duck2.shot();
			}
			if(num == 3)
			{
				if(duck3.rip == false)
					duck3.shot();
			}
		}
		function directonGenerator(num)
		{
			if(num == 1)
				direction1 = getRandomInteger(1,11);
			if(num == 2)
				direction2 = getRandomInteger(1,11);
			if(num == 3)
				direction3 = getRandomInteger(1,11);
		}
		function ducks(image, num)
		{
			this.img = image;
			this.img.setAttribute("object", this)
			this.speed = 3;
			this.directionString = "directonGenerator("+num+")";
			this.direction = num;
			this.duckName = "duck"+num;
			this.directionTimer = setInterval(this.directionString, 1000);
			this.rip = false;
			this.transition = false;
			var duckTimer = "temp";
			var enterTimer = "temp";
			var leaveTimer = "temp";
			var duckStopTimer = "temp";
			this.enter = function()
			{
				this.xBottomPosition = getRandomInteger(-500,-100);
				this.yPosition = getRandomInteger(0,environment.mainBottom-this.img.clientHeight);
				this.yBottomPosition = this.yPosition + this.img.clientHeight;
				this.xPosition = this.xBottomPosition - this.img.clientWidth;
				directonGenerator(this.direction);
				this.speed = getRandomInteger(3,3+wave);
				this.rip = false;
				this.transition = true;
				enterTimer = setInterval(this.duckName+".enterSetup1()", frameRate);
				readyArray[this.direction-1] = false;
			}
			this.enterSetup1 = function()
			{
				this.xPosition +=this.speed;
				this.xBottomPosition +=this.speed;
				this.img.src = "ducks/6.png";
				if (this.xPosition<-5-this.img.clientWidth||this.xBottomPosition>environment.mainRight+this.img.clientWidth+5||this.yPosition<-5-this.img.clientHeight||this.yBottomPosition>environment.mainBottom+this.img.clientHeight+5)
				{
					this.yPosition = getRandomInteger(0,environment.mainBottom-this.img.clientHeight);
					this.yBottomPosition = this.yPosition + this.img.clientHeight;
				}
				display();
				if(this.xPosition>(environment.mainRight/2))
					this.enterSetup2();
			}
			this.enterSetup2 = function()
			{
				clearInterval(enterTimer);
				enterTimer = "temp";
				this.transition = false;
				this.duckMoveTimerStart();
			}
			this.duckMoveTimerStart = function()
			{
				duckTimer = setInterval(this.duckName+".move(direction"+this.direction+");", frameRate);
				duckStopTimer = setTimeout(this.duckName+".duckMoveTimerStop()", 10000);
			}
			this.shot = function()
			{
				clearTimeout(duckStopTimer);
				duckStopTimer = "temp";
				clearInterval(duckTimer);
				duckTimer = "temp";
				clearInterval(enterTimer);
				enterTimer = "temp";
				clearInterval(leaveTimer);
				leaveTimer = "temp";
				this.img.src = "ducks/shot.png";
				this.rip = true;
				setTimeout(this.duckName+".shotSetup1()", 1000);
			}
			this.shotSetup1 = function()
			{
				this.xBottomPosition = getRandomInteger(-500,-100);
				this.yPosition = getRandomInteger(0,environment.mainBottom-this.img.clientHeight);
				this.yBottomPosition = this.yPosition + this.img.clientHeight;
				this.xPosition = this.xBottomPosition - this.img.clientWidth;
				score += this.speed*123;
				readyArray[this.direction-1] = true;
				display();
				nextRound();
			}
			this.duckMoveTimerStop = function()
			{
				clearInterval(duckTimer);
				duckTimer = "temp";
				this.leave();
			}
			this.leave = function()
			{
				leaveTimer = setInterval(this.duckName+".leaveSetup1()", frameRate);
			}
			this.leaveSetup1 = function()
			{
				this.xPosition -=this.speed;
				this.xBottomPosition -=this.speed;
				this.img.src = "ducks/5.png";
				this.transition = true;
				if (this.xPosition<-5-this.img.clientWidth||this.xBottomPosition>environment.mainRight+this.img.clientWidth+5||this.yPosition<-5-this.img.clientHeight||this.yBottomPosition>environment.mainBottom+this.img.clientHeight+5)
				{
					this.yPosition = getRandomInteger(0,environment.mainBottom-this.img.clientHeight);
					this.yBottomPosition = this.yPosition + this.img.clientHeight;
				}
				display();
				if(this.xBottomPosition<(-1*(this.speed+this.img.clientWidth)))
					this.leaveSetup2();
			}
			this.leaveSetup2 = function()
			{
				clearInterval(leaveTimer);
				leaveTimer = "temp";
				this.transition = false;
				looselife++;
				if(looselife>=10)
				{
					gameOver = true;
					looselife = 10;
				}
				readyArray[this.direction-1] = true;
				display();
				nextRound();
			}
			this.move = function(movement)
			{
				if (this.xPosition<-5-this.img.clientWidth||this.xBottomPosition>environment.mainRight+this.img.clientWidth+5||this.yPosition<-5-this.img.clientHeight||this.yBottomPosition>environment.mainBottom+this.img.clientHeight+5)
				{
					this.xBottomPosition = environment.mainRight/2;
					this.yPosition = getRandomInteger(0,environment.mainBottom-this.img.clientHeight);
					this.yBottomPosition = this.yPosition + this.img.clientHeight;
					this.xPosition = this.xBottomPosition - this.img.clientWidth;
					display();
					return;
				}
				if (movement == 1 || movement == 9)
				{
					this.xPosition += this.speed;
					this.yPosition += this.speed;
					this.xBottomPosition += this.speed;
					this.yBottomPosition += this.speed;
					if (this.xPosition<0||this.xBottomPosition>environment.mainRight||this.yPosition<0||this.yBottomPosition>environment.mainBottom)
					{
						this.xPosition -= this.speed;
						this.yPosition -= this.speed;
						this.xBottomPosition -= this.speed;
						this.yBottomPosition -= this.speed;
						directonGenerator(this.direction);
						return;
					}
				}
				if (movement == 2 || movement == 10)
				{
					this.xPosition += this.speed;
					this.yPosition -= this.speed;
					this.xBottomPosition += this.speed;
					this.yBottomPosition -= this.speed;
					if (this.xPosition<0||this.xBottomPosition>environment.mainRight||this.yPosition<0||this.yBottomPosition>environment.mainBottom)
					{
						this.xPosition -= this.speed;
						this.yPosition += this.speed;
						this.xBottomPosition -= this.speed;
						this.yBottomPosition += this.speed;
						directonGenerator(this.direction);
						return;
					}
				}
				if (movement == 3)
				{
					this.xPosition -= this.speed;
					this.yPosition -= this.speed;
					this.xBottomPosition -= this.speed;
					this.yBottomPosition -= this.speed;
					if (this.xPosition<0||this.xBottomPosition>environment.mainRight||this.yPosition<0||this.yBottomPosition>environment.mainBottom)
					{
						this.xPosition += this.speed;
						this.yPosition += this.speed;
						this.xBottomPosition += this.speed;
						this.yBottomPosition += this.speed;
						directonGenerator(this.direction);
						return;
					}
				}
				if (movement == 4)
				{
					this.xPosition -= this.speed;
					this.yPosition += this.speed;
					this.xBottomPosition -= this.speed;
					this.yBottomPosition += this.speed;
					if (this.xPosition<0||this.xBottomPosition>environment.mainRight||this.yPosition<0||this.yBottomPosition>environment.mainBottom)
					{
						this.xPosition += this.speed;
						this.yPosition -= this.speed;
						this.xBottomPosition += this.speed;
						this.yBottomPosition -= this.speed;
						directonGenerator(this.direction);
						return;
					}
				}
				if (movement == 5)
				{
					this.xPosition -= this.speed;
					this.xBottomPosition -= this.speed;
					if (this.xPosition<0||this.xBottomPosition>environment.mainRight||this.yPosition<0||this.yBottomPosition>environment.mainBottom)
					{
						this.xPosition += this.speed;
						this.xBottomPosition += this.speed;
						directonGenerator(this.direction);
						return;
					}
				}
				if (movement == 6 || movement == 11)
				{
					this.xPosition += this.speed;
					this.xBottomPosition += this.speed;
					if (this.xPosition<0||this.xBottomPosition>environment.mainRight||this.yPosition<0||this.yBottomPosition>environment.mainBottom)
					{
						this.xPosition -= this.speed;
						this.xBottomPosition -= this.speed;
						directonGenerator(this.direction);
						return;
					}
				}
				if (movement == 7)
				{
					this.yPosition -= this.speed;
					this.yBottomPosition -= this.speed;
					if (this.xPosition<0||this.xBottomPosition>environment.mainRight||this.yPosition<0||this.yBottomPosition>environment.mainBottom)
					{
						this.yPosition += this.speed;
						this.yBottomPosition += this.speed;
						directonGenerator(this.direction);
						return;
					}
				}
				if (movement == 8)
				{
					this.yPosition += this.speed;
					this.yBottomPosition += this.speed;
					if (this.xPosition<0||this.xBottomPosition>environment.mainRight||this.yPosition<0||this.yBottomPosition>environment.mainBottom)
					{
						this.yPosition -= this.speed;
						this.yBottomPosition -= this.speed;
						directonGenerator(this.direction);
						return;
					}
				}
				this.img.src = "ducks/"+movement+".png";
				display();
			}
		}
		function display()
		{
			duck1.img.style.top = duck1.yPosition+"px";
			duck1.img.style.left = duck1.xPosition+"px";
			duck2.img.style.top = duck2.yPosition+"px";
			duck2.img.style.left = duck2.xPosition+"px";
			duck3.img.style.top = duck3.yPosition+"px";
			duck3.img.style.left = duck3.xPosition+"px";
			scoreSpan.innerHTML = score;
			livesSpan.innerHTML = 10-looselife;
		}
		</script>
	</head>
	
	<body onload = "initialize();" onresize="resize();" id = "bdy">
		<table>
			<tr>
				<td class = "borders"></td>
				<td style = "width:60%;">
					<div id = "gameContainer" style = "cursor: url(crosshair.cur), default;">
						<img id = "BG" class = "bg" src="gameBackground.png" draggable = "false"/>
						<img id = "duck1" onclick = "shootDown(1)" src="ducks/6.png" draggable = "false">
						<img id = "duck2" onclick = "shootDown(2)" src="ducks/6.png" draggable = "false">
						<img id = "duck3" onclick = "shootDown(3)" src="ducks/6.png" draggable = "false">
						<div id = "scoreDiv">Score: <span id = "score"></span></div>
						<div id = "winner"></div>
						<div id = "livesDiv">Lives: <span id = "lives">10</span></div>
					</div>
				</td>
				<td class = "borders"></td>
			</tr>
		</table>
		<form id = "form" method = "post" action = "leaderboard.php" style = "display:none;"><input id = "usernameInput" type = "hidden" name = "username"/></form>
	</body>
</html>