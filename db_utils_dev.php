<?php
  require_once("config.php");

  function dbConnect() {
    return new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
  }

  function getUser($id) {
    $db = dbConnect();
    $sql = $db->prepare("SELECT * FROM User WHERE ".COL_USER_ID." = ?");
    $sql->bind_param("d", $id);
    if(!$sql->execute()) {
      $db->close();
      return false;
    }
    $result = $sql->get_result()->fetch_array(MYSQLI_ASSOC);
    $db->close();
    return $result;
  }

  function insertUser($username, $password, $fullname, $avatar, $email, $major, $enrollmentyear) {
    $db = dbConnect();
    $query = "INSERT INTO User(".COL_USER_USERNAME.", ".COL_USER_PASSWORD.", ".COL_USER_NAME.", "
                  .COL_USER_AVATAR.", ".COL_USER_EMAIL.", ".COL_USER_MAJOR.", ".COL_USER_ENROLLED.") VALUES (?, ?, ?, ?, ?, ?, ?)";

    $sql = $db->prepare($query);
    $sql->bind_param("ssssssd", $username, password_hash($password, PASSWORD_DEFAULT), $fullname, $avatar, $email, $major, $enrollmentyear);
    $result = $sql->execute();
    $db->close();
    return $result;
  }

  var_dump(getUser(2));
?>
