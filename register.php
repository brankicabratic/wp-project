<?php 
session_start();

include "parts.php";

$errors=array();

if(isset($_POST['register']))
{
	$name=htmlspecialchars($_POST['firstName']);
	$lastname=htmlspecialchars($_POST['lastName']);
	$username=htmlspecialchars($_POST['username']);
	$email=htmlspecialchars($_POST['email']);
	$password1=htmlspecialchars($_POST['password1']);
	$password2=htmlspecialchars($_POST['password2']);
	$city=htmlspecialchars($_POST['city']);
	$major=htmlspecialchars($_POST['major']);
	$startyear=htmlspecialchars($_POST['startyear']);
	$address=htmlspecialchars($_POST['address']);
	$date=htmlspecialchars($_POST['birthday']);
	$gender=htmlspecialchars($_POST['gender']);
}
//proverava email
if(!(filter_var($email, FILTER_VALIDATE_EMAIL)))
{
	$errors[]="Niste uneli pravilan email";
}
//proverava passworde
if($password1!=$password2)
{
	$errors[]="Niste uneli istu sifru";
}

// da li korisnicko ime ima vise od 20 karaktera
if(strlen($username)>20)
{
	$errors[]="Korisnicko ime mora sadrzati manje od 20 karaktera";
}

//U imenu ne smeju da stoje brojevi
if(strpbrk($name, '1234567890')!= FALSE)
{
	$errors[]="Ime ne sme da sadrzi brojeve";
}

//Prezime ne sme da sadrzi brojeve
if(strpbrk($lastname, '1234567890')!= FALSE)
{
	$errors[]="Prezime ne sme da sadrzi brojeve";
}



//Registracija uspesna
if(count($errors)==0)
{
	$success;
}


?>



<!DOCTYPE html>
<html>
<head>
	
	<?php printIncludes("Registracija"); ?>
	<link rel="stylesheet" type="text/css" href="css/login.css"></link>
</head>
	<body>
	<?php printHeader(); ?>
    <main>	
		<h2 class="center">Registracija</h2>
		<form method="post">
			<fieldset>
				<div class="form-group">
					<label>Ime:</label> <input type="text" name="firstName">
				</div>
				<div class="form-group">
				<label>Prezime:</label> <input type="text" name="lastName">
				</div>
				<div class="form-group">
				<label>Korisničko ime:</label> <input type="text" name="username">
				</div>
				<div class="form-group">
				<label>Email:</label> <input type="text" name="email">
				</div>
				<div class="form-group">
				<label>Lozinka:</label> <input type="password" name="password1">
				</div>
				<div class="form-group">
				<label>Ponovi lozinku:</label> <input type="password" name="password2">
				</div>
				<div class="form-group">
				<label>Smer:</label><select name="major">
						<option value="it">Informacione tehnologije</option>
						<option value="matematika">Matematika</option>
					</select>
				</div>
				<div class="form-group">						
				<label>Godina upisa:</label><input type="date" name="startyear">
				</div>
				<div class="form-group">						
				<label>Grad:</label> <input type="text" name="city">
				</div>
				<div class="form-group">						
				<label>Adresa:</label> <input type="text" name="address">
				</div>
				<div class="form-group">						
				<label>Datum rođenja:</label> <input type="date" name="birthday">
				</div>
				<div class="form-group">						
					<label>Pol:</label> 
					<div class="radios">
						<input type="radio" name="gender" value="m" checked> M
						<input type="radio" name="gender" value="z"> Ž 
					</div> 
				</div>
			</fieldset>
			<input type="submit" value="Registruj se" name="register">
		</form>

				<?php

				if(count($errors)!=0)
				{
					foreach ($errors as $error)
					 {
						echo "$error";
					}
				}
				  ?>
	</main>
	</body>
</html>