CREATE DATABASE pmfoverflow;
USE pmfoverflow;

-- USERS
CREATE TABLE User(
  UserID int NOT NULL AUTO_INCREMENT,
  FullName varchar(100) NOT NULL,
  Username varchar(32) NOT NULL,
  Password varchar(500) NOT NULL,
  Avatar varchar(1000) NOT NULL,
  Email varchar(100) NOT NULL,
  Major varchar(50) NOT NULL,
  EnrollmentYear int NOT NULL,
  PRIMARY KEY(UserID)
);

-- TAGS
CREATE TABLE Tag(
  TagID int NOT NULL AUTO_INCREMENT,
  NAME varchar(35) NOT NULL,
  PRIMARY KEY(TagID)
);

-- POSTS
CREATE TABLE Post(
  PostID int NOT NULL AUTO_INCREMENT,
  Header varchar(200) NOT NULL,
  Content text NOT NULL,
  PostingTime datetime NOT NULL,
  Author int NOT NULL,
  PRIMARY KEY(PostID),
  FOREIGN KEY(Author) REFERENCES User(UserID)
);

-- ANSWERS
CREATE TABLE Answer(
  AnswerID int NOT NULL AUTO_INCREMENT,
  Parent int NOT NULL,
  Content text NOT NULL,
  PostingTime datetime NOT NULL,
  Author int NOT NULL,
  PRIMARY KEY(AnswerID),
  FOREIGN KEY(Parent) REFERENCES Post(PostID),
  FOREIGN KEY(Author) REFERENCES User(UserID)
);

-- CONNECTS POSTS AND THEIR TAGS
CREATE TABLE PostTags(
  PostID int NOT NULL,
  TagID int NOT NULL,
  PRIMARY KEY(PostID, TagID),
  FOREIGN KEY(PostID) REFERENCES Post(PostID),
  FOREIGN KEY(TagID) REFERENCES Tag(TagID)
);

-- POSTS SCORES
CREATE TABLE Score(
  PostID int NOT NULL,
  UserID int NOT NULL,
  -- TYPES
  -- 1  UP VOTE
  -- 0 DOWN VOTE
  Type tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY(PostID, UserID),
  FOREIGN KEY(PostID) REFERENCES Post(PostID),
  FOREIGN KEY(UserID) REFERENCES User(UserID)
);

DROP DATABASE pmfoverflow; -- DEBUGGING
