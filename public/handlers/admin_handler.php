<?php
	require_once 'db_utils.php';
	require_once 'col_config.php';

	define("ADMIN_HANDLER_OK", 1);
    define("ADMIN_HANDLER_ERROR", 0);

	function deleteAnswer($id) {
		$db = new Database;
		$success = $db->deleteAnswer($id);
		return $success ? ADMIN_HANDLER_OK : ADMIN_HANDLER_ERROR;
	}

	function deleteQuestion($id) {
		$db = new Database;
		$success = $db->deletePost($id);
		return $success ? ADMIN_HANDLER_OK : ADMIN_HANDLER_ERROR;
	}

	function banUser($username) {
		$db = new Database;
		$user = $db->getUser($username);
		$success = $db->updateRank($user[COL_USER_ID], 2);
		if ($success) {
			$to = $user[COL_USER_EMAIL];
		  $subject = "Ban";
		  $message = "Banovani ste zbog neprikladnog ponašanja. Dok ste banovani ne možete da postavljate pitanja i odgovore. Ako mislite da se greškom banovani, možete da se obratite administratoru.";
		  $headers = "From: noreply@pmfoverflow.com";
		  mail($to, $subject, $message, $headers);
		}
		return $success ? ADMIN_HANDLER_OK : ADMIN_HANDLER_ERROR;
	}

	function unbanUser($username) {
		$db = new Database;
		$user = $db->getUser($username);
    $success = $db->updateRank($user[COL_USER_ID], 1);
    if ($success) {
			$to = $user[COL_USER_EMAIL];
		  $subject = "Ban";
		  $message = "Niste više banovani. Vraćene su vam mogućnosti postavljanja pitanja i odgovora.";
		  $headers = "From: noreply@pmfoverflow.com";
		  mail($to, $subject, $message, $headers);
		}
    return $success ? ADMIN_HANDLER_OK : ADMIN_HANDLER_ERROR;
	}
?>