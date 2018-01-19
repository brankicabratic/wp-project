<?php
	require_once 'db_utils.php';

	function createUser($username, $password, $email) {
		$db = new Database;
		$success = $db->createUser($username, password_hash($password, PASSWORD_DEFAULT), $email);
		if (!$success) return false;


		return true;
	}
?>
