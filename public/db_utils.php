<?php
  require_once("col_config.php");
  require_once("functions.php");

  class Database {
    /**
     * List of methods:
     *
     * getUser($username, $getter=USER_GETTER_ALL) DONE
     * getUserByID($id, $getter=USER_GETTER_ALL) DONE
     * deleteUser($userID) DONE
     * movePost($userID) DONE
     * getListOfUsers() DONE
     * getNthPageUsers($page, $step) DONE
     * getNumberOfUsers() DONE
     * getPosts($userID) DONE
     * getRelationFromPost($postID) DONE
     * updateOnlineTime($username) DONE
     * createUser($username, $password, $email) DONE
     * getUserEmailAndHash($email, $hash) DONE
     * getAllEmail($email) DONE
     * getUserID($username) DONE
     * getUserUsername($userID)
     * getQuestionAuthor($questionID) DONE
     * getCheckedAnswer($answerID) DONE
     * getAllChecked($questionID) DONE
     * uncheckAll($questionID) DONE
     * updateChecked($answerID) DONE
     * getNthPageQuestions($page, $step) DONE
     * getAllCategories() DONE
     * getTagIDByName($name) DONE
     * doesExist($table, $ID) DONE
     * insertPost($ID, $author, $content, $type) DONE
     * insertQuestion($author, $header, $content) DONE
     * insertAnswer($author, $content, $questionID) DONE
     * deleteAnswer($answerID) DONE
     * insertTag($name) DONE
     * deletePost($postID) DONE (used for deleting questions and answers too)
     * getQuestion($questionID) DONE
     * getAnswersRelatedToQuestion($questionID, $page = 1, $step = 100) DONE
     * getPostsScore($postID) DONE
     * getPostsAuthor($postID) DONE
     * countQuestionsAnswers($questionID) DONE
     * getAvaliableTags() DONE
     * givePostATag($postID, $tagID) DONE
     * getTagsRelatedToQuestion($questionID) DONE
     * updateUser(&$user) DONE
     * updateProfile($ID, $firstName, $lastName, $major, $enrollmentYear, $email, $sex, $dateOfBirth, $biography) DONE
     * updateAvatar($ID, $newAvatar) DONE
     * updatePost($postID, $content) DONE
     * updateQuestion($postID, $header, $content) DONE
     * updateAnswer($postID, $content) DONE
     * updatePassword($ID, $password) DONE
     * resetPassword($email, $password) DONE
     * getPasswordByID($ID) DONE
     * doesReactionExist($userID, $postID) DONE
     * saveReaction($userID, $postID, $type) DONE
     * insertRank($rankID, $rankName) DONE
     * getUserRank($userID) DONE
     * promoteUser($username, $rankID) DONE
     * insertPermissionOrRestriction($permRestID, $permRestName, $type) DONE
     * grantRankAPermissionOrRestriction($rankID, $permRestID) DONE
     * deleteRanksPermissionOrRestriction($rankID, $permRestID) DONE
     * getUsersPermissionsOrRestrictions($userID)
     * getNumberOfQuestions() DONE
     * updateRank($name, $rankID) DONE
     */
    private $connection, $idTable;
    public function __construct($configFile = "../local/config.ini") {
      if($config = parse_ini_file($configFile)) {
        $server = $config["server"];
        $user = $config["user"];
        $password = $config["password"];
        $database = $config["database"];
        $this->connection = new mysqli($server, $user, $password, $database);
        $this->connection->set_charset($config["db_charset"]);
      }
      else
        exit("Missing configuration file.");
      mysqli_report(MYSQLI_REPORT_STRICT);
      $this->idTable = array(
        DB_USER_TABLE => COL_USER_ID,
        DB_POST_TABLE => COL_POST_ID,
        DB_QUESTION_TABLE => COL_QUESTION_ID,
        DB_ANSWER_TABLE => COL_ANSWER_ID,
        DB_TAG_TABLE => COL_TAG_ID
      );
      $this->getterTable = array(
        USER_GETTER_ALL => "*",
        USER_GETTER_AUTHENTICATION => COL_USER_USERNAME . ", " . COL_USER_PASSWORD,
        USER_GETTER_LOGIN_DATA => COL_USER_USERNAME . ", " . COL_USER_FIRSTNAME . ", " . COL_USER_LASTNAME
      );
    }
    public function __destruct() {
      $this->connection->close();
    }

    /**
     * @param username as string
     * @return user as associative array or null if user is not found
     */
    public function getUser($username, $getter=USER_GETTER_ALL) {
      if(!$username)
        return null;
      if(!isset($this->getterTable[$getter]))
        throw new Exception("Invalid getter passed to function getUser method in Database class.");
      $stmt = $this->connection->prepare("SELECT ".$this->getterTable[$getter]." FROM ".DB_USER_TABLE." WHERE ".COL_USER_USERNAME." = ?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * @param id of the user
     * @return user as associative array or null if user is not found
     */
    public function getUserByID($id, $getter=USER_GETTER_ALL) {
      if(!$id)
        return null;
      if(!isset($this->getterTable[$getter]))
        throw new Exception("Invalid getter passed to function getUserByID method in Database class.");
      $stmt = $this->connection->prepare("SELECT ".$this->getterTable[$getter]." FROM ".DB_USER_TABLE." WHERE ".COL_USER_ID." = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * Sets that user is online at the moment
     * @return boolean as successfulness of query
     */
    public function updateOnlineTime($username) {
      if(!$username)
        return false;
      $stmt = $this->connection->prepare("UPDATE ".DB_USER_TABLE."
                                          SET ".COL_USER_LASTSEEN." = now()
                                          WHERE ".COL_USER_USERNAME." = ?");
      $stmt->bind_param("s", $username);
      return $stmt->execute();
    }
    /**
     * Store user with given parameters in database
     * @return true if everything went fine, otherwise return false(usually whether username already exists in database if types are checked)
     */
    public function createUser($username, $password, $email, & $hash) {
      $ID;
      $hash = md5( rand(0,1000) );
      while($this->doesExist(DB_USER_TABLE, ($ID = rand(-2147483648, 2147483647))));
      $stmt = $this->connection->prepare("INSERT INTO
                                          ".DB_USER_TABLE."(".COL_USER_ID.", ".COL_USER_USERNAME.", ".COL_USER_PASSWORD.", ".COL_USER_EMAIL.", ".COL_USER_RANK.", ".COL_USER_HASH.")
                                          VALUES (?, ?, ?, ?, ".RANK_NOT_ACTIVATED.",?)");
      $stmt->bind_param("issss", $ID, $username, $password, $email, $hash);
      return $stmt->execute();
    }
    /**
     * returns User with given email and hash or null if user doesnt exist
     */
    public function getUserEmailAndHash($email, $hash) {
      $sql = "SELECT * FROM ".DB_USER_TABLE." WHERE ".COL_USER_EMAIL." = ?  AND ".COL_USER_HASH." = ?;";
      $stmt = $this->connection->prepare($sql);
      $stmt->bind_param("ss", $email, $hash);
      $stmt->execute();
      $result = $stmt->get_result()->fetch_row();
      return $result;
    }

    public function getAllEmail($email) {
      $sql = "SELECT ".COL_USER_EMAIL." FROM ".DB_USER_TABLE." WHERE ".COL_USER_EMAIL." = ?;";
      $stmt = $this->connection->prepare($sql);
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result()->fetch_array(MYSQLI_NUM);
      return $result;
    }

  /**
     * activate User with given email
     */
    function activateUser($email) {
      $stmt = $this->connection->prepare("UPDATE ".DB_USER_TABLE."
                                          SET
                                          ".COL_USER_RANK." = 1
                                          WHERE ".COL_USER_EMAIL." = ?");
      $stmt->bind_param("s", $email);
      return $stmt->execute();
    }

    /**
     * @return whether record with id $ID exist in $table
     */
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

    public function getUserUsername($userID) {
      $stmt = $this->connection->prepare("SELECT ".COL_USER_USERNAME." FROM ".DB_USER_TABLE." WHERE ".COL_USER_ID." = ?");
      $stmt->bind_param("i", $userID);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
    }

    public function getQuestionAuthor($questionID) {
      $stmt = $this->connection->prepare(" SELECT  ".COL_POST_AUTHOR." FROM ".DB_POST_TABLE." WHERE ".COL_POST_ID." = ?");
      $stmt->bind_param("i", $questionID);
      $stmt->execute();
      return $stmt->get_result()->fetch_row()[0];
    }

    /**
    * @param answerID
    * @return checked answer
    */
    public function getCheckedAnswer($answerID) {
      $stmt = $this->connection->prepare("SELECT ".COL_ANSWER_CHECKED." FROM ".DB_ANSWER_TABLE." WHERE ".COL_ANSWER_ID." = ?");
      $stmt->bind_param("i", $answerID);
      $stmt->execute();
      return $stmt->get_result()->fetch_row()[0];
    }

    /**
    * @param questionID
    * @return count of checked answers
    */
    public function getAllChecked($questionID) {
      $stmt = $this->connection->prepare("SELECT COUNT(".COL_ANSWER_CHECKED.") FROM ".DB_ANSWER_TABLE." WHERE ".COL_ANSWER_PARENT." = ?");
      $stmt->bind_param("i", $questionID);
      $stmt->execute();
      return $stmt->get_result()->fetch_row()[0];
    }

    function uncheckAll($questionID) {
      $stmt = $this->connection->prepare("UPDATE ".DB_ANSWER_TABLE."
                                          SET
                                          ".COL_ANSWER_CHECKED." = NULL
                                          WHERE ".COL_ANSWER_PARENT." = ?");
      $stmt->bind_param("i", $questionID);
      return $stmt->execute();
    }

    public function updateChecked($answerID) {
      $stmt = $this->connection->prepare("UPDATE ".DB_ANSWER_TABLE."
                                          SET
                                          ".COL_ANSWER_CHECKED." = 1
                                          WHERE ".COL_ANSWER_ID." = ?");
      $stmt->bind_param("i", $answerID);
      return $stmt->execute();
    }

    /**
     * @return deletes a user with given ID
     */ 
    public function deleteUser($userID){
      $stmt = $this->connection->prepare("DELETE FROM ".DB_USER_TABLE." WHERE ".COL_USER_ID." = ?");
      $stmt->bind_param("i", $userID);
      $deletedUser = $stmt->execute();
      return $deletedUser;
    }

    /**
     * @return moves every post from $userID to user with the ID of 0
     */ 
    public function movePost($userID){
      $stmt = $this->connection->prepare("UPDATE ".DB_POST_TABLE." SET ".COL_POST_AUTHOR."= 0 WHERE ".COL_POST_AUTHOR." = ?");
      $stmt->bind_param("i", $userID);
      return $stmt->execute();
    }

    /**
     * @return every user as associative array or null if such doesn't exist
     */
    public function getListOfUsers(){
      $stmt = $this->connection->prepare("SELECT ".COL_USER_FIRSTNAME.", ".COL_USER_LASTNAME.", ".COL_USER_USERNAME.", ".COL_USER_LASTSEEN." FROM ".DB_USER_TABLE);
      $stmt->execute();
      return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
    * @return users with paging
    */
    public function getNthPageUsers($page, $step){
      if($step < 1 || $page < 1){
        return null;
      }
      $start = ($page - 1) * $step;
      $stmt = $this->connection->prepare("SELECT U.".COL_USER_FIRSTNAME.", U.".COL_USER_LASTNAME.", U.".COL_USER_USERNAME.", U.".COL_USER_LASTSEEN.", U.".COL_USER_RANK.", R.".COL_RANK_ID.", R.".COL_RANK_NAME." FROM ".DB_USER_TABLE." U, ".DB_RANK_TABLE." R WHERE U.".COL_USER_RANK."=R.".COL_RANK_ID." ORDER BY U.".COL_USER_RANK." DESC, U.".COL_USER_FIRSTNAME." LIMIT ? , ?");
      $stmt->bind_param("ii", $start, $step);
      $stmt->execute();
      return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
    * @return number of users
    */
    public function getNumberOfUsers() {
      $stmt = $this->connection->prepare("SELECT COUNT(".COL_USER_ID.")
                                          FROM ".DB_USER_TABLE);
      $stmt->execute();
      $result = $stmt->get_result()->fetch_row();
      return $result[0];
    }

    /**
     * @param userID has to be integer
     * @return every post from userID starting from the newest post as associative array or null if such doesn't exist
     */
    public function getPosts($userID){
      $stmt = $this->connection->prepare("SELECT * FROM ".DB_POST_TABLE." WHERE ".COL_POST_AUTHOR." = ? ORDER BY ".COL_POST_POSTED." DESC");
      $stmt->bind_param("i", $userID);
      $stmt->execute();
      return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @param postID has to be integer
     * @return relation between an answer and a question as an associative array or null if such doesn't exist
     */
    public function getRelationFromPost($postID){
      $stmt = $this->connection->prepare("SELECT * FROM ".DB_ANSWER_TABLE." WHERE ".COL_POST_ID." = ?");
      $stmt->bind_param("i", $postID);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * @param questionID has to be integer
     * @return question object as associative array or null if such doesn't exist
     */
    public function getQuestion($questionID) {
      $stmt = $this->connection->prepare("SELECT Q.".COL_QUESTION_ID.", Q.".COL_QUESTION_HEADER.", P.".COL_POST_CONTENT.", P.".COL_POST_POSTED.", U.".COL_USER_USERNAME.", U.".COL_USER_FIRSTNAME.", U.".COL_USER_LASTNAME.", U.".COL_USER_MAJOR.", U.".COL_USER_ENROLLED."
                                          FROM ".DB_QUESTION_TABLE." Q, ".DB_POST_TABLE." P, ".DB_USER_TABLE." U
                                          WHERE Q.".COL_QUESTION_ID." = ? AND Q.".COL_QUESTION_ID." = P.".COL_POST_ID." AND U.".COL_USER_ID." = P.".COL_POST_AUTHOR);
      $stmt->bind_param("i", $questionID);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * @return question suited for page $page and step $step, filtered by $filterType and ordered by $order. The search will be done by $name and $tags parameters (which are 
     * the question's name (doesn't have to be full name) and tags, respectively) if they are specified.
     */
    public function getNthPageQuestions($page, $step, $filterType = "dateOfCreation", $order = "0", $name = "", $tags = "", $category = "0") {
      if($step < 1 || $page < 1)
        return null;

      //Preparation of various variables required for the querries
      $start = ($page - 1) * $step;
      $order = $order == "0" ? "DESC" : "ASC";
      $name = "%".$name."%";
      $tagParams = "";
      $tags = trim($tags);
      $name = trim($name);
      $sqlType = "";
      $sqlData = array();
      $tagsSQL = "";

      // Preparation of the dynamic part of the querry which depends upon the number of tags beaing searched by
      if ($tags != "") {
        $tags = preg_split("/[\s,]+/", $tags);
        $firstTag = true;
        foreach ($tags as $tag) {
          if ($firstTag) {
            $tagParams = "?";
            $firstTag = false;
          }
          else {
            $tagParams = $tagParams.",?";
          }
          $sqlType = $sqlType."s";
          $tag = strtolower($tag);
          $sqlData[] = &$tag;
        }
        $tagsSQL = "AND EXISTS (SELECT *
                                FROM ".DB_POSTTAG_TABLE." PT, ".DB_TAG_TABLE." T
                                WHERE P.".COL_POST_ID." = PT.".COL_POSTTAG_POST." AND PT.".COL_POSTTAG_TAG." = T.".COL_TAG_ID." 
                                AND T.".COL_TAG_NAME." IN (".$tagParams."))";
      }

      $categorySQL = "";
      if ($category != "0") {
        $categorySQL = "AND Q.".COL_QUESTION_CATEGORY." = ? ";
        $sqlType = $sqlType."i";
        $sqlData[] = &$category;
      } 

      // Executing the correct querry specified by the $filterType
      switch ($filterType) {

        //Filtering by author's score
        case "authorScore":
          $nQuestionsScore = "(COALESCE(( SELECT COUNT(*)
                                      FROM ".DB_POST_TABLE." P
                                      WHERE  P.".COL_POST_AUTHOR." = U.".COL_USER_ID." AND P.".COL_POST_TYPE." = 1), 0) * ".SCORE_USER_QUESTIONS_COUNT.")";
          $nAnswersScore = " (COALESCE(( SELECT COUNT(*)
                                     FROM ".DB_POST_TABLE." P
                                     WHERE  P.".COL_POST_AUTHOR." = U.".COL_USER_ID." AND P.".COL_POST_TYPE." = 2), 0) * ".SCORE_USER_ANSWER_COUNT.")";
          $likeQuestionScore = "(COALESCE(( SELECT SUM(R.".COL_REACTION_TYPE.")
                                        FROM ".DB_POST_TABLE." P, ".DB_REACTION_TABLE." R
                                        WHERE  P.".COL_POST_AUTHOR." = U.".COL_USER_ID." AND P.".COL_POST_TYPE." = 1 AND P.".COL_POST_ID." = R.".COL_REACTION_POST." AND R.".COL_REACTION_TYPE." = 1), 0) * ".SCORE_USER_QUESTIONS_LIKE.")";
          $likeAnswerScore = "(COALESCE(( SELECT SUM(R.".COL_REACTION_TYPE.")
                                        FROM ".DB_POST_TABLE." P, ".DB_REACTION_TABLE." R
                                        WHERE  P.".COL_POST_AUTHOR." = U.".COL_USER_ID." AND P.".COL_POST_TYPE." = 2 AND P.".COL_POST_ID." = R.".COL_REACTION_POST." AND R.".COL_REACTION_TYPE." = 1), 0) * ".SCORE_USER_ANSWER_LIKE.")";
          $nAcceptedScore = "(COALESCE(( SELECT COUNT(*)
                                      FROM ".DB_POST_TABLE." P, ".DB_ANSWER_TABLE." A
                                      WHERE  P.".COL_POST_AUTHOR." = U.".COL_USER_ID." AND P.".COL_POST_ID." = A.".COL_ANSWER_ID." AND A.".COL_ANSWER_CHECKED." = 1), 0) * ".SCORE_USER_ANSWER_ACCEPTED.")";
          $dislikeQuestionScore = "(COALESCE(( SELECT SUM(R.".COL_REACTION_TYPE.")
                                            FROM ".DB_POST_TABLE." P, ".DB_REACTION_TABLE." R
                                            WHERE  P.".COL_POST_AUTHOR." = U.".COL_USER_ID." AND P.".COL_POST_TYPE." = 1 AND P.".COL_POST_ID." = R.".COL_REACTION_POST." AND R.".COL_REACTION_TYPE." = -1), 0) * ".SCORE_USER_QUESTIONS_DISLIKE.")";
          $dislikeAnswerScore = "(COALESCE(( SELECT SUM(R.".COL_REACTION_TYPE.")
                                            FROM ".DB_POST_TABLE." P, ".DB_REACTION_TABLE." R
                                            WHERE  P.".COL_POST_AUTHOR." = U.".COL_USER_ID." AND P.".COL_POST_TYPE." = 2 AND P.".COL_POST_ID." = R.".COL_REACTION_POST." AND R.".COL_REACTION_TYPE." = -1), 0) * ".SCORE_USER_ANSWER_DISLIKE.")";
          $nDislikesScore = "(COALESCE(( SELECT SUM(R.".COL_REACTION_TYPE.")
                                            FROM ".DB_REACTION_TABLE." R
                                            WHERE  U.".COL_USER_ID." = R.".COL_REACTION_USER." AND R.".COL_REACTION_TYPE." = -1), 0) * ".SCORE_USER_DISLIKED.")";

          $sql =  "SELECT Q.".COL_QUESTION_ID.", Q.".COL_QUESTION_HEADER.", P.".COL_POST_POSTED.", U.".COL_USER_USERNAME.", ( $nQuestionsScore + $nAnswersScore + $likeQuestionScore              +  $likeAnswerScore + $nAcceptedScore + $dislikeAnswerScore + $dislikeQuestionScore + $nDislikesScore ) AS score
                  FROM ".DB_QUESTION_TABLE." Q, ".DB_POST_TABLE." P, ".DB_USER_TABLE." U
                  WHERE Q.".COL_QUESTION_ID." = P.".COL_POST_ID." AND U.".COL_USER_ID." = P.".COL_POST_AUTHOR." $tagsSQL $categorySQL 
                        AND LOWER(Q.".COL_QUESTION_HEADER.") LIKE LOWER(?)
                  GROUP BY Q.".COL_QUESTION_ID."
                  ORDER BY score
                  LIMIT ?, ?";
          $stmt = $this->connection->prepare($sql);
          $sqlType = $sqlType."sii";
          $sqlData[] = &$name;
          $sqlData[] = &$start;
          $sqlData[] = &$step;
          array_unshift($sqlData, $sqlType);
          call_user_func_array(array($stmt, "bind_param"), $sqlData);
          break;

        //Filtering by question's score
        case "questionScore":
          $sql = "SELECT Q.".COL_QUESTION_ID.", Q.".COL_QUESTION_HEADER.", P.".COL_POST_POSTED.", A.".COL_USER_USERNAME."
                  FROM ".DB_QUESTION_TABLE." Q, ".DB_POST_TABLE." P, ".DB_USER_TABLE." A
                  WHERE Q.".COL_QUESTION_ID." = P.".COL_POST_ID." AND A.".COL_USER_ID." = P.".COL_POST_AUTHOR." $tagsSQL $categorySQL 
                        AND LOWER(Q.".COL_QUESTION_HEADER.") LIKE LOWER(?)
                  GROUP BY Q.".COL_QUESTION_ID."
                  ORDER BY COALESCE((SELECT SUM(R.".COL_REACTION_TYPE.")
                                      FROM ".DB_REACTION_TABLE." R
                                      WHERE P.".COL_POST_ID." = R.".COL_REACTION_POST."
                                      GROUP BY P.".COL_POST_ID."), 0) $order
                  LIMIT ?, ?";
          $stmt = $this->connection->prepare($sql);
          $sqlType = $sqlType."sii";
          $sqlData[] = &$name;
          $sqlData[] = &$start;
          $sqlData[] = &$step;
          array_unshift($sqlData, $sqlType);
          call_user_func_array(array($stmt, "bind_param"), $sqlData);
          break;

        //Filtering by average question's score. Calculated with Laplace smoothing.
        case "averageQuestionScore":
          $sql = "SELECT Q.".COL_QUESTION_ID.", Q.".COL_QUESTION_HEADER.", P.".COL_POST_POSTED.", A.".COL_USER_USERNAME.", 
                          (((SELECT SUM(R.".COL_REACTION_TYPE.")
                                      FROM ".DB_REACTION_TABLE." R
                                      WHERE P.".COL_POST_ID." = R.".COL_REACTION_POST." AND R.".COL_REACTION_TYPE." = 1
                                      GROUP BY P.".COL_POST_ID.") + 1) / ( (SELECT COUNT(*)
                                                                            FROM ".DB_REACTION_TABLE." R
                                                                            WHERE P.".COL_POST_ID." = R.".COL_REACTION_POST."
                                                                            GROUP BY P.".COL_POST_ID.") + 2)) AS orderCondition
                  FROM ".DB_QUESTION_TABLE." Q, ".DB_POST_TABLE." P, ".DB_USER_TABLE." A
                  WHERE Q.".COL_QUESTION_ID." = P.".COL_POST_ID." AND A.".COL_USER_ID." = P.".COL_POST_AUTHOR." $tagsSQL $categorySQL 
                        AND LOWER(Q.".COL_QUESTION_HEADER.") LIKE LOWER(?)
                  GROUP BY Q.".COL_QUESTION_ID."
                  ORDER BY COALESCE(orderCondition, 0) $order
                  LIMIT ?, ?";
          $stmt = $this->connection->prepare($sql);
          $sqlType = $sqlType."sii";
          $sqlData[] = &$name;
          $sqlData[] = &$start;
          $sqlData[] = &$step;
          array_unshift($sqlData, $sqlType);
          call_user_func_array(array($stmt, "bind_param"), $sqlData);
          break;

        //Filtering by number of answers
        case "noOfAnswers":
          $sql = "SELECT Q.".COL_QUESTION_ID.", Q.".COL_QUESTION_HEADER.", P.".COL_POST_POSTED.", A.".COL_USER_USERNAME."
                  FROM ".DB_QUESTION_TABLE." Q, ".DB_POST_TABLE." P, ".DB_USER_TABLE." A
                  WHERE Q.".COL_QUESTION_ID." = P.".COL_POST_ID." AND A.".COL_USER_ID." = P.".COL_POST_AUTHOR." $tagsSQL $categorySQL 
                        AND LOWER(Q.".COL_QUESTION_HEADER.") LIKE LOWER(?)
                  GROUP BY Q.".COL_QUESTION_ID."
                  ORDER BY COALESCE((SELECT COUNT(*)
                                      FROM ".DB_ANSWER_TABLE." AN
                                      WHERE P.".COL_POST_ID." = AN.".COL_ANSWER_PARENT."
                                      GROUP BY P.".COL_POST_ID."), 0) $order
                  LIMIT ?, ?";
          $stmt = $this->connection->prepare($sql);
          $sqlType = $sqlType."sii";
          $sqlData[] = &$name;
          $sqlData[] = &$start;
          $sqlData[] = &$step;
          array_unshift($sqlData, $sqlType);
          call_user_func_array(array($stmt, "bind_param"), $sqlData);
          break;

        //Filtering by date od question's modification
        case "modificationDate":
          $sql = "SELECT Q.".COL_QUESTION_ID.", Q.".COL_QUESTION_HEADER.", P.".COL_POST_POSTED.", A.".COL_USER_USERNAME."
                  FROM ".DB_QUESTION_TABLE." Q, ".DB_POST_TABLE." P, ".DB_USER_TABLE." A
                  WHERE Q.".COL_QUESTION_ID." = P.".COL_POST_ID." AND A.".COL_USER_ID." = P.".COL_POST_AUTHOR." $tagsSQL $categorySQL 
                        AND LOWER(Q.".COL_QUESTION_HEADER.") LIKE LOWER(?)
                  ORDER BY P.".COL_POST_MODIFIED." $order 
                  LIMIT ?, ?";
          $stmt = $this->connection->prepare($sql);
          $sqlType = $sqlType."sii";
          $sqlData[] = &$name;
          $sqlData[] = &$start;
          $sqlData[] = &$step;
          array_unshift($sqlData, $sqlType);
          call_user_func_array(array($stmt, "bind_param"), $sqlData);
          break;
        
        //Filtering by the date of question's posting
        default:
          $sql = "SELECT Q.".COL_QUESTION_ID.", Q.".COL_QUESTION_HEADER.", P.".COL_POST_POSTED.", A.".COL_USER_USERNAME."
                  FROM ".DB_QUESTION_TABLE." Q, ".DB_POST_TABLE." P, ".DB_USER_TABLE." A
                  WHERE Q.".COL_QUESTION_ID." = P.".COL_POST_ID." AND A.".COL_USER_ID." = P.".COL_POST_AUTHOR." $tagsSQL $categorySQL 
                        AND LOWER(Q.".COL_QUESTION_HEADER.") LIKE LOWER(?)
                  ORDER BY P.".COL_POST_POSTED." $order 
                  LIMIT ?, ?";
          $stmt = $this->connection->prepare($sql);
          $sqlType = $sqlType."sii";
          $sqlData[] = &$name;
          $sqlData[] = &$start;
          $sqlData[] = &$step;
          array_unshift($sqlData, $sqlType);
          call_user_func_array(array($stmt, "bind_param"), $sqlData);
          break;
      }
      $stmt->execute();
      return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllCategories() {
      $stmt = $this->connection->prepare("SELECT *
                                          FROM ".DB_CATEGORY_TABLE."
                                          ORDER BY ".COL_CATEGORY_ID);
      $stmt->execute();
      return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
	* @param name of the tag
	* @return tag or NULL if that tag doesn't exist
    */
    public function getTagIDByName($name) {
    	$stmt = $this->connection->prepare("SELECT *
                                          FROM ".DB_TAG_TABLE."
                                          WHERE ".COL_TAG_NAME." = ?");
    	$name = strtolower($name);
    	$stmt->bind_param("s", $name);
    	$stmt->execute();
    	$result = $stmt->get_result()->fetch_row();
    	return $result[0];
    }

    /**
     * Helper function for inserting post, so ids are in same order as posting time
     */
    private function getSmallestAvaliablePostID() {
      $stmt = $this->connection->prepare("SELECT ".COL_POST_ID." FROM ".DB_POST_TABLE." ORDER BY ".COL_POST_ID." DESC LIMIT 1");
      $stmt->execute();
      $result = $stmt->get_result()->fetch_row();
      return !$result ? -214748364 : ($result[0] + 1); // minimum value of mysqls int
    }

    /**
     * @param author user's username as string
     * @param content string
     * @param time (optional) time as string, recommended format "Y-m-d H:i:s"
     * @return ID of inserted post or false if query failed
     */
    public function insertPost($ID, $author, $content, $type) {
      if($type !== POST_TYPE_ANSWER && $type !== POST_TYPE_QUESTION) {
          return false;
      }
      if(($userID = $this->getUserID($author)) === null) {
          return false;
      }
      $stmt = $this->connection->prepare("INSERT INTO ".DB_POST_TABLE."(".COL_POST_ID.", ".COL_POST_CONTENT.", ".COL_POST_AUTHOR.", ".COL_POST_TYPE.") VALUES (?, ?, ?, ?)");
      $stmt->bind_param("isii", $ID, $content, $userID, $type);
      return $stmt->execute();
    }

    /**
     * @return ID of inserted question if insertion was successful, otherwise return false
     */
    public function insertQuestion($author, $header, $content, $category, $tags) {
      $ID = $this->getSmallestAvaliablePostID();
      if($this->insertPost($ID, $author, $content, POST_TYPE_QUESTION) === false)
        return false;
      $stmt = $this->connection->prepare("INSERT INTO ".DB_QUESTION_TABLE."(".COL_QUESTION_ID.", ".COL_QUESTION_HEADER.", ".COL_QUESTION_CATEGORY.") VALUES (?, ?, ?)");
      $stmt->bind_param("isi", $ID, $header, $category);
      $result = $stmt->execute();
      foreach ($tags as $tag) {
      	if ($tag == "")
      		continue;
      	$tagID = $this->getTagIDByName($tag);
      	if (!$tagID) {
      		$this->insertTag($tag);
      		$tagID = $this->getTagIDByName($tag);
      	}
      	$result = $result ? $this->givePostATag($ID, $tagID) : $result ;
      }
      if(!$result)
        $this->deletePost($ID);
      return $result ? $ID : false;
    }

    /**
     * Tries to insert answer into database and returns if query succeeded
     */
    public function insertAnswer($author, $content, $questionID) {
      $ID = $this->getSmallestAvaliablePostID();
      $authorUsername = $this->getUserUsername($author);
      if($this->insertPost($ID, $authorUsername, $content, POST_TYPE_ANSWER) === false)
        return false;
      $stmt = $this->connection->prepare("INSERT INTO ".DB_ANSWER_TABLE."(".COL_ANSWER_ID.", ".COL_ANSWER_PARENT.") VALUES (?, ?)");
      $stmt->bind_param("ii", $ID, $questionID);
      $result = $stmt->execute();
      if ($result) {
        $stmt = $this->connection->prepare("UPDATE ".DB_POST_TABLE."
                                            SET ".COL_POST_MODIFIED." = ? 
                                            WHERE ".COL_POST_ID." = ?");
        $modDate = date("Y-m-d H:i:s", time()+(2*60*60));
        $stmt->bind_param("si", $modDate, $questionID);
        $result = $stmt->execute();
      }
      if(!$result)
        $this->deletePost($ID);
      return $result;
    }

      /**
       * Deletes answer in both post and answer table
       * @param $id
       * @return boolean weather it succeeded
       */
    public function deleteAnswer($id) {
        $stmt1 = $this->connection->prepare("DELETE FROM ".DB_ANSWER_TABLE."
                                          WHERE ".COL_ANSWER_ID." = ? ");
        $stmt1->bind_param("i", $id);
        $deletedInAnswer =  $stmt1->execute();

        $stmt2 = $this->connection->prepare("DELETE FROM ".DB_POST_TABLE."
                                          WHERE ".COL_POST_ID." = ? ");
        $stmt2->bind_param("i", $id);
        $deletedInPost = $stmt2->execute();

        return $deletedInAnswer && $deletedInPost;
    }

    /**
     * @param $name name of taag you want to insert(unique)
     * @return whether insertion was successful or not
     */
    public function insertTag($name) {
      $ID;
      while($this->doesExist(DB_TAG_TABLE, ($ID = rand(-2147483648, 2147483647))));
      $stmt = $this->connection->prepare("INSERT INTO ".DB_TAG_TABLE."(".COL_TAG_ID.", ".COL_TAG_NAME.") VALUES (?, ?)");
      $name = strtolower($name);
      $stmt->bind_param("is", $ID, $name);
      return $stmt->execute();
    }

    /**
     * @param postID of post you want to delete
     * @return bool as success of query
     */
    public function deletePost($postID) {
      // Whole function relies mostly on cascade delete
      $stmt = $this->connection->prepare("SELECT ".COL_ANSWER_ID." FROM ".DB_ANSWER_TABLE." WHERE ".COL_ANSWER_PARENT." = ?");
      $stmt->bind_param("i", $postID);
      $stmt->execute();
      $answers = $stmt->get_result()->fetch_all(MYSQLI_NUM);
      $result = true;
      foreach($answers as $ans) {
          $deletedInPost = $this->connection->query("DELETE FROM " . DB_POST_TABLE . " WHERE " . COL_POST_ID . " = ".$ans[0]);
          $deletedInAnswer = $this->connection->query("DELETE FROM ". DB_ANSWER_TABLE . " WHERE " . COL_ANSWER_ID . " = ".$ans[0]);
          $result = $deletedInPost && $deletedInPost;
          if (!$result) {
            return false;
          }
      }

      $stmt = $this->connection->prepare("DELETE FROM ".DB_POSTTAG_TABLE." 
      																		WHERE ".COL_POSTTAG_POST." = ?");
      $stmt->bind_param("i", $postID);
      $stmt->execute();
      $result = $result ? $stmt->execute() : $result ;

      $stmt = $this->connection->prepare("DELETE FROM ".DB_TAG_TABLE."
      																		WHERE ".COL_TAG_ID." NOT IN (SELECT ".COL_POSTTAG_TAG." 
      																																	FROM ".DB_POSTTAG_TABLE.")");
      $stmt->execute();
      $result = $result ? $stmt->execute() : $result ;

      $post = $this->getQuestion($postID);
      $stmt = $this->connection->prepare("DELETE FROM ".DB_QUESTION_TABLE." 
      																		WHERE ".COL_QUESTION_ID." = ?");
      $stmt->bind_param("i", $postID);
      $stmt->execute();
      $result = $result ? $stmt->execute() : $result ;

      
      $stmt = $this->connection->prepare("DELETE FROM ".DB_POST_TABLE." WHERE ".COL_POST_ID." = ?");
      $stmt->bind_param("i", $postID);
      $result = $result ? $stmt->execute() : $result ;
      return $result;
    }

    /**
     * @param questionID primary key of question
     * @return array of answers associated with given question
     */
    public function getAnswersRelatedToQuestion($questionID, $page = 0, $step = 100) {
      $stmt = $this->connection->prepare("SELECT A.".COL_ANSWER_ID.", A.".COL_ANSWER_ACCEPTED.", P.".COL_POST_ID.", P.".COL_POST_POSTED.", P.".COL_POST_CONTENT.", U.".COL_USER_USERNAME.", U.".COL_USER_FIRSTNAME.", U.".COL_USER_LASTNAME.", U.".COL_USER_MAJOR.", U.".COL_USER_ENROLLED."
                                          FROM ".DB_ANSWER_TABLE." A, ".DB_QUESTION_TABLE." Q, ".DB_POST_TABLE." P, ".DB_USER_TABLE." U
                                          WHERE Q.".COL_QUESTION_ID." = ? AND A.".COL_ANSWER_PARENT." = Q.".COL_QUESTION_ID." AND A.".COL_ANSWER_ID." = P.".COL_ANSWER_ID." AND P.".COL_POST_AUTHOR." = U.".COL_USER_ID."
                                          ORDER BY A.".COL_ANSWER_ID." DESC
                                          LIMIT ?, ?");
      $stmt->bind_param("iii", $questionID, $page, $step);
      $stmt->execute();
      return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @param $postID ID of post whose score you want to get
     */
    public function getPostsScore($postID) {
      $stmt = $this->connection->prepare("SELECT SUM(".COL_REACTION_TYPE.")
                                          FROM ".DB_REACTION_TABLE."
                                          WHERE ".COL_REACTION_POST." = ?");
      $stmt->bind_param("i", $postID);
      $stmt->execute();
      $res = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
      return $res !== null ? (int)$res : 0;
    }
	
	public function getUserLike($userID) {
      $sql =  "SELECT COUNT(R.".COL_REACTION_TYPE.")
                FROM ".DB_REACTION_TABLE." R, ".DB_POST_TABLE." P
                WHERE P.".COL_POST_AUTHOR." = ? AND P.".COL_POST_ID." = R.".COL_REACTION_POST." AND R.".COL_REACTION_TYPE."=+1";
      $stmt = $this->connection->prepare($sql);
      $stmt->bind_param("i", $userID);
      $stmt->execute();
      $res = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
      return $res !== null ? (int)$res : 0;
    }

    public function getUserDislike($userID) {
      $sql =  "SELECT COUNT(R.".COL_REACTION_TYPE.")
                FROM ".DB_REACTION_TABLE." R, ".DB_POST_TABLE." P
                WHERE P.".COL_POST_AUTHOR." = ? AND P.".COL_POST_ID." = R.".COL_REACTION_POST." AND R.".COL_REACTION_TYPE."=-1 ";
      $stmt = $this->connection->prepare($sql);
      $stmt->bind_param("i", $userID);
      $stmt->execute();
      $res = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
      return $res !== null ? (int)$res : 0;
    }

    public function deleteReaction($userID, $postID) {
      $stmt = $this->connection->prepare("DELETE FROM ".DB_REACTION_TABLE." WHERE ".COL_REACTION_USER." = ? AND ".COL_REACTION_POST." = ?");
      $stmt->bind_param("ii", $userID, $postID);  
      $stmt->execute();
    }

    public function getCountQuestion($userID) {
      $sql =  "SELECT COUNT(Q.".COL_QUESTION_ID.")
                FROM ".DB_QUESTION_TABLE." Q, ".DB_POST_TABLE." P
                WHERE P.".COL_POST_AUTHOR." = ? AND P.".COL_POST_ID." = Q.".COL_QUESTION_ID." ";
      $stmt = $this->connection->prepare($sql);
      $stmt->bind_param("i", $userID);
      $stmt->execute();
      $res = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
      return $res !== null ? (int)$res : 0;
    }

    public function getCountAnswer($userID) {
      $sql =  "SELECT COUNT(A.".COL_ANSWER_ID.")
                FROM ".DB_ANSWER_TABLE." A, ".DB_POST_TABLE." P
                WHERE P.".COL_POST_AUTHOR." = ? AND P.".COL_POST_ID." = A.".COL_ANSWER_PARENT." ";
      $stmt = $this->connection->prepare($sql);
      $stmt->bind_param("i", $userID);
      $stmt->execute();
      $res = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
      return $res !== null ? (int)$res : 0;
    }

    
	  public function getPostsReaction($postID, $userID) {
      $stmt = $this->connection->prepare("SELECT R.".COL_REACTION_TYPE."
                                          FROM ".DB_REACTION_TABLE." R
                                          WHERE R.".COL_REACTION_POST." = ? AND R.".COL_REACTION_USER." = ?");
      $stmt->bind_param("ii", $postID, $userID);
      $stmt->execute();
      $res = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
      return $res !== null ? (int)$res : 0;
    }

    public function insertReaction($postID, $userID, $type) {
      $stmt = $this->connection->prepare("INSERT INTO ".DB_REACTION_TABLE."(".COL_REACTION_POST.", ".COL_REACTION_USER.", ".COL_REACTION_TYPE.") VALUES (?, ?, ?)");
      $stmt->bind_param("iii", $postID, $userID, $type);
      return $stmt->execute();
    }

      /**
       * @param $postID
       * @return array which contains user id at zero index
       */
    public function getPostsAuthor($postID) {
      $stmt = $this->connection->prepare("SELECT P.".COL_POST_AUTHOR." FROM ".DB_POST_TABLE." P WHERE ".COL_POST_ID." = ?");
      $stmt->bind_param("i", $postID);
      $stmt->execute();
      return $stmt->get_result()->fetch_row();
    }

    /**
     * @return number of answers associatied with question
     */
     public function countQuestionsAnswers($questionID) {
       $stmt = $this->connection->prepare("SELECT COUNT(1)
                                           FROM ".DB_ANSWER_TABLE."
                                           WHERE ".COL_ANSWER_PARENT." = ?");
       $stmt->bind_param("i", $questionID);
       $stmt->execute();
       $res = $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
       return $res !== null ? (int)$res : 0;
     }

    /**
     * @return all avaliable tags as associative array
     */
    public function getAvaliableTags() {
      return $this->connection->query("SELECT * FROM ".DB_TAG_TABLE)->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Attach tag to post
     */
    public function givePostATag($postID, $tagID) {
      $stmt = $this->connection->prepare("INSERT INTO ".DB_POSTTAG_TABLE."(".COL_POSTTAG_POST.", ".COL_POSTTAG_TAG.")
                                          VALUES (?, ?)");
      $stmt->bind_param("ii", $postID, $tagID);
      return $stmt->execute();
    }

    /**
     * @param questionID primary key of question
     * @return array of tags associated with given question
     */
    public function getTagsRelatedToQuestion($questionID) {
      $stmt = $this->connection->prepare("SELECT T.".COL_TAG_ID.", T.".COL_TAG_NAME."
                                          FROM ".DB_TAG_TABLE." T, ".DB_POSTTAG_TABLE." QT
                                          WHERE QT.".COL_POST_ID." = ? AND QT.".COL_TAG_ID." = T.".COL_TAG_ID);
      $stmt->bind_param("i", $questionID);
      $stmt->execute();
      return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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

    public function updateProfile($ID, $firstName, $lastName, $major, $enrollmentYear, $email, $sex, $dateOfBirth, $biography) {
      $stmt = $this->connection->prepare("UPDATE ".DB_USER_TABLE."
                                          SET
                                          ".COL_USER_FIRSTNAME." = ?,
                                          ".COL_USER_LASTNAME." = ?,
                                          ".COL_USER_SEX." = ?,
                                          ".COL_USER_EMAIL." = ?,
                                          ".COL_USER_MAJOR." = ?,
                                          ".COL_USER_ABOUT." = ?,
                                          ".COL_USER_BIRTHDAY." = ?,
                                          ".COL_USER_ENROLLED." = ?
                                          WHERE ".COL_USER_ID." = ?");
      if (empty($dateOfBirth))
        $dateOfBirth = null;
      $stmt->bind_param("sssssssii", $firstName, $lastName, $sex, $email, $major, $biography, $dateOfBirth, $enrollmentYear, $ID);
      return $stmt->execute();
    }

    public function updateAvatar($ID, $newAvatar){
      $stmt = $this->connection->prepare("UPDATE " .DB_USER_TABLE. "
                                          SET
                                          ".COL_USER_AVATAR." = ?
                                          WHERE ".COL_USER_ID." = ?");
      $stmt->bind_param("si", $newAvatar, $ID);
      return $stmt->execute();
    }

    public function updatePassword($ID, $password) {
      $stmt = $this->connection->prepare("UPDATE ".DB_USER_TABLE."
                                          SET
                                          ".COL_USER_PASSWORD." = ?
                                          WHERE ".COL_USER_ID." = ?");
      $stmt->bind_param("si", $password, $ID);
      return $stmt->execute();
    }

    public function resetPassword($email, $password) {
      $stmt = $this->connection->prepare("UPDATE ".DB_USER_TABLE."
                                          SET
                                          ".COL_USER_PASSWORD." = ?
                                          WHERE ".COL_USER_EMAIL." = ?");
      $stmt->bind_param("ss", $password, $email);
      return $stmt->execute();
    }

    public function getPasswordById($ID) {
      $stmt = $this->connection->prepare("SELECT " .COL_USER_PASSWORD. 
                                          " FROM "
                                          .DB_USER_TABLE."
                                           WHERE ".COL_USER_ID." = ?");
      $stmt->bind_param("i", $ID);
      $stmt->execute();
      $result = $stmt->get_result()->fetch_row();
      return $result[0];
    }

    /**
     * Change content of post with given ID
     */
    private function updatePost($postID, $content) {
      $stmt = $this->connection->prepare("UPDATE ".DB_POST_TABLE."
                                          SET
                                          ".COL_POST_CONTENT." = ?
                                          WHERE ".COL_POST_ID." = ?");
      $stmt->bind_param("si", $content, $postID);
      return $stmt->execute();
    }

    /**
     * Change header and content of question with given ID
     */
    public function updateQuestion($postID, $header, $content) {
      if($this->updatePost($postID, $content) === false)
        return false;
      $stmt = $this->connection->prepare("UPDATE ".DB_QUESTION_TABLE."
                                          SET
                                          ".COL_QUESTION_HEADER." = ?
                                          WHERE ".COL_QUESTION_ID." = ?");
      $stmt->bind_param("si", $header, $postID);
      return $stmt->execute();
    }


    /**
     * Change content of answer with given ID
     */
    public function updateAnswer($postID, $content) {
      return $this->updatePost($postID, $content);
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

    /**
     * Insert rank into database
     */
    public function insertRank($rankID, $rankName) {
      $stmt = $this->connection->prepare("INSERT INTO ".DB_RANK_TABLE."(".COL_RANK_ID.", ".COL_RANK_NAME.") VALUES (?, ?)");
      $stmt->bind_param("is", $rankID, $rankName);
      return $stmt->execute();
    }

    /**
    * Get user rank
    */
    public function getUserRank($userID) {
      $stmt = $this->connection->prepare("SELECT ".COL_USER_RANK." FROM ".DB_USER_TABLE." WHERE ".COL_USER_ID." = ?");
      $stmt->bind_param("i", $userID);
      $stmt->execute();
      return $stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
    }

    /**
     * Promote user to given rank
     * @return boolean successfulness of query
     */
    public function promoteUser($username, $rankID) {
      $stmt = $this->connection->prepare("UPDATE ".DB_USER_TABLE."
                                          SET ".COL_USER_RANK." = ?
                                          WHERE ".COL_USER_USERNAME." = ?");
      $stmt->bind_param("is", $rankID, $username);
      return $stmt->execute();
    }

    /**
     * Insert permission or restriction into database
     */
    public function insertPermissionOrRestriction($permRestID, $permRestName, $type) {
      if($type !== PERMREST_PERMISSION && $type !== PERMREST_RESTRICTION)
        return false;
      $stmt = $this->connection->prepare("INSERT INTO ".DB_PERMREST_TABLE."(".COL_PERMREST_ID.", ".COL_PERMREST_NAME.", ".COL_PERMREST_TYPE.") VALUES (?, ?, ?)");
      $stmt->bind_param("isi", $permRestID, $permRestName, $type);
      return $stmt->execute();
    }

    public function grantRankAPermissionOrRestriction($rankID, $permRestID) {
      $stmt = $this->connection->prepare("INSERT INTO ".DB_RANK_PERMREST_TABLE."(".COL_RANK_PERMREST_RANK.", ".COL_RANK_PERMREST_PERMREST.") VALUES (?, ?)");
      $stmt->bind_param("ii", $rankID, $permRestID);
      return $stmt->execute();
    }

    public function deleteRanksPermissionOrRestriction($rankID, $permRestID) {
      $stmt = $this->connection->prepare("DELETE FROM ".DB_RANK_PERMREST_TABLE."
                                          WHERE ".COL_RANK_PERMREST_RANK." = ? AND ".COL_RANK_PERMREST_PERMREST." = ?");
      $stmt->bind_param("ii", $rankID, $permRestID);
      return $stmt->execute();
    }

    public function getUsersPermissionsOrRestrictions($userID) {
      $stmt = $this->connection->prepare("SELECT ".COL_RANK_PERMREST_PERMREST."
                                          FROM ".DB_USER_TABLE.", ".DB_RANK_PERMREST_TABLE."
                                          WHERE ".COL_USER_ID." = ? AND ".COL_USER_RANK." = ".COL_RANK_PERMREST_RANK);
      $stmt->bind_param("i", $userID);
      $stmt->execute();
      return get_first($stmt->get_result()->fetch_all(MYSQLI_NUM));
    }

    public function givePostADeletionFlag($postID) {
      $stmt = $this->connection->prepare("UPDATE ".DB_POST_TABLE."
                                          SET ".COL_POST_DELETIONFLAG." = now()
                                          WHERE ".COL_POST_ID." = ?");
      $stmt->bind_param("i", $postID);
      return $stmt->execute();
    }

    public function deletePostsDeletionFlag($postID) {
      $stmt = $this->connection->prepare("UPDATE ".DB_POST_TABLE."
                                          SET ".COL_POST_DELETIONFLAG." = null
                                          WHERE ".COL_POST_ID." = ?");
      $stmt->bind_param("i", $postID);
      return $stmt->execute();
    }

    /**
    *	@return returns the number of question in the database
    */
    public function getNumberOfQuestions($category = "0") {
      $cat = $category ? " WHERE ".COL_QUESTION_CATEGORY." = ?" : "";
    	$stmt = $this->connection->prepare("SELECT COUNT(".COL_QUESTION_ID.")
                                          FROM ".DB_QUESTION_TABLE.$cat);
      if ($category) $stmt->bind_param("s", $category);
    	$stmt->execute();
    	$result = $stmt->get_result()->fetch_row();
      return $result[0];
    }

      /**
       * @param $authorID
       * @param $rankID
       * @return bool
       */
    public function updateRank($authorID, $rankID) {
        $stmt = $this->connection->prepare("UPDATE ".DB_USER_TABLE."
                                          SET
                                          ".COL_USER_RANK." = ?
                                          WHERE ".COL_USER_ID." = ?");
        $stmt->bind_param("ii", $rankID, $authorID);
        return $stmt->execute();
    }
    
    function getTopActiveUsers() {
      $sql_post = "SELECT ".COL_POST_AUTHOR.", COUNT(".COL_POST_AUTHOR.") AS count_msg FROM "
                  .DB_POST_TABLE." WHERE NOT ".COL_POST_AUTHOR." = 0 AND PostingTime > DATE_SUB(CURDATE(), INTERVAL 1 WEEK) GROUP BY ".COL_POST_AUTHOR." ORDER BY count_msg DESC LIMIT 5";

      $stmt = $this->connection->prepare($sql_post);
      $stmt->execute();
      $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
      return $result;
    }

    function getUsernameById($id) {
      if(!$id)
        return null;
      $stmt = $this->connection->prepare("SELECT ".COL_USER_USERNAME." FROM ".DB_USER_TABLE." WHERE ".COL_USER_ID." = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function getPopularCategory() {
      $sql_post = "SELECT ".COL_QUESTION_CATEGORY.", COUNT(".COL_QUESTION_CATEGORY.") AS count_cat FROM "
                  .DB_QUESTION_TABLE." GROUP BY ".COL_QUESTION_CATEGORY." ORDER BY count_cat DESC LIMIT 5";

      $stmt = $this->connection->prepare($sql_post);
      $stmt->execute();
      $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
      return $result;
    }

    function getCategoryNameById($id) {
      if(!$id)
        return null;
      $stmt = $this->connection->prepare("SELECT ".COL_CATEGORY_NAME." FROM ".DB_CATEGORY_TABLE." WHERE ".COL_CATEGORY_ID." = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function getUserScore($id) {
      $nQuestionsScore = "(COALESCE(( SELECT COUNT(*)
                                      FROM ".DB_POST_TABLE." P
                                      WHERE  P.".COL_POST_AUTHOR." = U.".COL_USER_ID." AND P.".COL_POST_TYPE." = 1), 0) * ".SCORE_USER_QUESTIONS_COUNT.")";
      $nAnswersScore = " (COALESCE(( SELECT COUNT(*)
                                     FROM ".DB_POST_TABLE." P
                                     WHERE  P.".COL_POST_AUTHOR." = U.".COL_USER_ID." AND P.".COL_POST_TYPE." = 2), 0) * ".SCORE_USER_ANSWER_COUNT.")";
      $likeQuestionScore = "(COALESCE(( SELECT SUM(R.".COL_REACTION_TYPE.")
                                        FROM ".DB_POST_TABLE." P, ".DB_REACTION_TABLE." R
                                        WHERE  P.".COL_POST_AUTHOR." = U.".COL_USER_ID." AND P.".COL_POST_TYPE." = 1 AND P.".COL_POST_ID." = R.".COL_REACTION_POST." AND R.".COL_REACTION_TYPE." = 1), 0) * ".SCORE_USER_QUESTIONS_LIKE.")";
      $likeAnswerScore = "(COALESCE(( SELECT SUM(R.".COL_REACTION_TYPE.")
                                        FROM ".DB_POST_TABLE." P, ".DB_REACTION_TABLE." R
                                        WHERE  P.".COL_POST_AUTHOR." = U.".COL_USER_ID." AND P.".COL_POST_TYPE." = 2 AND P.".COL_POST_ID." = R.".COL_REACTION_POST." AND R.".COL_REACTION_TYPE." = 1), 0) * ".SCORE_USER_ANSWER_LIKE.")";
      $nAcceptedScore = "(COALESCE(( SELECT COUNT(*)
                                      FROM ".DB_POST_TABLE." P, ".DB_ANSWER_TABLE." A
                                      WHERE  P.".COL_POST_AUTHOR." = U.".COL_USER_ID." AND P.".COL_POST_ID." = A.".COL_ANSWER_ID." AND A.".COL_ANSWER_CHECKED." = 1), 0) * ".SCORE_USER_ANSWER_ACCEPTED.")";
      $dislikeQuestionScore = "(COALESCE(( SELECT SUM(R.".COL_REACTION_TYPE.")
                                            FROM ".DB_POST_TABLE." P, ".DB_REACTION_TABLE." R
                                            WHERE  P.".COL_POST_AUTHOR." = U.".COL_USER_ID." AND P.".COL_POST_TYPE." = 1 AND P.".COL_POST_ID." = R.".COL_REACTION_POST." AND R.".COL_REACTION_TYPE." = -1), 0) * ".SCORE_USER_QUESTIONS_DISLIKE.")";
      $dislikeAnswerScore = "(COALESCE(( SELECT SUM(R.".COL_REACTION_TYPE.")
                                            FROM ".DB_POST_TABLE." P, ".DB_REACTION_TABLE." R
                                            WHERE  P.".COL_POST_AUTHOR." = U.".COL_USER_ID." AND P.".COL_POST_TYPE." = 2 AND P.".COL_POST_ID." = R.".COL_REACTION_POST." AND R.".COL_REACTION_TYPE." = -1), 0) * ".SCORE_USER_ANSWER_DISLIKE.")";
      $nDislikesScore = "(COALESCE(( SELECT SUM(R.".COL_REACTION_TYPE.")
                                            FROM ".DB_REACTION_TABLE." R
                                            WHERE  U.".COL_USER_ID." = R.".COL_REACTION_USER." AND R.".COL_REACTION_TYPE." = -1), 0) * ".SCORE_USER_DISLIKED.")";
      $stmt = $this->connection->prepare("SELECT ( $nQuestionsScore + $nAnswersScore + $likeQuestionScore + $likeAnswerScore + $nAcceptedScore + $dislikeAnswerScore +
                                                    $dislikeQuestionScore + $nDislikesScore ) AS score 
                                          FROM ".DB_USER_TABLE." u 
                                          WHERE u.".COL_USER_ID." = ?");
      $stmt->bind_param("s", $id);
      $stmt->execute();
      $result = $stmt->get_result()->fetch_row();
      return $result[0];
    }
  }
?>
