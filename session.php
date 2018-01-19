<?php
	session_start();
	require_once 'database/db_utils_dev.php';

	$_SESSION["userID"] = 595383190;

	$user = null;

	if(isset($_SESSION["userID"])) {
			$db = new Database;
			try {
				$user = $db->getUser($db->getUserUsername($_SESSION["userID"]), USER_GETTER_LOGIN_DATA);
			}
			catch(Exception $e) {}
	}
?>
