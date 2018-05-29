<?php
	require_once 'db_utils.php';

	define("ADMIN_HANDLER_OK", 0);
    define("ADMIN_HANDLER_ERROR", 1);

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

	function banUser($authorID) {
		$db = new Database;
		$success = $db->updateRank($authorID, 2);
		return $success ? ADMIN_HANDLER_OK : ADMIN_HANDLER_ERROR;
	}

	function unbanUser($authorID) {
		$db = new Database;
        $success = $db->updateRank($authorID, 1);
        return $success ? ADMIN_HANDLER_OK : ADMIN_HANDLER_ERROR;
	}
?>