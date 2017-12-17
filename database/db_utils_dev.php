<?php
  require_once("col_config.php");
  require_once("functions.php");

  /**
   * System uses bcrypt for crypting password
   */

  class Database {
    private $connection;
    public function __construct($configFile = "db.ini") {
      if($config = parse_ini_file($configFile)) {
        $server = $config["server"];
        $user = $config["user"];
        $password = $config["password"];
        $database = $config["database"];
        $this->connection = new mysqli($server, $user, $password, $database);
      }
      else
        exit("Missing configuration file.");
    }
    public function __destruct() {
      $this->connection->close();
    }

    /**
     * @param username as string
     * @return user as associative array or null if user is not found
     */
    public function getUser($username) {
      $stmt = $this->connection->prepare("SELECT * FROM ".DB_USER_TABLE." WHERE ".COL_USER_USERNAME." = ?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * Store user with given parameters in database
     * @return true if everything went fine, otherwise return false(usually whether username already exists in database if types are checked)
     */
    public function insertUser($username, $password, $fullName, $avatar, $email, $major, $enrollmentYear) {
      $stmt = $this->connection->prepare("INSERT INTO User(".COL_USER_USERNAME.", ".COL_USER_PASSWORD.", ".COL_USER_NAME.", ".COL_USER_AVATAR.
                                         ", ".COL_USER_EMAIL.", ".COL_USER_MAJOR.", ".COL_USER_ENROLLED.") VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("ssssssi", $username, password_hash($password, PASSWORD_DEFAULT), $fullName, $avatar, $email, $major, $enrollmentYear);
      return $stmt->execute();
    }

    /**
     * returns UserID of User with given username or NULL if user doesnt exist
     */
    public function getUserID($username) {
      $stmt = $this->connection->prepare("SELECT ".COL_USER_ID." FROM ".DB_USER_TABLE." WHERE ".COL_USER_USERNAME." = ?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
    }

    /**
     * @param username has to be string
     * @param password has to be string
     * @return User object as associative array or null if authentication failed
     */
    public function authenticateUser($username, $password) {
      $stmt = $this->connection->prepare("SELECT * FROM ".DB_USER_TABLE." WHERE ".COL_USER_USERNAME." = ?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      if(($result = $stmt->get_result()->fetch_array(MYSQLI_ASSOC)) === NULL)
        return false;
      return password_verify($password, $result[COL_USER_PASSWORD]) ? $result : false;
    }

    /**
     * @param questionID has to be integer
     * @return question object as associative array or NULL if such doesn't exist
     */
    public function getQuestion($questionID) {
      $stmt = $this->connection->prepare("SELECT * FROM ".DB_QUESTION_TABLE." Q, ".DB_POST_TABLE." P WHERE Q.".COL_QUESTION_ID." = ? AND Q.".COL_QUESTION_ID." = P.".COL_POST_ID);
      $stmt->bind_param("i", $questionID);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * @param author user's username as string
     * @param content string
     * @param time (optional) time as string, recommended format "Y-m-d H:i:s"
     * @return ID of inserted post or false if query failed
     */
    public function insertPost($author, $content, $time = null) {
      if(($userID = $this->getUserID($author)) === NULL)
        return false;
      if($time === null)
        $time = date("Y-m-d H:i:s");
      $stmt = $this->connection->prepare("INSERT INTO ".DB_POST_TABLE."(".COL_POST_CONTENT.", ".COL_POST_TIME.", ".COL_POST_AUTHOR.") VALUES (?, ?, ?)");
      $stmt->bind_param("ssi", $content, $time, $userID);
      return $stmt->execute() ? $stmt->insert_id : false;
    }

    /**
     * @param postID of post you want to delete
     * @return bool as success of query
     */
    public function deletePost($postID) {
      $stmt = $this->connection->prepare("DELETE FROM ".DB_POST_TABLE." WHERE ".COL_POST_ID." = ?");
      $stmt->bind_param("i", $postID);
      return $stmt->execute();
    }

    /**
     * Tries to insert question into database and returns if query succeeded
     */
    public function insertQuestion($author, $header, $content, $time = null) {
      if(($postID = $this->insertPost($author, $content, $time)) === false)
        return false;
      $stmt = $this->connection->prepare("INSERT INTO ".DB_QUESTION_TABLE."(".COL_QUESTION_ID.", ".COL_QUESTION_HEADER.") VALUES (?, ?)");
      $stmt->bind_param("is", $postID, $header);
      $result = $stmt->execute();
      if(!$result)
        $this->deletePost($postID);
      return $result;
    }

    /**
     * Tries to insert answer into database and returns if query succeeded
     */
    public function insertAnswer($author, $content, $questionID, $time = null, $accepted = 0) {
      if(($postID = $this->insertPost($author, $content, $time)) === false)
        return false;
      $stmt = $this->connection->prepare("INSERT INTO ".DB_ANSWER_TABLE."(".COL_ANSWER_ID.", ".COL_ANSWER_PARENT.", ".COL_ANSWER_ACCEPTED.") VALUES (?, ?, ?)");
      $stmt->bind_param("iii", $postID, $questionID, $accepted);
      $result = $stmt->execute();
      if(!$result)
        $this->deletePost($postID);
      return $result;
    }

    /**
     * @param questionID primary key of question
     * @return array of answers associated with given question
     */
    public function getAnswersRelatedToQuestion($questionID) {
      $stmt = $this->connection->prepare("SELECT A.".COL_ANSWER_ID.", A.".COL_ANSWER_ACCEPTED.", P.".COL_POST_AUTHOR.", P.".COL_POST_TIME.", P.".COL_POST_CONTENT." FROM ".DB_ANSWER_TABLE." A, ".DB_QUESTION_TABLE." Q, ".DB_POST_TABLE." P WHERE Q.".COL_QUESTION_ID." = ? AND A.".COL_ANSWER_PARENT." = Q.".COL_QUESTION_ID." AND A.".COL_ANSWER_ID." = P.".COL_ANSWER_ID);
      $stmt->bind_param("i", $questionID);
      $stmt->execute();
      return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @param questionID primary key of question
     * @return array of tags associated with given question
     */
    public function getTagsRelatedToQuestion($questionID) {
      $stmt = $this->connection->prepare("SELECT T.".COL_TAG_NAME." FROM ".DB_TAG_TABLE." T, ".DB_POSTTAG_TABLE." QT WHERE QT.".COL_POST_ID." = ? AND QT.".COL_TAG_ID." = T.".COL_TAG_ID);
      $stmt->bind_param("i", $questionID);
      $stmt->execute();
      return get_first($stmt->get_result()->fetch_all());
    }
  }
?>
