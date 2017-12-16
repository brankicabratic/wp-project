<!DOCTYPE html>
<html>
<head>
		<title>Register</title>
</head>
	<body>
		<h1>Registracija</h1>
		<div id="sadrzaj">
			<div id="poruke">
				<?php				
					foreach($messages as $msg){
						echo "<div>$msg</div>";
					}
				?>
			</div>
			<div id="greske">
				<?php				
					foreach($errors as $err){
						echo "<div>$err</div>";
					}
				?>
			</div>		
			<div class="kontejner">
				<h2>Registracija</h2>
				<form action="register.php" method="post">
					Ime: <br><input type="text" name="firstName"><br>
					Prezime: <br><input type="text" name="lastName"><br>
					Korisničko ime: <br><input type="text" name="username"><br>
					Lozinka: <br><input type="password" name="password1"><br>
					Ponovi lozinku: <br><input type="password" name="password2"><br>
					Grad: <br><input type="text" name="city"><br>
					Država: <br><input type="text" name="country"><br>
					Adresa: <br><input type="text" name="address"><br>
					Email: <br><input type="text" name="email"><br>
					Datum rođenja (DD-MM-YYYY): <br><input type="date" name="birthday"><br>
					Pol: <br><input type="radio" name="gender" value="m" checked> M
					<input type="radio" name="gender" value="z"> Ž <br> 

					<input type="submit" value="Registruj se" name="register">
				</form>
			</div>
		</div>
		<table>
			<tr>
				<th colspan="8" style="text-align:center;font-size:28px;">
					Registrovani korisnici
				</th>
			</tr>
			<tr>
				<th>Ime</th>
				<th>Prezime</th>	
				<th>Datum rođenja</th>	
				<th>Adresa</th>	
				<th>Grad</th>
				<th>Država</th>	
				<th>Poštanski broj</th>
				<th>Pol</th>	
			</tr>	
			<?php
				$korisnici = getUsers();
				foreach ($korisnici as $k) {
					echo "<tr>";
					echo "<td>".$k[COL_FIRST_NAME]."</td>";
					echo "<td>".$k[COL_LAST_NAME]."</td>";
					echo "<td>".$k[COL_BIRTHDAY]."</td>";
					echo "<td>".$k[COL_ADDRESS]."</td>";
					echo "<td>".$k[COL_CITY]."</td>";
					echo "<td>".$k[COL_COUNTRY]."</td>";
					echo "<td>".$k[COL_POSTCODE]."</td>";
					echo "<td>".$k[COL_GENDER]."</td>";					
					echo "</tr>";
				}
			?>
		</table>
	</body>
</html>