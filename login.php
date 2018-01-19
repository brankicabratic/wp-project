<?php
  require_once 'parts.php';
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
      <form>
        <input type="hidden" name="formType" value="loginForm">
        <h3>Prijavljivanje</h3>
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
</body>
</html>
