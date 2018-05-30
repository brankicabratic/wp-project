<?php
	require_once 'db_utils.php';
	require_once 'col_config.php';

	define("ANSWER_HANDLER_OK", 1);
    define("ANSWER_HANDLER_ERROR", 0);

	function insertAnswer($author, $answerContent, $questionId) {
		$db = new Database;
		$success = $db->insertAnswer($author, $answerContent, $questionId);
		return $success ? ANSWER_HANDLER_OK : ANSWER_HANDLER_ERROR;
	}
?>