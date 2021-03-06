<?php
  define('DB_USER_TABLE', 'User');
  define('DB_POST_TABLE', 'Post');
  define('DB_QUESTION_TABLE', 'Question');
  define('DB_ANSWER_TABLE', 'Answer');
  define('DB_POSTTAG_TABLE', 'Tagged');
  define('DB_TAG_TABLE', 'Tag');
  define('DB_REACTION_TABLE', 'Reaction');
  define('DB_RANK_TABLE', 'Rank');
  define('DB_PERMREST_TABLE', 'PermRest');
  define('DB_RANK_PERMREST_TABLE', 'HasPermRest');
  define('DB_CATEGORY_TABLE', 'Category');

  define('COL_USER_ID', 'UserID');
  define('COL_USER_USERNAME', 'Username');
  define('COL_USER_PASSWORD', 'Password');
  define('COL_USER_HASH', 'HashActivation');
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
  define('COL_USER_LASTSEEN', 'LastTimeSeen');
  define('COL_USER_RANK', 'BelongsTo');

  define('COL_POST_ID', 'PostID');
  define('COL_POST_CONTENT', 'Content');
  define('COL_POST_POSTED', 'PostingTime');
  define('COL_POST_MODIFIED', 'ModificationTime');
  define('COL_POST_DELETIONFLAG', 'Deleted');
  define('COL_POST_AUTHOR', 'Author');
  define('COL_POST_TYPE', 'Type');

  define('COL_REACTION_USER', 'UserID');
  define('COL_REACTION_POST', 'PostID');
  define('COL_REACTION_TYPE', 'Type');

  define('COL_QUESTION_ID', 'PostID');
  define('COL_QUESTION_HEADER', 'Header');
  define('COL_QUESTION_CATEGORY', 'CategoryID');

  define('COL_ANSWER_ID', 'PostID');
  define('COL_ANSWER_PARENT', 'RelatedTo');
  define('COL_ANSWER_ACCEPTED', 'Accepted');
  define('COL_ANSWER_CHECKED', 'Checked');

  define('COL_TAG_ID', 'TagID');
  define('COL_TAG_NAME', 'Name');

  define('COL_POSTTAG_POST', 'PostID');
  define('COL_POSTTAG_TAG', 'TagID');

  define('COL_RANK_ID', 'RankID');
  define('COL_RANK_NAME', 'Name');

  define('COL_PERMREST_ID', 'PermRestID');
  define('COL_PERMREST_NAME', 'Name');
  define('COL_PERMREST_TYPE', 'Type');
  define('PERMREST_PERMISSION', 1);
  define('PERMREST_RESTRICTION', 2);

  define('COL_RANK_PERMREST_RANK', 'RankID');
  define('COL_RANK_PERMREST_PERMREST', 'PermRestID');

  define('COL_CATEGORY_ID','CategoryID');
  define('COL_CATEGORY_NAME', 'Name');

  // REACTIONS
  define('REACTION_LIKE', 1);
  define('REACTION_DISLIKE', -1);
  define('REACTION_NONE', 0);

  // POST TYPES
  define('POST_TYPE_QUESTION', 1);
  define('POST_TYPE_ANSWER', 2);

  // USER GETTER
  define('USER_GETTER_ALL', 9900);
  define('USER_GETTER_AUTHENTICATION', 9901);
  define('USER_GETTER_LOGIN_DATA', 9902);

  // RANKS
  define('RANK_NOT_ACTIVATED', 0);
  define('RANK_ACTIVATED', 1);
  define('RANK_BANNED', 2);
  define('RANK_ADMIN', 3);

  //USER SCORE MODIFIERS (negative values for dislikes are defined as positive here)
  define('SCORE_USER_QUESTIONS_COUNT', 5);
  define('SCORE_USER_QUESTIONS_LIKE', 5);
  define('SCORE_USER_QUESTIONS_DISLIKE', 2);
  define('SCORE_USER_ANSWER_COUNT', 10);
  define('SCORE_USER_ANSWER_LIKE', 10);
  define('SCORE_USER_ANSWER_ACCEPTED', 15);
  define('SCORE_USER_ANSWER_DISLIKE', 2);
  define('SCORE_USER_DISLIKED', 1);
?>
