<?php
readfile("assets/head.html");
session_start();
header('Cache-Control: max-age=900');

if (isset($_SESSION['user']) && isset($_SESSION['id']) && isset($_POST['exam_name'])) {
	$user = $_SESSION['user'];
	$id = $_SESSION['id'];
	$exname = $_POST['exam_name'];
	
	readfile("assets/sidebar.html");
	
	echo "<div class='col-xs-11' id='dash'>
			" . start($exname) . "
		</div>
	";
} else {
	header("Location: dashboard.php");
}

readfile("assets/foot.html");
exit;

function start($exname) {
	$dbhost = "classdb.it.mtu.edu";
	$dbuser = "cs3425gr";
	$dbpass = "cs3425gr";
	$db		= "sameluch";

	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

	if($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$q = "SELECT * FROM grade WHERE s_id = $_SESSION[id] && ex_name = '$exname'";
	$result = $conn->query($q);
	$output = "";
	
	if ($result->num_rows > 0) {
		$output = showExam($conn, $exname);
	} else {
		$output = takeExam($conn, $exname);
	}
	
	mysqli_close($conn);
	return $output;
}

function takeExam($conn, $exname) {
	$out = "<h2>$exname</h2><hr>
		<form name='exam' onSubmit='return validate()' action='grader.php' method='POST'>";
	
	$question = $conn->query("SELECT * FROM question WHERE ex_name = '$exname'");
	
	while ($q_row = $question->fetch_assoc()) {
		$out .= "<div class='question'>
				<h4>$q_row[text]</h4>";
				
		$choice = $conn->query("SELECT * FROM choice WHERE ex_name = '$exname' AND q_num = '$q_row[number]'");
		while ($c_row = $choice->fetch_assoc()) {
			$out .= "<input type='radio' name='$q_row[number]' value='$c_row[id]'> $c_row[text]<br>";
		}
		
		$out .= "</div>";
	}
	
	$out .= "<input type='submit' value='Submit'><input type='hidden' name='exam_name' value='$exname'></form>";
	
	$out .= "
		<script>
			function validate() {
				var questions = document.exam.elements;
				var answers = '';
				var comma = '';
				var old = '';
				var count = 0;
				
				for (var i = 0, q; q = questions[i++];) {
					if(q.type === 'radio' && q.name != old) {
						old = q.name;
						count += 2;
					}
					if(q.type === 'radio' && q.checked) {
						answers += comma + q.value;
						comma = ',';
					}
				}
				
				if (answers.length != count - 1) {
					alert('Not all questions have been answered!');
					return false;
				} else
					return true;
			}
		</script>
	";
	
	return $out;
}

function showExam($conn, $exname) {
	$out = "<h2>$exname</h2>";

	$response = array();
	$answers = $conn->query("SELECT * FROM grade WHERE s_id = '$_SESSION[id]' AND ex_name = '$exname'");
	while ($row = $answers->fetch_assoc()) {
		$out .= "<h3>Your score: $row[total]";
		for ($i = 0; $i < strlen($row['correct_q']); $i += 2) {
			$str = $row['correct_q'];
			array_push($response, $str{$i});
		}
	}

	$result = $conn->query("SELECT total_points FROM exam WHERE name = '$exname'");
	while ($row = $result->fetch_assoc()) {
		$out .= " / $row[total_points]</h3><hr>";
	}
	
	$question = $conn->query("SELECT * FROM question WHERE ex_name = '$exname'");
	while ($q_row = $question->fetch_assoc()) {
		$out .= "<div class='question'>
				<h4>$q_row[text]</h4>";
		$num = $q_row['number'] - 1;
		$out .= "Your answer: $response[$num]<br>";
		$out .= "Correct answer: $q_row[correct]<br>";
		if ($response[$num] == $q_row['correct']) {
			$out .= "<br>Points earned: $q_row[num_points]<br>";
		} else {
			$out .= "<br>Points earned: 0<br>";
		}
		$out .= "</div>";
	}

	return $out;
}

?>