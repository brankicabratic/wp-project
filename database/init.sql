CREATE DATABASE pmfoverflow;
USE pmfoverflow;

-- USERS
CREATE TABLE User(
	UserID int NOT NULL AUTO_INCREMENT,
	Username varchar(32) NOT NULL UNIQUE,
	FullName varchar(100) NOT NULL,
	Password varchar(500) NOT NULL,
	Avatar varchar(1000) NOT NULL,
	Email varchar(100) NOT NULL,
	Major varchar(50) NOT NULL,
	EnrollmentYear int NOT NULL,
	PRIMARY KEY(UserID)
);

-- POSTS
CREATE TABLE Post(
	PostID int NOT NULL AUTO_INCREMENT,
	Content text NOT NULL,
	PostTime datetime NOT NULL,
	Author int NOT NULL,
	PRIMARY KEY(PostID),
	FOREIGN KEY(Author) REFERENCES User(UserID)
);

-- REACTIONS
CREATE TABLE Reaction(
	UserID int NOT NULL,
	PostID int NOT NULL,
	Type tinyint(1) NOT NULL,
	PRIMARY KEY(UserID, PostID),
	FOREIGN KEY(UserID) REFERENCES User(UserID),
	FOREIGN KEY(PostID) REFERENCES Post(PostID)
);

-- QUESTIONS
CREATE TABLE Question(
	PostID int NOT NULL,
	Header varchar(200) NOT NULL,
	PRIMARY KEY(PostID),
	FOREIGN KEY(PostID) REFERENCES Post(PostID)
);

-- ANSWERS
CREATE TABLE Answer(
	PostID int NOT NULL,
	RelatedTo int NOT NULL,
	Accepted tinyint(1),
	PRIMARY KEY(PostID),
	FOREIGN KEY(RelatedTo) REFERENCES Question(PostID)
);

-- TAGS
CREATE TABLE Tag(
	TagID int NOT NULL AUTO_INCREMENT,
	NAME varchar(35) NOT NULL,
	PRIMARY KEY(TagID)
);

-- TABLE THAT CONNECTS TAGS AND POSTS
CREATE TABLE Tagged(
	PostID int NOT NULL,
	TagID int NOT NULL,
	PRIMARY KEY(PostID, TagID),
	FOREIGN KEY(PostID) REFERENCES Question(PostID),
	FOREIGN KEY(TagID) REFERENCES Tag(TagID)
);
