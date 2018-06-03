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
      $db->updateOnlineTime($username);
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
      $_SESSION["message"] = "<div class=\"col-8 offset-md-2 alert alert-success\" role=\"alert\"><center>Uspešno ste se registrovali!<br>Na unetu email adresu Vam je poslat link za verifikaciju naloga. <br>Ako ne verifikujete Vaš nalog nećete moći postavljati pitanja i odgovore.</center></div>";
              
      $to = $email;
      $subject = 'Signup | Verification';
      $message = '
        
        Thanks for signing up!
        Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
        
        ------------------------
        Username: '.$username.'
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

  function getAllEmail($email){
    $db = new Database;
    return  $success = $db->getAllEmail($email);
  }

  function getTopActiveUsers() {
    $db = new Database;
    $success = $db->getTopActiveUsers();
    $result = array();
    foreach($success as $user) {
      $name = $db->getUsernameById($user["Author"])[0]["Username"];
      $result[] = array("username" => $name, "count_msg" => $user["count_msg"]);
    }
    return $result;
  }

  function getPopularCategory() {
    $db = new Database;
    $success = $db->getPopularCategory();
    $result = array();
    foreach($success as $category) {
      $name = $db->getCategoryNameById($category["CategoryID"])[0]["Name"];
      $result[] = array("CategoryID" => $category["CategoryID"], "name" => $name, "count_cat" => $category["count_cat"]);
    }
    return $result;
  }

  function resetPassword($email, $password) {
    $db = new Database;
    $success = $db->resetPassword($email, password_hash($password, PASSWORD_DEFAULT));
    if($success) {
      return USER_HANDLER_OK;
    }
    return USER_HANDLER_INVALID_PASSWORD;
  }

  function emailResetPassword($email) { 
    $to = $email;
    $subject = 'PMFOverflow promena lozinke';
    $message = '
      
      Poštovani,
       
      Primili smo zahtev za promenu Vaše PMFOverflow lozinke.
      
      ------------------------
      
      Kliknite na link ispod za promenu lozinke.
      http://localhost/wp-project/public/resetPassword.php?email='.$email.' ';

    $headers = 'From:noreply@PMFOverflow.com'. "\r\n";
    $mailSuccessfullySent = mail($to, $subject, $message, $headers);
    if ($mailSuccessfullySent) {
        return USER_HANDLER_OK;
    }
    return USER_HANDLER_INVALID;
  }

  function deleteUser($userID){
    $db = new Database;
    $moveSuccess = $db->movePost($userID);
    if ($moveSuccess) {
      $success = $db->deleteUser($userID);
      if ($success){
        return USER_HANDLER_OK;
      }
    }
    return USER_HANDLER_INVALID;
  }
  
?>