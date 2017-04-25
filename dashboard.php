<?php
readfile("assets/head.html");
session_start();
header('Cache-Control: max-age=900');

if (isset($_SESSION['user']) && isset($_SESSION['id'])) {
	$user = $_SESSION['user'];
	$id = $_SESSION['id'];
	
	readfile("assets/sidebar.html");

	echo "<div class='col-xs-11' id='dash'>
			<h2>Dashboard</h2>
			<hr>
			<div style='padding:0 20px;'>
				<h3>Available Exams</h3>
				<hr>
				<div id='exam-list'>" . getExam() . "</div>
			</div>
		</div>
	";
} else {
	header("Location: logout.php?invalid_login");
}

readfile("assets/foot.html");
exit;

function getExam() {
	$dbhost = "classdb.it.mtu.edu";
	$dbuser = "cs3425gr";
	$dbpass = "cs3425gr";
	$db		= "sameluch";

	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$q = "SELECT * FROM exam";
	$result = $conn->query($q);
	$output = "";
	$horbar = "<tr><td><hr></td><td><hr></td><td><hr></td><td><hr></td></tr>";
	
	if($result->num_rows > 0) {
		$output .= "<table id='exams'>";
		$output .= "<tr><th>Exam</th><th>Points</th><th>Date</th></tr>";
		$output .= $horbar;
		while($row = $result->fetch_assoc()) {
			$output .= "<tr>";
			$output .= "<td><form action='exam.php' method='post'>
							<input type='submit' name='exam_name' value='$row[name]' class='btn-link'>
						</form></td>
						<td>$row[total_points]</td>
						<td>$row[date]</td>";
			$output .= "</tr>";
			$output .= $horbar;
		}
		$output .= "</table>";
	} else {
		$output = "There are no exams.";
	}

	mysqli_close($conn);
	return $output;
}

?>