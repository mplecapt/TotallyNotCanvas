<?php
session_start();
header('Cache-Control: max-age=900');

if(isset($_POST['username']) && isset($_POST['password'])) {
	$_SESSION['user'] = $_POST['username'];
	$_SESSION['pass'] = $_POST['password'];
	
	header("Location: dashboard.php");
} else {
	header("Location: logout.php?invalid_login");
}

?>