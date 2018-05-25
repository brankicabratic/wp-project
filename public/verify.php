<?php 
  require_once 'db_utils.php';
  require_once 'parts.php';
	require_once 'handlers/user_handler.php';
?>

<html lang="en">
<head>
    <?php printIncludes('Pitanje') ?>
  </head>
  <body>
  

    <div class="container main-container">
      <?php includeNavigation() ?>
      <div class="row">
      <?php 
        if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
          $email = htmlspecialchars($_GET['email']);
          $hash = htmlspecialchars($_GET['hash']);
          $match = getUserEmailAndHash($email, $hash);

          $db = new Database;
          $msg;

          if ($match) {
            $db->activateUser($email);
            $msg = '<div class="col-6 offset-md-3 alert alert-success" role="alert">
                    <strong>Well done!</strong> You successfully activated account.
                  </div>';
            echo $msg;
          } else {
            $msg = '<div class="col-6 offset-md-3 alert alert-danger" role="alert">
                    <strong>Oh snap!</strong> Something went wrong.
                  </div>';
            echo $msg;
          }
        }
      ?>
      </div>
      <?php includeFooter() ?>
    </div>
</body>
</html>