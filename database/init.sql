CREATE DATABASE pmfoverflow;
USE pmfoverflow;

-- USERS
CREATE TABLE User(
	UserID int NOT NULL,
	Username varchar(32) NOT NULL UNIQUE,
	FirstName varchar(100) NOT NULL,
	LastName varchar(100) NOT NULL,
	Password varchar(500) NOT NULL,
	Sex varchar(1) DEFAULT NULL,
	Avatar varchar(1000) DEFAULT NULL,
	Email varchar(100) NOT NULL,
	Major varchar(50) DEFAULT NULL,
	About varchar(2000) DEFAULT NULL,
	EnrollmentYear int,
	DateOfBirth date DEFAULT NULL,
	RegistrationTime datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(UserID)
);

-- POSTS
CREATE TABLE Post(
	PostID int NOT NULL,
	Content text NOT NULL,
	PostingTime datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
	ModificationTime datetime ON UPDATE CURRENT_TIMESTAMP DEFAULT NULL,
	Author int NOT NULL,
	PRIMARY KEY(PostID),
	FOREIGN KEY(Author) REFERENCES User(UserID)
);

-- REACTIONS
CREATE TABLE Reaction(
	UserID int NOT NULL,
	PostID int NOT NULL,
	-- Like +1, dislike -1
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
	Accepted tinyint(1) DEFAULT NULL, -- TODO: LOOK FOR BETTER SOLUTION
	PRIMARY KEY(PostID),
	FOREIGN KEY(RelatedTo) REFERENCES Question(PostID)
);

-- TAGS
CREATE TABLE Tag(
	TagID int NOT NULL,
	Name varchar(35) NOT NULL UNIQUE,
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
