<?php 
  require_once 'parts.php';
  require_once 'db_utils.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <?php printIncludes('Pitanje') ?>
  </head>
  <body>

    <div class="container main-container">
      <?php includeNavigation() ?>
      
      <!-- TODO Move style to css -->
      <div style="width: 100%; margin:50px 0px 45%; text-align: center; font-size: 25px; width: 100%;">
          Pitanje kojem ste pokušali da pristupite više ne postoji
      </div>

      <?php includeFooter() ?>
    </div>
    <?php includeScripts() ?>
  </body>
</html>
