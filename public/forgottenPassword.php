<?php 
  require_once 'db_utils.php';
  require_once 'parts.php';
	require_once 'handlers/user_handler.php';

  $result = array(
    "errors" => array(),
    "data" => array()
  );

  if (isset($_POST['potvdi'])) {
    $email = $_POST['email'];
    if (!isset($email) || empty($email)) {
        $result["errors"][] = "Morate uneti mail adresu";
    }
    if (count($result["errors"]) == 0) {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
          $result["errors"][] = "Neispravna mail adresa";
        }
        $getAllEmail = getAllEmail($_POST["email"]);
        if ($getAllEmail == 0) {
          $result["errors"][] = "Ne postojeća mail adresa";
        }
    }
    if (count($result["errors"]) == 0) {
        $successReset = emailResetPassword($email);
        if($successReset == USER_HANDLER_OK){
          $result["data"][] = "Na Vašu mail adresu je poslat zahtev za kreiranje nove lozinke.";
        }else{
          $result["errors"][] = "Postoje problemi sa serverom, molimo pokušajte kasnije!";
      }   
    }
  }

  
?>

<html lang="en">
<head>
    <?php printIncludes('Pitanje') ?>
</head>
<body>
  <div class="container main-container">
    <?php includeNavigation() ?>
    <div class="row">
          
      <div class="col-sm-8"> 
        &emsp;Upišite Vašu mail adresu kako bi smo Vam poslali zahtev za kreiranje nove lozinke.<br><br>

        <div class="col-sm-7"> 
          <?php
            foreach ($result["errors"] as $error) {
              echo "<div class=\"alert alert-danger\" role=\"alert\"><center>$error<br></center></div>";
            }
          ?>

          <?php
            foreach ($result["data"] as $msg) {
              echo "<div class=\"alert alert-success\" role=\"alert\"><center>$msg<br></center></div>";
            }
          ?>
        </div>

        <form action="" method="post"> 
          <div class="form-group">  
            <label for="email">&emsp;Email:</label>
            <div class="col-sm-7">
              <input type="text" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>" class="form-control">
            </div>
          </div>
          <div class="col-sm-7">
            <input type="submit" name="potvdi" class="btn btn-primary float-right" value="Pošalji">
          </div>
        </form>

      </div>
    </div>
    <?php includeFooter() ?>
  </div>
</body>
</html>