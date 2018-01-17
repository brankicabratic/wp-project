<?php
	require_once 'session.php';

  function includeNavigation() {
  	global $user;
?>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-primary">
    <div class="container">
      <a href="index.php" class="navbar-brand">PMFOverflow</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Pitanja</a>
          </li>
        </ul>
<?php
		if ($user) {
?>
				<!-- FOR LOGGED IN USERS -->
        <span class="navbar-text disabled">You're logged in as <a href="profile.php?user=peraPeric">Pera Peric</a> (<a href="#">Log out</a>)</span>
<?php
		} else {
?>       
        <!-- FOR ANONYMOUS USERS -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="login.php">Uloguj se</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="register.php">Registruj se</a>
          </li>
        </ul>
<?php
		}
?>      
      </div>
    </div>
  </nav>
<?php
  }

  function printIncludes($title) {
?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title><?php echo $title ?></title>

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/master.min.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
  <link rel="stylesheet" type="text/css" href="css/fontawesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<?php
  }

  function includeScripts() {
?>
  <script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/jquery.cookies.js"></script>
  <script type="text/javascript" src="js/master.js"></script>
  <script defer src="js/fontawesome-all.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
<?php
  }

  function includeQuoteHeader() {
?>
  <div class="home-header">
    <div class="home-header-content">
      <h3>"The important thing is not to stop questioning. Curiosity has its own reason for existing."</h3>
      <small>Albert Einstein</small>
    </div>
  </div>
<?php
  }

  function includeFooter() {
?>
  <footer>
    <div class="row">
      <div class="col-lg-4">
        <span class="footer-heading">Sajtovi departmana</span>
        <ul class="footer-link-list">
          <li><a href="http://www.dbe.uns.ac.rs/">Departman za biologiju i ekologiju</a></li>
          <li><a href="http://www.dgt.uns.ac.rs/">Departman za geografiju, turizam i hotelijerstvo</a></li>
          <li><a href="https://www.dmi.uns.ac.rs/">Departman za matematiku i informatiku</a></li>
          <li><a href="http://www.df.uns.ac.rs/">Departman za fiziku</a></li>
          <li><a href="http://www.dh.uns.ac.rs/">Departman za hemiju, biohemiju i zaštitu životne sredine</a></li>
        </ul>
      </div>
      <div class="col-lg-4">
        <span class="footer-heading">Korisni linkovi</span>
        <ul class="footer-link-list">
          <li><a href="https://www.pmf.uns.ac.rs/">PMF</a></li>
          <li><a href="https://eportal.pmf.uns.ac.rs/">Eportal PMF</a></li>
          <li><a href="https://moodle.pmf.uns.ac.rs/">PMF Moodle</a></li>
          <li><a href="https://perun.pmf.uns.ac.rs/moodle/">Perun Moodle</a></li>
        </ul>
      </div>
      <div class="col-lg-4 footer-info">
        <h5>Lorem ipsum dolor sit amet, cu eum eripuit docendi, virtute repudiare omittantur ei est.</h5>
        <p>Lorem ipsum dolor sit amet, cu eum eripuit docendi, virtute repudiare omittantur ei est.</p>
      </div>
    </div>
    <div class="copy">
      PMFOverflow | 2018
    </div>
  </footer>
<?php
  }
?>
