<?php
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
			$errors[] = "Korisnik sa ovim mailom vec postoji u bazi";
			$errors[] = "Nesto lepo";
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
