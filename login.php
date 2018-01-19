<?php
  require_once 'parts.php';

  if($user)
    header("Location: index.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php printIncludes('Uloguj se - PMFOverflow') ?>
</head>
<body>
  <?php includeNavigation() ?>

  <div class="container main-container">
    <div id="login-container">
      <form method="post">
        <input type="hidden" name="formType" value="loginForm">
        <h3>Prijavljivanje</h3>
        <div class="form-result-box"></div>
        <div class="form-group">
          <input type="text" name="username" placeholder="Korisničko ime">
        </div>
        <div class="form-group">
          <input type="password" name="password" placeholder="Lozinka">
        </div>
        <div class="checkbox">
          <label for="loginRemember"><input type="checkbox" id="loginRemember" name="remember-me"> Sačuvaj korisničko ime</label>
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Prijavi se">
        </div>

        <p>Nemate nalog? <a href="register.php">Registrujte se</a></p>
      </form>
    </div>

    <?php includeFooter() ?>
  </div>

  <?php includeScripts() ?>
  <script type="text/javascript">
    $("form").submit(function(event) {
      event.preventDefault();
      var form = $(this);
      var messageBox = form.find(".form-result-box");
      var data = form.serialize();
      var output;
      $.ajax({
        url: 'formHandler.php',
        type: 'post',
        dataType: 'json',
        data: data,
        success: function(result) {
          if(result.errors.length === 0) {
            window.open("index.php", "_self");
          }
          else
            output = "<div class=\"alert alert-danger\" role=\"alert\">" + result.errors.join("<br>") + "</div>";
        },
        error: function() {
          output = "<div class=\"alert alert-danger\" role=\"alert\">Postoje problemi sa servevom, molimo pokušajte kasnije!</div>";
        },
        complete: function() {
          messageBox.html(output);
        }
      });
    });
  </script>
</body>
</html>
