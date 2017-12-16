<?php
    session_start();
	include "parts.php";
	
    if (isset($_POST["login"])) {
	    $username = $_POST ["username"];
	    $password = $_POST ["password"];
	  //$result = checkLogin($username, $password); //Provera unesenih podataka.
	}
?>
<html>
    <head>
		<?php printIncludes("Logovanje"); ?>
		<link rel="stylesheet" type="text/css" href="css/login.css"></link>
    </head>
    <body>
          <?php printHeader(); ?>
          <main>
	        <h2 class="center">Uloguj se</h2>
			<form action="login.php" method="post">
				<fieldset>
					<div class="form-group">
						<label>Korisničko ime:</label> 
						<input type="text" name="username">
					</div>
					<div class="form-group">
						<label>Lozinka:</label>
						<input type="password" name="password">
					</div>
					<div class="form-group check">
						<input type="checkbox" name="remember-me" checked> 
						<label>Zapamti Moj Username<label> 
					</div>
				</fieldset>
				<input type="submit" value="Uloguj se" name="login" />
			</form>
	      </main>
    </body>
</html>