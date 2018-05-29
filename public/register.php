<?php
  require_once 'parts.php';

  if($user) {
    header("Location: index.php");
    exit();
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php printIncludes('Registracija') ?>
</head>
<body>
  <?php includeNavigation() ?>

  <div class="container main-container">
    <?php includeQuoteHeader() ?>

    <div class="row register-tab">
      <div class="col-lg-6 register-motto">
        <div class="register-motto-content text-center">
          <h3>Od studenata za studente</h3>
          <img src="img/students.png" alt="students">
        </div>
      </div>
      <div class="col-lg-6" id="registration-form-container">
        <h2 class="text-center">Pravljenje naloga</h2>
        <div class="form-result-box"></div>
        <form>
          <input type="hidden" name="formType" value="registrationForm">
          <div class="form-group">
            <label for="name">Korisničko ime:</label>
            <input type="text" name="name" id="" class="form-control">
            <small class="form-text text-muted">Korisničko ime je vid autentikacije na sajtu.</small>
          </div>
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" name="email" id="" class="form-control">
            <small class="form-text text-muted">U nastavku registracije će Vam na ovaj email stici link za aktivaciju naloga. On takođe igra važnu ulogu u slučaju da zaboravite lozinku.</small>
          </div>
          <div class="form-group">
            <label for="password">Lozinka:</label>
            <input type="password" name="password" id="" class="form-control">
          </div>
          <div class="form-group">
            <label for="password2">Ponovite lozinku:</label>
            <input type="password" name="password2" id="" class="form-control">
            <small class="form-text text-muted">Radi sigurnosti je potrebno da unesete lozinku još jednom.</small>
          </div>
          <input type="submit" class="btn btn-primary float-right" value="Registruj se">
        </form>
      </div>
    </div>
    <?php includeFooter() ?>
  </div>

  <?php includeScripts() ?>
  <script>
    $("form").submit(function(event) {
      event.preventDefault();
      var form = $(this);
      var data = form.serialize();
      var messageBox = $(".form-result-box");
      var output = "";
      $.ajax({
        url: 'formHandler.php',
        type: 'post',
        dataType: 'json',
        data: data,
        success: function(result) {
          console.log(result);
          try {
            if(result.errors.length === 0) {
              window.open("index.php", "_self");
              $("input:not([type=\"hidden\"], [type=\"submit\"])").each(function() {
                $(this).val("");
              });
            }
            else
              output = "<div class=\"alert alert-danger\" role=\"alert\">" + result.errors.join("<br>") + "</div>";
          }
          catch(err) {
            output = "<div class=\"alert alert-danger\" role=\"alert\">Postoje problemi sa serverom, molimo pokušajte kasnije!</div>";
          }
        },
        error: function() {
          output = "<div class=\"alert alert-danger\" role=\"alert\">Postoje problemi sa serverom, molimo pokušajte kasnije!</div>";
        },
        complete: function() {
          if(output === "")
            return;
          messageBox.html(output);
          $('html, body').animate({
            scrollTop: $("#registration-form-container").offset().top - 59
          }, 1000);
        }
      });
    });
  </script>
</body>
</html>
