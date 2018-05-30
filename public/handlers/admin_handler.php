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

	function banUser($authorID) {
		$db = new Database;
		$id = $db->getUser($authorID);
		$success = $db->updateRank($id[COL_USER_ID], 2);
		return $success ? ADMIN_HANDLER_OK : ADMIN_HANDLER_ERROR;
	}

	function unbanUser($authorID) {
		$db = new Database;
		$id = $db->getUser($authorID);
        $success = $db->updateRank($id[COL_USER_ID], 1);
        return $success ? ADMIN_HANDLER_OK : ADMIN_HANDLER_ERROR;
	}
?>