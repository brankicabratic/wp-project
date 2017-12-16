<!DOCTYPE html>
<html>
<head>
		<title>Socijalna mreža</title>
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/login.css">
	</head>
	<body>	
		<h1>Dobrodošli!</h1>
		<div id="sadrzaj">
			<?php
				if (!empty($messages)) {
					echo "<div class=\"kontejner poruke svetlo\">";
					foreach ($messages as $message) {
						echo "<div>$message</div>";
					}
					echo "</div><br>";
				}
			?>

			<div class="kontejner login svetlo">
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
			</div>
			
			<div class="kontejner registracija svetlo">
				<h2>Registruj se</h2>
				<p>* Obavezno polje.</p>
				<form method="post" action="">
					<label for="name" class="obavezno-polje">Ime i prezime:</label>
					<?php outputError("name");?>
					<input type="text" name="name" value="<?php echo $name;?>"><br>
	  				
					<label for="username" class="obavezno-polje">Korisničko ime:</label>
					<?php outputError("username");?>
					<input type="text" name="username" value="<?php echo $username;?>"><br>
	  				
					<label for="profession" class="obavezno-polje">Profesija:</label>
					<?php outputError("profession");?>
					<input type="text" name="profession" value="<?php echo $profession;?>"><br>
	  				
					<label for="address" class="obavezno-polje">Adresa:</label>
					<?php outputError("address");?>
					<input type="text" name="address" value="<?php echo $address;?>"><br>
	  				
					<label for="password1" class="obavezno-polje">Lozinka:</label>
					<?php outputError("password1");?>
					<input type="password" name="password1" value="<?php echo $password1;?>"><br>
	  				
					<label for="password2" class="obavezno-polje">Ponovi lozinku:</label>
					<?php outputError("password2");?>
					<?php outputError("poklapanjeLozinki");?>
					<input type="password" name="password2" value="<?php echo $password2;?>"><br>
	  				
					<label for="date" class="obavezno-polje">Datum rođenja:</label>
					<?php outputError("birthday");?>
					<input type="date" name="birthday" value="<?php echo $birthday;?>"><br>
	  				
					<label for="pol">Pol:</label> <br>
					<?php outputError("gender");?>
					<input type="radio" name="pol" value="m" <?php if ($gender == "m") echo 'checked'; ?>> M 
					<input type="radio" name="pol" value="z" <?php if ($gender == "z") echo 'checked'; ?>> Ž <br> 
					
					<input type="checkbox" name="novosti" checked> Želim da dobijam novosti<br> 
					<input type="submit" name="registerButton" value="Registruj se">
				</form>
			</div>
		</div>
	</body>
</html>