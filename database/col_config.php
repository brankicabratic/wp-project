<?php
  define('DB_USER_TABLE', 'User');
  define('DB_POST_TABLE', 'Post');
  define('DB_QUESTION_TABLE', 'Question');
  define('DB_ANSWER_TABLE', 'Answer');
  define('DB_POSTTAG_TABLE', 'Tagged');
  define('DB_TAG_TABLE', 'Tag');

  define('COL_USER_ID', 'UserID');
  define('COL_USER_USERNAME', 'Username');
  define('COL_USER_PASSWORD', 'Password');
  define('COL_USER_NAME', 'FullName');
  define('COL_USER_AVATAR', 'Avatar');
  define('COL_USER_EMAIL', 'Email');
  define('COL_USER_MAJOR', 'Major');
  define('COL_USER_ENROLLED', 'EnrollmentYear');

  define('COL_POST_ID', 'PostID');
  define('COL_POST_AUTHOR', 'Author');
  define('COL_POST_TIME', 'PostTime');
  define('COL_POST_CONTENT', 'Content');

  define('COL_QUESTION_ID', 'PostID');
  define('COL_QUESTION_HEADER', 'Header');

  define('COL_TAG_NAME', 'Name');
  define('COL_TAG_ID', 'TagID');

  define('COL_ANSWER_ID', 'PostID');
  define('COL_ANSWER_PARENT', 'RelatedTo');
  define('COL_ANSWER_ACCEPTED', 'Accepted');

  define('COL_POSTTAG_POSTID', 'PostID');
?>
