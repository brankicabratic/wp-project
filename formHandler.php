<?php
	require_once 'handlers/user_handler.php';

	$errors = array();

	if(!isset($_POST["formType"]))
		exit(json_encode(null));

	switch($_POST["formType"]) {
		case "biography":
			// CHANGING PROFILE INFO GOES HERE
			break;
		case "password":
			// CHANGING PASSWORD GOES HERE
			break;
		case "registrationForm":
			if (!isset($_POST["name"]) || empty($_POST["name"])) {
				$errors[] = "Morate uneti korisničko ime";
			}
			if (!isset($_POST["email"]) || empty($_POST["email"])) {
				$errors[] = "Morate uneti mejl adresu";
			}
			if (!isset($_POST["password"]) || empty($_POST["password"])) {
				$errors[] = "Morate uneti lozinku";
			}
			if (count($errors) == 0) {
				if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
					$errors[] = "Neispravna mejl adresa";
				}
				if (!isset($_POST["password2"]) || ($_POST["password"] != $_POST["password2"])) {
					$errors[] = "Unete šifre se ne slažu";
				}
			}
			if (count($errors) == 0) {
				$success = createUser($_POST["name"], $_POST["password"], $_POST["email"]);
				if (!$success) {
					$errors[] = "Došlo je do greške pri registraciji. Pokušajte sa drugim korisničkim imenom. Ukoliko to ne uspe, kontaktirajte administratore.";
				}
			}
			break;
		case "askQuestion":
			$errors[] = "Neka greska pri unosu!";
			$errors[] = "Nevalidni tagovi";
			$errors[] = $_POST["tags"];
			break;
		default:
			exit(json_encode(null));
	}

	echo json_encode($errors);
?>
