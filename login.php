<?php
	$main_user = false;
	if (isset($_POST["loginButton"])) {
		$main_user = getUser($_POST["username"], $_POST["password"]);
		if (!$main_user) {
			header( "Location: login.php?login-fail" );
		} else {
			$_SESSION["user"] = $main_user;
			if ($_POST["remember-me"]) {
				setcookie("username", $main_user[COL_USER_USERNAME], time()+60*60*24*365);
			}
			header( "Location: profile.php" );
		}
	}
?>
<html>
<header>Login</header>
<body>
	<h2>Uloguj se</h2>
	  <form method="post" action="profile.php">
		<label for="username">Korisničko ime:</label> 
		<input type="text" name="username" value="<?php echo isset($_COOKIE["username"]) ? $_COOKIE["username"] : "";?>"><br>

		<label for="password">Lozinka:</label>
		<input	type="password" name="password"><br>
					
		<input type="checkbox" name="remember-me" checked> Zapamti moj username<br> 
		<a href="?forget-me">Forget me</a>

		<input type="submit" name="loginButton" value="Uloguj se">
	  </form>
</body>
</html>