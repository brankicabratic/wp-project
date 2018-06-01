<?php 
  require_once 'db_utils.php';
  require_once 'parts.php';
  require_once 'handlers/user_handler.php';

  $result = array(
    "errors" => array(),
    "data" => array()
  );

  if (isset($_POST['potvdi'])) {
    if (!isset($_POST["password"]) || !isset($_POST["password"]) || !isset($_POST["password2"]) || empty($_POST["password2"])){
      $result["errors"][] = "Morate popuniti oba polja.";
    }
    if (count($result["errors"]) == 0) {
      if ($_POST["password"] != $_POST["password2"]) {
        $result["errors"][] = "Unete lozinke nisu jednake.";
      }
      if (strlen($_POST["password"]) < 6){
        $result["errors"][] = "Dužina lozinke mora biti najmanje 6 karaktera.";
      }
    }
    if (count($result["errors"]) == 0) {
      $success = resetPassword($_GET["email"], $_POST["password"]);
      if ($success) {
        $result["data"][] = "Uspešno ste promenuli lozinku";
        header("Refresh: 1; url=login.php");
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
      <div class="col-lg-8">

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
            <div class="col-sm-7">
            <label for="password">Nova lozinka:</label>
            <input type="password" name="password" id="" class="form-control">
            <label for="password2">Ponovite lozinku:</label>
            <input type="password" name="password2" id="" class="form-control">
            <small class="form-text text-muted">Radi sigurnosti je potrebno da unesete lozinku još jednom.</small>
          </div>
        </div>
        <div class="col-sm-7">
          <input type="submit" name="potvdi" class="btn btn-primary float-right" value="Potvrdi"> 
        </div>
        </form>
      </div>
    </div>
    <?php includeFooter() ?>
  </div>
</body>
</html>