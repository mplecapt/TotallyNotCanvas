<?php
session_start();
header('Cache-Control: max-age=900');

if(isset($_POST['username']) && isset($_POST['password'])) {
	login($_POST['username'], $_POST['password']);	
} else {
	header("Location: logout.php?invalid_login");
}

function login ($user, $pass) {
	$dbhost = "classdb.it.mtu.edu";
	$dbuser = "cs3425gr";
	$dbpass = "cs3425gr";
	$db		= "sameluch";
	
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
	
	if($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
		
	$q = "SELECT * FROM student WHERE username = '$user' AND password = '$pass'";
	$result = $conn->query($q);
	
	if ($result->num_rows > 0) {
		$_SESSION['user'] = $_POST['username'];
		$_SESSION['pass'] = $_POST['password'];
		
		mysqli_close($conn);
		header("Location: dashboard.php");
	} else {
		mysqli_close($conn);
		header("Location: logout.php?invalid_login");
	}
}

?>