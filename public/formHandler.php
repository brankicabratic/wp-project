<?php
	require_once 'handlers/user_handler.php';
	require_once 'handlers/question_handler.php';

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
			if (!isset($_SESSION["userID"])) {
				$result["errors"][] = "Morate biti prijavljeni";
			}	
			if (!isset($_POST["naslov"]) || empty($_POST["naslov"])) {
				$result["errors"][] = "Morate uneti naslov";
			}
			if (!isset($_POST["sadrzaj"]) || empty($_POST["sadrzaj"])) {
				$result["errors"][] = "Morate uneti sadrzaj";
			}
			
			$success = insertQuestion($_COOKIE["remembered_username"], $_POST["naslov"], $_POST["sadrzaj"]);
			
			if ($success == QUESTION_HANDLER_OK) {
				$result["succ"][] = "Uspesno uneto pitanje.";
			}
			if ($success == QUESTION_HANDLER_INVALID) {
				$result["errors"][] = "Neka greska pri unosu!";
			}
			break;
		case "loginForm":
      if (!isset($_POST["username"]) || empty($_POST["username"])) {
        $result["errors"][] = "Morate uneti korisničko ime";
      }
      if (!isset($_POST["password"]) || empty($_POST["password"])) {
        $result["errors"][] = "Morate uneti šifru";
      }
      if (count($result["errors"]) == 0) {
        $code = login($_POST["username"], $_POST["password"]);
        if ($code == USER_HANDLER_INVALID_USERNAME) {
          $result["errors"][] = "Neispravno korisničko ime";
        } else if ($code == USER_HANDLER_INVALID_PASSWORD) {
          $result["errors"][] = "Neispravna šifra";
        }
				else {
					if(isset($_POST["remember-me"]))
						setcookie("remembered_username", $_POST["username"], time() + COOKIE_EXP_TIME);
					else
						setcookie("remembered_username", "");
				}
      }
			break;
		default:
			exit(json_encode(null));
	}

	echo json_encode($result);
?>
