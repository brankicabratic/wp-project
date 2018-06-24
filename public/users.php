<?php 
  require_once 'parts.php';
  require_once 'db_utils.php';

  function printPageLinks($numAllPages, $page) {
    echo "<div class=\"pages\">";
    if ($numAllPages > 10) {
      $from = $page - 5;
      $to = $page + 4;
      while ($from < 1) {
        $from = $from + 1;
        $to = $to + 1;
      }
      while ($to > $numAllPages) {
        $from = $from - 1;
        $to = $to - 1;
      }
    }
    else {
      $from = 1;
      $to = $numAllPages;
    }
    for ($i = $from; $i <= $to; $i++) {
      $class = $i == $page ? "page-link-selected" : "page-link";
      $url = $_SERVER['REQUEST_URI'];
      if (empty($_GET) && $i != $page) {
        $url = $_SERVER['REQUEST_URI']."?page={$i}";
      }
      else {
        $url = isset($_GET["page"]) ? preg_replace("%page=[/^[1-9][0-9]*|0$/]%", "page=$i", $url) : $_SERVER['REQUEST_URI']."&page={$i}" ;
      }
      if($numAllPages > 1){
        echo $i == $page ? "<span class=\"page-link-selected\"> $i </span>" : "<a href=\"{$url}\"><span class=\"page-link\"> $i </span></a>";
      }
    }
    echo "</div>";
  }

  function printUsersTable($users){
    $db = new Database;
    echo "<table id=\"users\"> 
            <tr>
              <th>Prezime</th>
              <th>Ime</th>
              <th>Korisničko ime</th>
              <th>Poslednji put viđen</th>
              <th>Rang korisnika</th>
              <th>Score</th>
            </tr>";
    foreach ($users as $userTable) {
      $user_id = $db->getUserID($userTable[COL_USER_USERNAME]);
      if (!$user_id) continue;
      $score=$db->getUserScore($user_id);
      echo "<tr>"; 
      echo "<td>{$userTable[COL_USER_FIRSTNAME]}</td>";
      echo "<td>{$userTable[COL_USER_LASTNAME]}</td>";
      echo "<td><a href=\"profile.php?user={$userTable[COL_USER_USERNAME]}\">{$userTable[COL_USER_USERNAME]}</a></td>";
      echo "<td>{$userTable[COL_USER_LASTSEEN]}</td>";
      if ($userTable[COL_USER_RANK]==0) {
      echo "<td>Nije aktiviran</td>";
      }else{
      echo "<td>{$userTable[COL_RANK_NAME]}";
      }
      echo "<td> $score </td>";
      echo "</tr>";
    } 
      echo "</table>";
  }

  $db = new Database;

  $numUsers = $db->getNumberOfUsers();
  $step = isset($_GET["step"]) && $_GET["step"] > 0 ? $_GET["step"] : 10;
  $numAllPages = ceil($numUsers / $step); 
  $page = isset($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $numAllPages ? $_GET["page"] : 1;
  $users = $db->getNthPageUsers($page, $step);
?>
<!DOCTYPE html>
<html>
  <head>
    <?php printIncludes('Korisnici') ?>
    <style>
      #users{
        font-family: 'Raleway', sans-serif;
        font-size: 14pt;
        border-collapse: collapse;
        width: 100%;
      }
      #users td, #users th {
          border: 1px solid #ddd;
          padding: 8px;
      }
      #users tr:nth-child(even){
        background-color: #f2f2f2;
      }
      #users tr:hover {
        background-color: #ddd;
      }
      #users th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: center;
        background-color: #007BFF;
        color: white;
      }
    </style>
  </head>
  <body>
    
    <div class="container main-container">
      <div class="row">
        <div class="col-lg-1"><!-- Sometime in the future something may even be here! It only exists for filling up the space at the moment. --></div>
         <div class="col-lg-8">
          <?php includeNavigation() ?>
          <div style="width: 100%; margin:50px 0px 45%; text-align: center; font-size: 25px; width: 100%;">
            <?php
              printUsersTable($users);
            ?> 
            </table>
            <?php printPageLinks($numAllPages, $page);?>
          </div>
        </div>
      </div>
      <?php includeFooter() ?>
    </div>
    <?php includeScripts() ?>
  </body>
</html>