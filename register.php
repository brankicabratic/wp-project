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
	
	<title>Register</title>

    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/profile.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">

</head>
	<body>
	<?php printHeader(); ?>
    <main>
		<h1>Dobrodosli!</h1>		
			<h2>Registracija</h2>
				<form method="post">
					Ime: <br><input type="text" name="firstName"><br>
					Prezime: <br><input type="text" name="lastName"><br>
					Korisničko ime: <br><input type="text" name="username"><br>
					Email: <br><input type="text" name="email"><br>
					Lozinka: <br><input type="password" name="password1"><br>
					Ponovi lozinku: <br><input type="password" name="password2"><br>
					Smer:<br><select name="major"><br>
						<option value="it">Informacione tehnologije</option>
						<option value="matematika">Matematika</option>
					</select><br><br>
					Godina upisa:<br><input type="date" name="startyear"><br>
					Grad: <br><input type="text" name="city"><br>
					Adresa: <br><input type="text" name="address"><br>
					Datum rođenja: <br><input type="date" name="birthday"><br>
					Pol: <br><input type="radio" name="gender" value="m" checked> M
					<input type="radio" name="gender" value="z"> Ž <br> 

					<input type="submit" value="Registruj se" name="register">
				</form>
				<br>
				<br>
				<br>

				<?php

				if(count($errors)!=0)
				{
					foreach ($errors as $error)
					 {
						echo "$error<br>";
					}
				}
				  ?>
	</main>
	</body>
</html>