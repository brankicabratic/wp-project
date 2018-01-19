<?php
	require_once 'handlers/user_handler.php';

	session_start();

	$result = array(
		"errors" => array(),
		"data" => array()
	);

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
				$result["errors"][] = "Morate uneti korisničko ime";
			}
			if (!isset($_POST["email"]) || empty($_POST["email"])) {
				$result["errors"][] = "Morate uneti mejl adresu";
			}
			if (!isset($_POST["password"]) || empty($_POST["password"])) {
				$result["errors"][] = "Morate uneti lozinku";
			}
			if (count($result["errors"]) == 0) {
				if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
					$result["errors"][] = "Neispravna mejl adresa";
				}
				if (!isset($_POST["password2"]) || ($_POST["password"] != $_POST["password2"])) {
					$result["errors"][] = "Unete šifre se ne slažu";
				}
			}
			if (count($result["errors"]) == 0) {
				$success = createUser($_POST["name"], $_POST["password"], $_POST["email"]);
				if (!$success) {
					$result["errors"][] = "Došlo je do greške pri registraciji. Pokušajte sa drugim korisničkim imenom. Ukoliko to ne uspe, kontaktirajte administratore.";
				}
			}
			break;
		case "askQuestion":
			$result["errors"][] = "Neka greska pri unosu!";
			$result["errors"][] = "Nevalidni tagovi";
			$result["errors"][] = $_POST["tags"];
			break;
		case "loginForm":
			$result["errors"][] = "Pogresna sifra!";
			break;
		default:
			exit(json_encode(null));
	}

	echo json_encode($result);
?>
