<?php
  require_once("col_config.php");
  require_once("functions.php");

  class Database {
    /**
     * List of methods:
     *
     * getUser($username) DONE
     * createUser($username, $firstName, $lastName, $password, $email)    DONE
     * getUserID($username) DONE
     * getNthPageQuestions($page, $step)
     * doesExist($table, $ID) DONE
     * insertPost($ID, $author, $content) DONE
     * insertQuestion($author, $header, $content) DONE
     * insertAnswer($author, $content, $questionID) DONE
     * insertTag($name) DONE
     * deletePost($postID) DONE (used for deleting questions and answers too)
     * clearTags($postID)
     * getAnswersRelatedToQuestion($questionID)
     * getTagsRelatedToQuestion($questionID)
     * updateUser(&$user) DONE
     * doesReactionExist($userID, $postID) DONE
     * saveReaction($userID, $postID, $type) DONE
     */
    private $connection, $idTable;
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
      $this->idTable = array(
        DB_USER_TABLE => COL_USER_ID,
        DB_POST_TABLE => COL_POST_ID,
        DB_QUESTION_TABLE => COL_QUESTION_ID,
        DB_ANSWER_TABLE => COL_ANSWER_ID,
        DB_TAG_TABLE => COL_TAG_ID
      );
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
    public function createUser($username, $firstName, $lastName, $password, $email) {
      $ID;
      while($this->doesExist(DB_USER_TABLE, ($ID = rand(-2147483648, 2147483647))));
      $stmt = $this->connection->prepare("INSERT INTO
                                          ".DB_USER_TABLE."(".COL_USER_ID.", ".COL_USER_USERNAME.", ".COL_USER_FIRSTNAME.", ".COL_USER_LASTNAME.", ".COL_USER_PASSWORD.", ".COL_USER_EMAIL.")
                                          VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("isssss", $ID, $username, $firstName, $lastName, $password, $email);
      return $stmt->execute();
    }

    public function doesExist($table, $ID) {
      if(!isset($this->idTable[$table]))
        return null;
      $stmt = $this->connection->prepare("SELECT 1
                                          FROM {$table}
                                          WHERE {$this->idTable[$table]} = ?");
      $stmt->bind_param("i", $ID);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_NUM) != NULL;
    }

    /**
     * returns UserID of User with given username or null if user doesnt exist
     */
    public function getUserID($username) {
      $stmt = $this->connection->prepare("SELECT ".COL_USER_ID." FROM ".DB_USER_TABLE." WHERE ".COL_USER_USERNAME." = ?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
    }

    /**
     * @param questionID has to be integer
     * @return question object as associative array or null if such doesn't exist
     */
    /*public function getQuestion($questionID) {
      $stmt = $this->connection->prepare("SELECT * FROM ".DB_QUESTION_TABLE." Q, ".DB_POST_TABLE." P WHERE Q.".COL_QUESTION_ID." = ? AND Q.".COL_QUESTION_ID." = P.".COL_POST_ID);
      $stmt->bind_param("i", $questionID);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
    }*/

    /**
     * @return question suited for page $page and step $step ordered by ID, every newer record has ID bigger than older one
     */
    public function getNthPageQuestions($page, $step) {
      if($step < 1 || $page < 1)
        return null;
      $start = ($page - 1) * $step;
      $stmt = $this->connection->prepare("SELECT Q.".COL_QUESTION_ID.", Q.".COL_QUESTION_HEADER.", P.".COL_POST_POSTED.", A.".COL_USER_USERNAME." FROM ".DB_QUESTION_TABLE." Q, ".DB_POST_TABLE." P, ".DB_USER_TABLE." A WHERE Q.".COL_QUESTION_ID." = P.".COL_POST_ID." AND A.".COL_USER_ID." = P.".COL_POST_AUTHOR." ORDER BY P.".COL_POST_ID." DESC LIMIT ?, ?");
      $stmt->bind_param("ii", $start, $step);
      $stmt->execute();
      return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     *
     */
    private function getSmallestAvaliablePostID() {
      $stmt = $this->connection->prepare("SELECT ".COL_POST_ID." FROM ".DB_POST_TABLE." ORDER BY ".COL_POST_ID." DESC LIMIT 1");
      $stmt->execute();
      $result = $stmt->get_result()->fetch_all(MYSQLI_NUM)[0];
      return ($result === null) ? -214748364 : ($result[0] + 1); // minimum value of mysqls int
    }

    /**
     * @param author user's username as string
     * @param content string
     * @param time (optional) time as string, recommended format "Y-m-d H:i:s"
     * @return ID of inserted post or false if query failed
     */
    public function insertPost($ID, $author, $content) {
      if(($userID = $this->getUserID($author)) === null)
        return false;
      $stmt = $this->connection->prepare("INSERT INTO ".DB_POST_TABLE."(".COL_POST_ID.", ".COL_POST_CONTENT.", ".COL_POST_AUTHOR.") VALUES (?, ?, ?)");
      $stmt->bind_param("isi", $ID, $content, $userID);
      return $stmt->execute();
    }

    /**
     * Tries to insert question into database and returns if query succeeded
     */
    public function insertQuestion($author, $header, $content) {
      $ID = $this->getSmallestAvaliablePostID();
      if($this->insertPost($ID, $author, $content) === false)
        return false;
      $stmt = $this->connection->prepare("INSERT INTO ".DB_QUESTION_TABLE."(".COL_QUESTION_ID.", ".COL_QUESTION_HEADER.") VALUES (?, ?)");
      $stmt->bind_param("is", $ID, $header);
      $result = $stmt->execute();
      if(!$result)
        $this->deletePost($ID);
      return $result;
    }

    /**
     * Tries to insert answer into database and returns if query succeeded
     */
    public function insertAnswer($author, $content, $questionID) {
      $ID = $this->getSmallestAvaliablePostID();
      if($this->insertPost($ID, $author, $content) === false)
        return false;
      $stmt = $this->connection->prepare("INSERT INTO ".DB_ANSWER_TABLE."(".COL_ANSWER_ID.", ".COL_ANSWER_PARENT.") VALUES (?, ?)");
      $stmt->bind_param("ii", $ID, $questionID);
      $result = $stmt->execute();
      if(!$result)
        $this->deletePost($ID);
      return $result;
    }

    public function insertTag($name) {
      $ID;
      while($this->doesExist(DB_TAG_TABLE, ($ID = rand(-2147483648, 2147483647))));
      $stmt = $this->connection->prepare("INSERT INTO ".DB_TAG_TABLE."(".COL_TAG_ID.", ".COL_TAG_NAME.") VALUES (?, ?)");
      $stmt->bind_param("is", $ID, $name);
      return $stmt->execute();
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
     * @param questionID of question whose tags you want to delete
     * @return bool as success of query
     */
    public function clearTags($postID) {
      $stmt = $this->connection->prepare("DELETE FROM ".DB_POSTTAG_TABLE." WHERE ".COL_POSTTAG_POST." = ?");
      $stmt->bind_param("i", $postID);
      return $stmt->execute();
    }

    /**
     * @param questionID primary key of question
     * @return array of answers associated with given question
     */
    public function getAnswersRelatedToQuestion($questionID) {
      $stmt = $this->connection->prepare("SELECT A.".COL_ANSWER_ID.", A.".COL_ANSWER_ACCEPTED.", P.".COL_POST_AUTHOR.", P.".COL_POST_POSTED.", P.".COL_POST_CONTENT." FROM ".DB_ANSWER_TABLE." A, ".DB_QUESTION_TABLE." Q, ".DB_POST_TABLE." P WHERE Q.".COL_QUESTION_ID." = ? AND A.".COL_ANSWER_PARENT." = Q.".COL_QUESTION_ID." AND A.".COL_ANSWER_ID." = P.".COL_ANSWER_ID);
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

    /**
     * @param $user User object as associative array
     * @return bool which represents success of query
     */
    public function updateUser(&$user) {
      if($user == null)
        return false;
      $stmt = $this->connection->prepare("UPDATE ".DB_USER_TABLE."
                                          SET
                                          ".COL_USER_USERNAME." = ?,
                                          ".COL_USER_PASSWORD." = ?,
                                          ".COL_USER_FIRSTNAME." = ?,
                                          ".COL_USER_LASTNAME." = ?,
                                          ".COL_USER_SEX." = ?,
                                          ".COL_USER_AVATAR." = ?,
                                          ".COL_USER_EMAIL." = ?,
                                          ".COL_USER_MAJOR." = ?,
                                          ".COL_USER_ABOUT." = ?,
                                          ".COL_USER_BIRTHDAY." = ?,
                                          ".COL_USER_ENROLLED." = ?
                                          WHERE ".COL_USER_ID." = ?");
      $stmt->bind_param("ssssssssssii",
                        $user[COL_USER_USERNAME],
                        $user[COL_USER_PASSWORD],
                        $user[COL_USER_FIRSTNAME],
                        $user[COL_USER_LASTNAME],
                        $user[COL_USER_SEX],
                        $user[COL_USER_AVATAR],
                        $user[COL_USER_EMAIL],
                        $user[COL_USER_MAJOR],
                        $user[COL_USER_ABOUT],
                        $user[COL_USER_BIRTHDAY],
                        $user[COL_USER_ENROLLED],
                        $user[COL_USER_ID]);
      return $stmt->execute();
    }

    /**
     * Checks for existance of reaction
     */
    public function doesReactionExist($userID, $postID) {
      $stmt = $this->connection->prepare("SELECT 1 FROM ".DB_REACTION_TABLE." WHERE ".COL_REACTION_USER." = ? AND ".COL_REACTION_POST." = ?");
      $stmt->bind_param("ii", $userID, $postID);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_NUM) != NULL;
    }

    /**
     * Set reaction to given value and delete it if type REACTION_NONE is passed
     */
    public function saveReaction($userID, $postID, $type) {
      if($type != REACTION_LIKE && $type != REACTION_DISLIKE && $type != REACTION_NONE)
        return false;
      $exists = $this->doesReactionExist($userID, $postID);

      $stmt;

      if($type == REACTION_NONE) {
        if(!$exists)
          return true;
        else {
          $stmt = $this->connection->prepare("DELETE FROM ".DB_REACTION_TABLE." WHERE ".COL_REACTION_USER." = ? AND ".COL_REACTION_POST." = ?");
          $stmt->bind_param("ii", $userID, $postID);
        }
      }
      else {
        if($exists)
          $stmt = $this->connection->prepare("UPDATE ".DB_REACTION_TABLE."
                                              SET ".COL_REACTION_TYPE." = ?
                                              WHERE ".COL_REACTION_USER." = ? AND ".COL_REACTION_POST." = ?");
        else
          $stmt = $this->connection->prepare("INSERT INTO ".DB_REACTION_TABLE."(".COL_REACTION_TYPE.", ".COL_REACTION_USER.", ".COL_REACTION_POST.")
                                              VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $type, $userID, $postID);
      }
      return $stmt->execute();
    }
  }
?>
