<?php
readfile("assets/head.html");

echo "
	<!-- content -->
	<div class='row'>
		<div class='col-l-12' id='topbar'>
			<h3>Michigan Tech</h3>
		</div>
		
		<div class='col-l-12 logincontent' >
			<form id='login' action='login.php' method='post'>
				<div id='logo'></div>
				
				<input type='text' name='username' placeholder='Username' class='center' autocomplete='off' style='margin-bottom:10px;'/><br>
				
				<input type='password' name='password' placeholder='Password' class='center' style='margin-top:10px;'/><br>
				
				<div class='center' style='position:relative; width:260px;'>
					<input type='submit' value='Login'/>
					<a id='forgot' href='/cs3425'>Forgot your password?</a>
				</div>
				<br><br><br>
				<div id='disclaimer'>
					<a href='/cs3425'>Disclaimer: This is not a real site</a>
				</div>
			</form>
		</div>
	</div>
	<!-- end of content -->
";

readfile("assets/foot.html");
?>