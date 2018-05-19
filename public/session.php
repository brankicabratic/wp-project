<?php
	require_once 'db_utils.php';

  define("SESSION_USER_ID", "user_id");

  function logout() {
    unset($_SESSION[SESSION_USER_ID]);
  }

	session_start();
  
  $user = null;

	if(isset($_SESSION[SESSION_USER_ID])) {
		$db = new Database;
		try {
			$user = $db->getUserByID($_SESSION[SESSION_USER_ID]);
		}
		catch(Exception $e) {}
	}
?>