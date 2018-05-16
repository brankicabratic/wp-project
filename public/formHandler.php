<?php
	require_once 'session.php';
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
			if (!isset($_SESSION["username"])) {
				$result["errors"][]="Morate biti ulogovani.";
			}
			$success = checkPassword($_SESSION["username"], $_POST["authenticationPassword"]);
			if($success==USER_HANDLER_INVALID_PASSWORD){
				$result["errors"][] = "Neispravna lozinka.";
			}

			if(count($result["errors"])==0) {
				$success = updateProfile($_SESSION["username"], $_POST["firstName"], $_POST["lastName"], $_POST["major"], $_POST["enrollmentYear"], $_POST["email"], $_POST["sex"], $_POST["dateOfBirth"], $_POST["biography"]);
				if(!$success){
					$result["errors"][] = "Došlo je do greške pri podesavanje profila. Pokušajte ponovo. Ukoliko to ne uspe, kontaktirajte administratore.";
				}
			}
			break;
		case "password":
			if (!isset($_POST["current-password"]) || !isset($_POST["new-password"]) || !isset($_POST["new-password-repeated"]) || empty($_POST["current-password"]) || empty($_POST["new-password"]) || empty($_POST["new-password-repeated"])){
				$result["errors"][] = "Morate popuniti sva polja.";
			}
			if ($_POST["new-password"] != $_POST["new-password-repeated"]) {
				$result["errors"][] = "Unete lozinke nisu jednake.";
			}
			
			if (count($result["errors"]) == 0) {
				$success = checkPassword($user[COL_USER_USERNAME], $_POST["current-password"]);
				if ($success==USER_HANDLER_INVALID_PASSWORD){
					$result["errors"][] = "Trenutna lozinka nije odgovarajuća.";
				} else {
					$success = updatePassword($user[COL_USER_USERNAME], $_POST["new-password"]);
					if ($success==USER_HANDLER_INVALID_PASSWORD) {
						$result["errors"][] = "Došlo je do greške pri promeni lozinke. Pokušajte ponovo. Ukoliko to ne uspe, kontaktirajte administratore.";
					}
				}
			}
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
			if (!$user) {
				$result["errors"][] = "Morate biti prijavljeni.";
			}	
			if (!isset($_POST["naslov"]) || empty($_POST["naslov"])) {
				$result["errors"][] = "Morate uneti naslov.";
			}
			if (!isset($_POST["sadrzaj"]) || empty($_POST["sadrzaj"])) {
				$result["errors"][] = "Morate uneti sadržaj.";
			}
			
			if (count($result["errors"]) == 0) {
				$success = insertQuestion($user[COL_USER_USERNAME], $_POST["naslov"], $_POST["sadrzaj"]);
				
				if ($success) {
					$result["succ"][] = "Uspešno uneto pitanje.";
					$result["succ"][] = $success;
				}
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
				} else {
					if(isset($_POST["remember-me"]))
						setcookie("remembered_username", $_POST["username"], time() + COOKIE_EXP_TIME);
					else
						setcookie("remembered_username", "");
				}
      		}
			break;
		case "avatar":
			if(isset($_POST["saveAvatarChanges"])){
				if (!isset($_SESSION["username"])) {
					$result["errors"][]="Morate biti ulogovani.";
				}
				if (isset( $_FILES["photo"]) and $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
					if ($_FILES["photo"]["type"] != "image/jpeg" && $_FILES["photo"]["type"] != "image/jpg" && $_FILES["photo"]["type"] != "image/png" && $_FILES["photo"]["type"] != "image/gif") {
						$result["errors"][]="Slika za avatar mora da bude formata: .jpeg, .jpg, .png ili .gif .";
					}elseif(!move_uploaded_file( $_FILES["photo"]["tmp_name"], "img/" . basename($_FILES["photo"]["name"]))) {
				        $result["errors"][]="Došlo je do greške tokom uploada vašeg slike." . $_FILES["photo"]["error"];
					    }
				}else{
				    switch($_FILES["photo"]["error"]) {
				      case UPLOAD_ERR_INI_SIZE:
				        $message = "Slika je prevelika.";
				        break;
				      case UPLOAD_ERR_FORM_SIZE:
				        $message = "Slika je prevelika.";
				        break;
				      case UPLOAD_ERR_NO_FILE:
				        $message = "Slika nije uploadovan. Budite sigurni da ste izabrali fajl za upload.";
				        break;
				      default:
				        $message = "Potražite administratora za pomoć.";
				    }
					echo "Izvinite, došlo je do greške tokom uploada. $message";
				}
			}
			if(count($result["errors"][] == 0)){
				$newAvatar = "img/" . basename($_FILES["photo"]["name"]);
				$success = updateAvatar($user[COL_USER_USERNAME], $newAvatar);
			}
			break;
		default:
			exit(json_encode(null));
	}
	echo json_encode($result);
?>