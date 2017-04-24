<?php
readfile("assets/head.html");
session_start();
header('Cache-Control: max-age=900');

if (isset($_SESSION['user']) && isset($_SESSION['pass'])) {
	$user = $_SESSION['user'];
	$pass = $_SESSION['pass'];
	
	echo "
		<div id='sidebar' class='test'>
			<h1><a href='logout.php'>A</a></h1>
			<h1>B</h1>
			<h1>C</h1>
		</div>
	";
} else {
	header("Location: logout.php?invalid_login");
}

readfile("assets/foot.html");
?>