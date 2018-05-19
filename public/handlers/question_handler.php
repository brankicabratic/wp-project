<?php
	require_once 'db_utils.php';

  define("QUESTION_HANDLER_OK", 1);
  define("QUESTION_HANDLER_INVALID", 2);

  function insertQuestion($author, $header, $content, $category) {
    $db = new Database;
    $success = $db->insertQuestion($author, $header, $content, $category);
    if (!$success) {
      return QUESTION_HANDLER_INVALID;
    } 
    return $success;
  }

  function insertTag($name) {
    $db = new Database;
    return $db->insertTag($name);
  }
?>
