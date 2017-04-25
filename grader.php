<?php
session_start();
header('Cache-Control: max-age=900');
readfile('assets/head.html');

if (isset($_SESSION['user']) && isset($_SESSION['id']) && isset($_POST['exam_name'])) {
	$dbhost = "classdb.it.mtu.edu";
	$dbuser = "cs3425gr";
	$dbpass = "cs3425gr";
	$db		= "sameluch";

	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$answers = array();
	foreach($_POST as $key => $value) {
		if (!strstr($key, 'exam_name')) {
			array_push($answers, $value);
		}
	}

	$correct = array();
	$points = array();
	$result = $conn->query("SELECT num_points, correct FROM question WHERE ex_name = '$_POST[exam_name]'");
	while ($row = $result->fetch_assoc()) {
		array_push($correct, $row['correct']);
		array_push($points, $row['num_points']);
	}

	$total_points = 0;
	for ($i = 0; $i < sizeof($correct); $i++) {
		#echo "$correct[$i], $points[$i] : $answers[$i]<br>";
		if ($correct[$i] == $answers[$i]) {
			$total_points += $points[$i];
		}
	}

	$text = implode(",", $answers);

	if ($conn->query("INSERT INTO grade VALUES ('$_SESSION[id]', '$_POST[exam_name]', '$text', $total_points)") === FALSE) {
		echo "Error: " . $conn->error;
	} else {
		echo "
			<div id='msg'>
				<div>
					<h2>Submitted successfully</h2>
					<p>Your exam has been graded and submitted successfully.</p>
					<br>
					<a href='dashboard.php'>Return to Dashboard</a>
				</div>
			</div>
		";
	}

	$conn->close();
} else {
	header("Location: exam.php");
}

readfile('assets/foot.html');
?>