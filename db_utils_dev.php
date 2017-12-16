<?php
  require_once("col_config.php");
  require_once("functions.php");

  class Database {
    private $connection;
    public function __construct() {
      if($config = parse_ini_file("db.ini")) {
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
      $stmt = $this->connection->prepare("SELECT * FROM User WHERE ".COL_USER_USERNAME." = ?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * @param page
     * @param step Number of posts per page
     * @return array with posts ordered descending by date/time of posting
     */
    public function getNthPagePosts($page, $step) {
      if(strtolower(gettype($page)) != "integer" || strtolower(gettype($step)) != "integer" || $page < 1 || $step < 1)
        return false;

      $start = ($page - 1) * $step;
      $stmt = $this->connection->prepare("SELECT * FROM Post ORDER BY ".COL_POST_DATETIME." DESC LIMIT ?, ?");
      $stmt->bind_param("ii", $start, $step);
      $stmt->execute();
      return $stmt->get_result()->fetch_all();
    }

    /**
     * @param postID as integer
     * @return post as associative array or null if post is not found
     */
    public function getPost($postID) {
      $stmt = $this->connection->prepare("SELECT * FROM Post WHERE ".COL_POST_ID." = ?");
      $stmt->bind_param("i", $postID);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * @param postID primary key of post
     * @return array of actions associated with given post
     */
    public function getReactionsOnPost($postID) {
      $stmt = $this->connection->prepare("SELECT ".COL_SCORE_TYPE." FROM Score WHERE ".COL_SCORE_POST." = ?");
      $stmt->bind_param("i", $postID);
      $stmt->execute();
      return get_first($stmt->get_result()->fetch_all());
    }

    /**
     * @param postID primary key of post
     * @return array of tags associated with given post
     */
    public function getTagsOfPost($postID) {
      $stmt = $this->connection->prepare("SELECT T.".COL_TAG_NAME." FROM Tag T, PostTags PT WHERE PT.".COL_POST_ID." = ? AND PT.".COL_TAG_ID." = T.".COL_TAG_ID);
      $stmt->bind_param("i", $postID);
      $stmt->execute();
      return get_first($stmt->get_result()->fetch_all());
    }

    /**
     * @param postID primary key of post
     * @return array of answers associated with given post
     */
    public function getAnswersOfPost($postID) {
      $stmt = $this->connection->prepare("SELECT * FROM Answer WHERE ".COL_ANSWER_POST." = ?");
      $stmt->bind_param("i", $postID);
      $stmt->execute();
      return $stmt->get_result()->fetch_all();
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
  }
?>
