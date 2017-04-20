<?php
	session_start();
	session_destroy();
	if(isset($_GET['invalid_login'])) {
		header('Location: /cs3425/?invalid_login');
	} else {
		header('Location: /cs3425/');
	}
	exit;
?>
