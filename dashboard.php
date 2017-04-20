<?php
readfile("assets/head.html");
session_start();
header('Cache-Control: max-age=900');

if (isset($_SESSION['user']) && isset($_SESSION['pass'])) {
	$user = $_SESSION['user'];
	$pass = $_SESSION['pass'];
	
	echo "
		<a href='logout.php'>Logout</a><br>
		Hello $user,<br>
		Your password is '$pass'.
	";
} else {
	header("Location: logout.php?invalid_login");
}

readfile("assets/foot.html");
?>