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

  function getPost($id) {
    $db = dbConnect();
    $sql = $db->prepare("SELECT * FROM Post WHERE ".COL_POST_ID." = ?");
    $sql->bind_param("d", $id);
    if(!$sql->execute()) {
      $db->close();
      return false;
    }
    $result = $sql->get_result()->fetch_array(MYSQLI_ASSOC);
    $db->close();
    return $result;
  }

  function doesPostExist($pid) {
    $db = dbConnect();
    $sql = $db->prepare("SELECT COUNT(*) FROM Post WHERE ".COL_POST_ID." = ?");
    $sql->bind_param("d", $pid);
    $sql->execute();
    $result = $sql->get_result()->fetch_array(MYSQLI_NUM)[0];
    $db->close();
    return $result != 0;
  }

  function getPostTags($pid) {
    if(!doesPostExist($pid))
      return false;
    $ret_result = array();
    $db = dbConnect();
    $sql = $db->prepare("SELECT * FROM PostTags WHERE ".COL_POST_ID." = ?");
    $sql->bind_param("d", $pid);
    if(!$sql->execute()) {
      $db->close();
      return false;
    }
    $results = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    return array_map(create_function('$el', 'return $el["TagID"];'), $results);
  }

  function insertUser($username, $password, $fullname, $avatar, $email, $major, $enrollmentyear) {
    $db = dbConnect();
    $query = "INSERT INTO User(".COL_USER_USERNAME.", ".COL_USER_PASSWORD.", ".COL_USER_NAME.", "
                  .COL_USER_AVATAR.", ".COL_USER_EMAIL.", ".COL_USER_MAJOR.", ".COL_USER_ENROLLED.") VALUES (?, ?, ?, ?, ?, ?, ?)";
    echo $query;
    $sql = $db->prepare($query);
    $sql->bind_param("ssssssd", $username, password_hash($password, PASSWORD_DEFAULT), $fullname, $avatar, $email, $major, $enrollmentyear);
    $result = $sql->execute();
    $db->close();
    return $result;
  }
?>
