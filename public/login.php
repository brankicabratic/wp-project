<?php
  require_once 'parts.php';

  if($user) {
    header("Location: index.php");
    exit();
  }

  $remembered_username = "";
  if(isset($_COOKIE["remembered_username"]))
    $remembered_username = htmlspecialchars($_COOKIE["remembered_username"]);
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
          <div class="login-input-group<?php if($remembered_username) echo " focused" ?>">
            <label for="login-form-username">Korisničko ime</label>
            <input type="text" id="login-form-username" name="username"<?php if($remembered_username) echo " value=\"$remembered_username\"" ?>>
          </div>
        </div>
        <div class="form-group">
          <div class="login-input-group">
            <label for="login-form-password">Lozinka</label>
            <input type="password" id="login-form-password" name="password">
          </div>

        </div>
        <div class="checkbox">
          <label for="loginRemember"><input type="checkbox" id="loginRemember" name="remember-me"<?php if($remembered_username) echo " checked" ?>> Sačuvaj korisničko ime</label>
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
    $("input:not([type=\"submit\"])").on("input", function(e) {
      var el = $(this);
      var parent = el.parent();
      if(el.val() == "")
        parent.removeClass("focused");
      else
        parent.addClass("focused");
    });



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
          try {
            if(result.errors.length === 0) {
              window.open("index.php", "_self");
            }
            else
              output = "<div class=\"alert alert-danger\" role=\"alert\">" + result.errors.join("<br>") + "</div>";
          }
          catch(err) {
            output = "<div class=\"alert alert-danger\" role=\"alert\">Postoje problemi sa servevom, molimo pokušajte kasnije!</div>";
          }
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
