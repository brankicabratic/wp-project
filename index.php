<?php
  require_once("parts.php");
  require_once 'db_utils.php';
 ?>
<html>
  <head>
    <?php printIncludes("Početna"); ?>
  </head>
  <body>
    <?php printHeader(); ?>
    <main>
      <div align='center'>
      <form method="post">
        <input type="text" name="PostaviPitanje" placeholder="What is your question?">
        <input type="submit" value="Send" >
      </form>
      </div>
      <div align="right">
        <h4>Top Questions</h4>
        <?php
        $post=getPost();
        for($i=0; $i<=count($post); $i++)
          echo "<a href='post.php?id=$i' >".$post[$i]['Naslov']."</a><br>";
        
        ?>
        </div>
        <div align="center">
        <h2>Recent questions</h2>
        <?php
          foreach ($post as $key => $value) {
            echo "<div><br>";
            foreach ($value as $id => $val){
              echo $val."<br>";
            }
            echo "</div>";
          }
          ?>
        </div>
      </main>
  </body>
</html>
