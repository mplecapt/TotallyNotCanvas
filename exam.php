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
		<form name='exam' onSubmit='return grade()' action='exam.php' method='POST'>";
	
	$question = $conn->query("SELECT * FROM question WHERE ex_name = '$exname'");
	
	while ($q_row = $question->fetch_assoc()) {
		$out .= "<div class='question'>
				<h4>$q_row[text]</h4>";
				
		$choice = $conn->query("SELECT * FROM choice WHERE ex_name = '$exname' AND q_num = '$q_row[number]'");
		while ($c_row = $choice->fetch_assoc()) {
			$out .= "<input type='radio' name='$q_row[number]' value='$c_row[id]'> $c_row[text]<br>";
		}
		
		$out .= "<input type='hidden' name='answer' value='$q_row[correct]'>
				<input type='hidden' name='points' value='$q_row[num_points]'></div>";
	}
	
	$out .= "<input type='submit' value='Submit'><input type='hidden' name='exam_name' value='$exname'></form>";
	
	$out .= "
		<script>
			function grade() {
				var answers = getAnswers();
				if (answers == 'false')
					return false;
				var correct = getCorrect();
				var points = getPoints();
				var totalPoints = 0;
				
				for (var i = 0; i < correct.length; i += 2) {
					if (correct.charAt(i) == answers.charAt(i)) {
						totalPoints += parseInt(points.charAt(i));
					}
				}

			}

			function getAnswers() {
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
					return 'false';
				} else
					return answers;
			}

			function getCorrect() {
				var questions = document.exam.elements;
				var correct = '';
				var comma = '';
				
				for (var i = 0, q; q = questions[i++];) {
					if(q.type === 'hidden' && q.name === 'answer') {
						correct += comma + q.value;
						comma = ',';
					}
				}

				return correct;
			}

			function getPoints() {
				var questions = document.exam.elements;
				var points = '';
				var comma = '';
				
				for (var i = 0, q; q = questions[i++];) {
					if(q.type === 'hidden' && q.name === 'points') {
						points += comma + q.value;
						comma = ',';
					}
				}

				return points;
			}
		</script>
	";
	
	return $out;
}

function submit($conn, $exname, $answers, $totalPoints) {
	$result = $conn->query("INSERT INTO grade values ($_SESSION[id], '$exname', '$answers', $totalPoints)");
}

function showExam($conn, $exname) {
	
}

?>