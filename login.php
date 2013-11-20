<?php
require_once('mysql.php');

if (isset($_POST['pid']) && isset($_POST['password'])){
	if($stmt = $mysqli -> prepare("SELECT pid, fname, lname FROM person WHERE pid=? AND passwd=?")){
		$stmt -> bind_param("ss", $_POST['pid'], hash("md5", $_POST['password']));
		$stmt -> execute();
		$stmt -> bind_result($pid, $fname, $lname);
		// $stmt -> fetch();

		if($stmt -> fetch()){
			$_SESSION['pid'] = $pid;
			$_SESSION['fname'] = $fname;
			$_SESSION['lname'] = $lname;
			header('Location: index.php');
		}

		// if(isset($_SESSION['pid']))
		// 	echo "We are logged in";
			//header('Location: index.php');
	}

}

if(isset($_SESSION['pid']))
	echo "We are logged in";
	//header('Location: index.php');
?>
<!DOCTYPE html>
<html>
<?php require_once('header.php'); ?>
<body>
	<link href="signin.css" rel="stylesheet">
	<?php require_once('navbar.php'); ?>
	<div class="container" style="padding-top: 60px;">
		<form action="login.php" method="POST" class="form-signin">
			<h2 class="form-signin-heading">Please sign in</h2>
			<input name ="pid" type="text" class="form-control" placeholder="PID" required autofocus>
			<input name ="password" type="password" class="form-control" placeholder="Password" required>
			<label class="checkbox">
				<input type="checkbox" value="remember-me"> Remember me
			</label>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		</form>
	</div>
	<!--Javascript-->
	<?php //require_once('js.php'); ?>
</body>
</html>