<?php 
  require_once 'db_utils.php';
  require_once 'parts.php';
	require_once 'handlers/user_handler.php';

  if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
    $email = mysql_escape_string($_GET['email']);
    $hash = mysql_escape_string($_GET['hash']);
    $match = getUserEmailAndHash($email, $hash);
    
    if ($match) {
      // actiave acc
      echo "<div>Activated.</div>";
    } else {
      // error msg.
    }
  }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    
</body>
</html>