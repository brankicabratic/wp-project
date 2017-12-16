<?php
    session_start();
    include "parts.php";
    $errors = array();
    $messages = array();
    if (isset($_POST["login"])) {
	    $username = $_POST ["username"];
	    $password = $_POST ["password"];
	  //$result = checkLogin($username, $password); //Provera unesenih podataka.
	if ($result) {
		header("Location: profile.php?user=$username");
		$_SESSION["user"] = $result;
	}
	else {
		$errors[] = "Neispravno korisničko ime ili lozinka."; //U suprotnom, ne valja(logicno).
	}
	$main_user = false;
	if (isset($_POST["loginButton"])) {
		$main_user = getUser($_POST["username"], $_POST["password"]);
		    if (!$main_user) {
			    header( "Location: login.php?login-fail" );
	    }   else {
			    $_SESSION["user"] = $main_user;
			if ($_POST["remember-me"]) {
				setcookie("username", $main_user[COL_USER_USERNAME], time()+60*60*24*365);
			}
			header( "Location: profile.php" );
		}
	}
?>
<html>
    <head>
        <div>
	        <meta charset="utf-8" /> 
            <link rel="stylesheet" type="text/css" href="css/style.css"> 
            <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
        <div> 
    </head>
    <body>
          <?php printHeader(); ?>
          <main>
            <?php echo date('Y-m-d');?>
            <h1>Dobrodosli na login stranicu</h1> <!--Formica za login-->
	        <h2>Uloguj se</h2>
	        <div>
	            <form action="login.php" method="post">
	            Korisničko ime: <br><input type="text" name="username"><br>
	            Lozinka: <br><input type="password" name="password"><br>
	            Zapamti me: <br><input type="checkbox" name="remember-me" checked> Zapamti Moj Username<br>
	            <input type="submit" value="Uloguj se" name="login">
	            </form>
	        <div>
	      </main>
    </body>
</html>