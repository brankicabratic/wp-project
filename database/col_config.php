<?php
  define('DB_USER_TABLE', 'User');
  define('DB_POST_TABLE', 'Post');
  define('DB_QUESTION_TABLE', 'Question');
  define('DB_ANSWER_TABLE', 'Answer');
  define('DB_POSTTAG_TABLE', 'Tagged');
  define('DB_TAG_TABLE', 'Tag');
  define('DB_REACTION_TABLE', 'Reaction');

  define('COL_USER_ID', 'UserID');
  define('COL_USER_USERNAME', 'Username');
  define('COL_USER_PASSWORD', 'Password');
  define('COL_USER_FIRSTNAME', 'FirstName');
  define('COL_USER_LASTNAME', 'LastName');
  define('COL_USER_SEX', 'Sex');
  define('SEX_MALE', 'M');
  define('SEX_FEMALE', 'F');
  define('COL_USER_AVATAR', 'Avatar');
  define('COL_USER_EMAIL', 'Email');
  define('COL_USER_MAJOR', 'Major');
  define('COL_USER_ABOUT', 'About');
  define('COL_USER_BIRTHDAY', 'DateOfBirth');
  define('COL_USER_REGISTERED', 'RegistrationTime');
  define('COL_USER_ENROLLED', 'EnrollmentYear');

  define('COL_POST_ID', 'PostID');
  define('COL_POST_CONTENT', 'Content');
  define('COL_POST_POSTED', 'PostingTime');
  define('COL_POST_MODIFIED', 'ModificationTime');
  define('COL_POST_AUTHOR', 'Author');

  define('COL_REACTION_USER', 'UserID');
  define('COL_REACTION_POST', 'PostID');
  define('COL_REACTION_TYPE', 'Type');

  define('COL_QUESTION_ID', 'PostID');
  define('COL_QUESTION_HEADER', 'Header');

  define('COL_ANSWER_ID', 'PostID');
  define('COL_ANSWER_PARENT', 'RelatedTo');
  define('COL_ANSWER_ACCEPTED', 'Accepted');

  define('COL_TAG_ID', 'TagID');
  define('COL_TAG_NAME', 'Name');

  define('COL_POSTTAG_POST', 'PostID');
  define('COL_POSTTAG_TAG', 'TagID');

  // REACTIONS
  define('REACTION_LIKE', 1);
  define('REACTION_DISLIKE', -1);
  define('REACTION_NONE', 0);
?>
