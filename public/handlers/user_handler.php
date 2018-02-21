<?php
	require_once 'db_utils.php';

  session_start();

  define("USER_HANDLER_OK", 1);
  define("USER_HANDLER_INVALID_USERNAME", 2);
  define("USER_HANDLER_INVALID_PASSWORD", 3);

	define('COOKIE_EXP_TIME', 315360000); // 10 years

  function login($username, $password) {
    $db = new Database;
    $user = $db->getUser($username);
    if (!$user) return USER_HANDLER_INVALID_USERNAME;
    $success = password_verify($password, $user[COL_USER_PASSWORD]);
    if ($success) {
      $_SESSION["userID"] = $user[COL_USER_ID];
      return USER_HANDLER_OK;
    }
    return USER_HANDLER_INVALID_PASSWORD;
  }

	function createUser($username, $password, $email) {
		$db = new Database;
		return $db->createUser($username, password_hash($password, PASSWORD_DEFAULT), $email);
	}
?>
