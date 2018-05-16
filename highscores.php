<?php
	$connection = new PDO("mysql:hostname=localhost;dbname=c9","frogsteel1","");
	$username = $_GET['username'];
	$score = $_GET['score'];
	$command = "UPDATE `users` SET `score` = $score WHERE `username` = '$username'";
	$result = $connection->prepare($command);
	$result->execute();
?>