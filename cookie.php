<?php
	if($_GET['set'] == true)
	{
		setcookie('color', $_GET['color'], time() + (86400 * 30), "/");
	}
?>