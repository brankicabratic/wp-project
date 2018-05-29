<?php
	require_once 'session.php';
	require_once 'handlers/user_handler.php';
	require_once 'handlers/question_handler.php';
	require_once 'handlers/admin_handler.php';

	$result = array(
		"errors" => array(),
		"data" => array()
	);

	if(!isset($_POST["formType"]))
		exit(json_encode(null));

	switch($_POST["formType"]) {
		case "biography":
			if (!$user) {
				$result["errors"][]="Morate biti ulogovani.";
			}
			$success = checkPassword($user[COL_USER_USERNAME], $_POST["authenticationPassword"]);
			if($success==USER_HANDLER_INVALID_PASSWORD){
				$result["errors"][] = "Neispravna lozinka.";
			}
			if(count($result["errors"])==0) {
				$success = updateProfile($user[COL_USER_ID], $_POST["firstName"], $_POST["lastName"], $_POST["major"], $_POST["enrollmentYear"], $_POST["email"], $_POST["sex"], $_POST["dateOfBirth"], $_POST["biography"]);
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
			if (strlen($_POST["new-password"]) < 6){
				$result["errors"][] = "Dužina lozinke mora biti najmanje 6 karaktera.";
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
				if (strlen($_POST["password"]) < 6){
					$result["errors"][] = "Dužina lozinke mora biti najmanje 6 karaktera.";
				} 
				if (!isset($_POST["password2"]) || ($_POST["password"] != $_POST["password2"])) {
					$result["errors"][] = "Unete šifre se ne slažu";
				}
			}
			if (count($result["errors"]) == 0) {
				$success = createUser($_POST["name"], $_POST["password"], $_POST["email"]);
				if ($success !== USER_HANDLER_OK) {
					$result["errors"][] = "Došlo je do greške pri registraciji. Pokušajte sa drugim korisničkim imenom. Ukoliko to ne uspe, kontaktirajte administratore.";
				}
			}
			break;
		case "askQuestion":
			if (!$user) {
				$result["errors"][] = "Morate biti prijavljeni.";
			}
			if (count($result["errors"]) == 0) {
				$succes = getUserRank($user[COL_USER_ID]);
				if ($succes == 0) {
					$result["errors"][] = "Morate verifikovati Vaš nalog da biste postavljali pitanja.";		
				}
			}
			if (count($result["errors"]) == 0) {	
				if (!isset($_POST["naslov"]) || empty($_POST["naslov"])) {
					$result["errors"][] = "Morate uneti naslov.";
				}
				if (!isset($_POST["sadrzaj"]) || empty($_POST["sadrzaj"])) {
					$result["errors"][] = "Morate uneti sadržaj.";
				}
				$tags = array_merge(explode(",", $_POST["tags"]), preg_split("/[\s,]+/", $_POST["tag"]));
				if (count($tags) > 5) {
					$result["errors"][] = "Imate više od 5 tagova.";
				}
			}
			if (count($result["errors"]) == 0) {
				$success = insertQuestion($user[COL_USER_USERNAME], $_POST["naslov"], $_POST["sadrzaj"], $_POST["category"], $tags);
				
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
        case "answerQuestionForm":
            if ($user) {
				$succesVerification = getUserRank($user[COL_USER_ID]);
				if ($succesVerification != 0) {
	                $author = $user[COL_USER_ID];
	                $answerContent = htmlspecialchars($_POST["answer-content"]);
	                if (isset($answerContent) && !empty($answerContent)) {
						$questionId = $_POST["questionId"];
		                $successfullyInserted = $db->insertAnswer($author, $answerContent, $questionId);
		                if ($successfullyInserted) {
		                    $result["succ"][] = "Uspesno unet odgovor";
		                    // Send email notification
		                    $question = $db->getQuestion($questionId);
		                    $questionAuthorId = $db->getPostsAuthor($question[COL_QUESTION_ID]);
		                    $questionAuthor = $db->getUserByID($questionAuthorId[0]);
		                    $to = $questionAuthor[COL_USER_EMAIL];
		                    $subject = "Postavljen odgovor na pitanje \"".$question[COL_QUESTION_HEADER]."\"";
		                    $txt = "User ".$user[COL_USER_USERNAME]." je odgovorio na postavljeno pitanje.";

		                    $mailSuccessfullySent = mail($to,$subject,$txt);
		                    if (!$mailSuccessfullySent) {
		                        $result["errors"][] = "Došlo je do greške prilikom slanja mail-a";
		                    }
		                } else {
		                    $result["errors"][] = "Došlo je do greške prilikom postavljanja odgovora";
		                }
		            }else{
		            	$result["errors"][] = "Morate uneti sadržaj.";
		            }
	            }else{
	                $result["errors"][] = "Morate verifikovati Vaš nalog da biste postavljali odgovore.";
	            }
            } else {
                $result["errors"][] = "Morate biti ulogovani da biste postavljali odgovore";
            }
            break;
		case "avatar":
      if (!$user) {
				$result["errors"][] = "Morate biti ulogovani.";
			} else if (!isset( $_FILES["photo"]) || $_FILES["photo"]["error"] != UPLOAD_ERR_OK) {
        switch($_FILES["photo"]["error"]) {
          case UPLOAD_ERR_INI_SIZE:
            $message = "Slika je prevelika.";
            break;
          case UPLOAD_ERR_FORM_SIZE:
            $message = "Slika je prevelika.";
            break;
          case UPLOAD_ERR_NO_FILE:
            $message = "Niste okačili fajl.";
            break;
          default:
            $message = "Obratite se administratorima.";
        }
        $result["errors"][] = "Izvinite, došlo je do greške tokom kačenja slike. $message";
      } else {
        $target_file = "img/avatars/" . $user[COL_USER_USERNAME] . "." . pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
        if ($_FILES["photo"]["type"] != "image/jpeg" && $_FILES["photo"]["type"] != "image/jpg" && $_FILES["photo"]["type"] != "image/png" && $_FILES["photo"]["type"] != "image/gif") {
          $result["errors"][] = "Slika za avatar mora da bude formata: .jpeg, .jpg, .png ili .gif .";
        } elseif(!move_uploaded_file( $_FILES["photo"]["tmp_name"], $target_file)) {
          $result["errors"][] = "Došlo je do greške tokom kačenja vaše slike.";
        }

        if(count($result["errors"]) == 0){
          $success = updateAvatar($user[COL_USER_ID], $target_file);
        }
      }
			break;
		case "deleteAnswer":
			if (deleteAnswer($_POST["id"])) {
				$result["data"][] = "Odgovor je uspešno izbrisan.";
			}
			else {
				$result["errors"][] = "Došlo je do greške pri brisanju odgovora.";
			}
			break;
		case "deleteQuestion":
			if (deleteQuestion($_POST["id"])) {
				$result["data"][] = "Pitanje je uspešno izbrisan.";
			}
			else {
				$result["errors"][] = "Došlo je do greške pri brisanju pitanja.";
			}
			break;
		case "banUser":
			if (banUser($_POST["id"])) {
				$result["data"][] = "Korisnik je uspešno banovan.";
			}
			else {
				$result["errors"][] = "Došlo je do greške pri banovanju korinika.";
			}
			break;
		case "unbanUser":
			if (unbanUser($_POST["id"])) {
				$result["data"][] = "Korisnik je uspešno odbanovan.";
			}
			else {
				$result["errors"][] = "Došlo je do greške kod odbanovanja korinika.";
			}
			break;	
		default:
			exit(json_encode(null));
	}
	echo json_encode($result);
?>