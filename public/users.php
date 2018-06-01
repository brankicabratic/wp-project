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
      <div style="width: 100%; margin:50px 0px 45%; text-align: center; font-size: 25px; width: 100%;">
        <table border="2px">
          <tr>
            <td>Prezime</td>
            <td>Ime</td>
            <td>Username</td>
            <td>Poslednji put viđen</td>
          </tr>
        <?php
          $db=new Database;
          $list = $db->getListOfUsers();
          foreach ((array)$list as $dat) {
            echo "<tr>";
            echo "<td>";
            echo $dat[COL_USER_FIRSTNAME];
            echo "</td>";
            echo "<td>";
            echo $dat[COL_USER_LASTNAME];
            echo "</td>";
            echo "<td>";
            echo "<a href=\"profile.php?user={$dat[COL_USER_USERNAME]}\">{$dat[COL_USER_USERNAME]}</a>";
            echo "</td>";
            echo "<td>";
            echo $dat[COL_USER_LASTSEEN];
            echo "</td>";
            echo "</tr>";
          }
        ?> 
        </table>
      </div>

      <?php includeFooter() ?>
    </div>
    <?php includeScripts() ?>
  </body>
</html>