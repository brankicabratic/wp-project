CREATE DATABASE pmfoverflow;
USE pmfoverflow;
CREATE TABLE User(
  UserID int NOT NULL,
  FullName varchar(100) NOT NULL,
  Username varchar(32) NOT NULL,
  Password varchar(500) NOT NULL,
  Avatar varchar(1000) NOT NULL,
  Email varchar(100) NOT NULL,
  PRIMARY KEY(UserID)
);
CREATE TABLE Tag(
  TagID int NOT NULL,
  NAME varchar(35) NOT NULL,
  PRIMARY KEY(TagID)
);
CREATE TABLE Post(
  PostID int NOT NULL,
  Header varchar(200) NOT NULL,
  Content text NOT NULL,
  UserID int NOT NULL,
  PRIMARY KEY(PostID),
  FOREIGN KEY(UserID) REFERENCES User(UserID)
);

DROP DATABASE pmfoverflow; -- DEBUGGING
