<?php
	require_once 'db_utils.php';
	require_once 'session.php';

  define("USER_HANDLER_OK", 1);
  define("USER_HANDLER_INVALID", 2);
  define("USER_HANDLER_INVALID_USERNAME", 3);
  define("USER_HANDLER_INVALID_PASSWORD", 4);
  define("USER_HANDLER_INVALID_ACTIVATION", 5);

	define('COOKIE_EXP_TIME', 315360000); // 10 years

  function login($username, $password) {
    $db = new Database;
    $user = $db->getUser($username);
    if (!$user) return USER_HANDLER_INVALID_USERNAME;
    $success = password_verify($password, $user[COL_USER_PASSWORD]);
    if ($success) {
      $_SESSION[SESSION_USER_ID] = $user[COL_USER_ID];
      return USER_HANDLER_OK;
    }
    return USER_HANDLER_INVALID_PASSWORD;
  }

	function createUser($username, $password, $email) {
    $db = new Database;
    $hash;
    $userSuccessfullyCreated = $db->createUser($username, password_hash($password, PASSWORD_DEFAULT), $email, $hash);
    if ($userSuccessfullyCreated) {
      login($username, $password);
      $to = $email;
      $subject = 'Signup | Verification';
      $message = '
        
        Thanks for signing up!
        Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
        
        ------------------------
        Username: '.$username.'
        Password: '.$password.'
        ------------------------
        
        Please click this link to activate your account:
        http://localhost/wp-project/public/verify.php?email='.$email.'&hash='.$hash.'
        
      ';

      $headers = 'From:noreply@yourwebsite.com'. "\r\n";
      $mailSuccessfullySent = mail($to, $subject, $message, $headers);
      if (!$mailSuccessfullySent) {
          return USER_HANDLER_INVALID_ACTIVATION;
      }
    } else {
      return USER_HANDLER_INVALID;
    }
    return USER_HANDLER_OK;

	} 

  function updateProfile($user_id, $firstName, $lastName, $major, $enrollmentYear, $email, $sex, $dateOfBirth, $biography){
    $db = new Database;
    $success = $db->updateProfile($user_id, $firstName, $lastName, $major, $enrollmentYear, $email, $sex, $dateOfBirth, $biography);
    if($success){
      return USER_HANDLER_OK;
    }
    return USER_HANDLER_ERROR;
  }

  function updateAvatar($user_id, $newAvatar){
    $db = new Database;
    $success = $db->updateAvatar($user_id, $newAvatar);
    if($success){
      return USER_HANDLER_OK;
    }
    return USER_HANDLER_ERROR;
  }

   function updatePassword($username, $password) {
    $db = new Database;
    $user_id = $db->getUserID($username);
    $success = $db->updatePassword($user_id, password_hash($password, PASSWORD_DEFAULT));
    if($success) {
      return USER_HANDLER_OK;
    }
    return USER_HANDLER_INVALID_PASSWORD;
  }

  function checkPassword($username, $password){
    $db = new Database;
    $user_id = $db->getUserID($username);
    $db_password = $db->getPasswordById($user_id);
    $success = password_verify($password, $db_password);
    if($success) {
      return USER_HANDLER_OK;
    }
    return USER_HANDLER_INVALID_PASSWORD;
  }

  function getUserEmailAndHash($email, $hash) {
    $db = new Database;
    $success = $db->getUserEmailAndHash($email, $hash);
    if ($success) {
      return true;
    }
    return false;
  }

  function activateUser($email) {
    $db = new Database;
    return $success = $db->activateUser($email);
  }

  function getUserRank($id) {
    $db = new Database;
    $success = $db->getUserRank($id);
    return $success;
  }
  
?>