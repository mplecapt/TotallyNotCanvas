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
		
	$stmt = $conn->prepare("SELECT * FROM student WHERE username = ?");
	$stmt->bind_param('s', $user);

	$stmt->execute();
	$result = $stmt->get_result();
	
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		mysqli_close($conn);
		if(verifyPass($pass, $row['password'])) {
			$_SESSION['user'] = $_POST['username'];
			$_SESSION['pass'] = $_POST['password'];

			header("Location: dashboard.php");
			exit;
		} else {
			header("Location: logout.php?invalid_login");
		}
	} else {
		mysqli_close($conn);
		header("Location: logout.php?invalid_login");
	}
}

function verifyPass ($user_input, $hashed_pass) {
	$ui = strip_tags($user_input);
	$hp = strip_tags($hashed_pass);
	$test = hash("sha512",$ui);

	return hash_equals($hp, $test);
}

?>