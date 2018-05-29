<?php
	require_once 'db_utils.php';

	define("ADMIN_HANDLER_OK", 0);
  define("ADMIN_HANDLER_ERROR", 1);

	function deleteAnswer($id) {
		$db = new Database;
		//TODO:
	//	$success = $db->
	//	return $success ? ADMIN_HANDLER_OK : ADMIN_HANDLER_ERROR;
	}

	function deleteQuestion($id) {
		$db = new Database;
		//TODO:
	//	$success = $db->
	//	return $success ? ADMIN_HANDLER_OK : ADMIN_HANDLER_ERROR;
	}

	function banUser($name) {
		$db = new Database;
		//TODO:
	//	$success = $db->
	//	return $success ? ADMIN_HANDLER_OK : ADMIN_HANDLER_ERROR;
	}

	function unbanUser($name) {
		$db = new Database;
		//TODO:
	//	$success = $db->
	//	return $success ? ADMIN_HANDLER_OK : ADMIN_HANDLER_ERROR;
	}
?>