<?php
	require_once 'db_utils.php';

  define("QUESTION_HANDLER_OK", 1);
  define("QUESTION_HANDLER_INVALID", 2);

  function insertQuestion($author, $header, $content, $category, $tags) {
    $db = new Database;
    $success = $db->insertQuestion($author, $header, $content, $category, $tags);
    if (!$success) {
      return QUESTION_HANDLER_INVALID;
    } 
    return $success;
  }

  function insertTag($name) {
    $db = new Database;
    return $db->insertTag($name);
  }

  function getPostsAuthor($questionID) {
    $db = new Database;
    $success = $db->getPostsAuthor($questionID);
    if (!$success) {
      return QUESTION_HANDLER_INVALID;
    } 
    return $success;
  }

  function getQuestion($questionId) {
    $db = new Database;
    $success = $db->getQuestion($questionId);
    if (!$success) {
      return QUESTION_HANDLER_INVALID;
    } 
    return $success;
  }

  function getUserByID($authorId) {
    $db = new Database;
    $success = $db->getUserByID($authorId);
    if (!$success) {
      return QUESTION_HANDLER_INVALID;
    } 
    return $success;
  }
?>
