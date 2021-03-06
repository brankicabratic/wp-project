CREATE DATABASE pmfoverflow;
USE pmfoverflow;

-- DA BI SE DODAVALI KORISNICI MORA SE DODATI BAR RANK SA ID-om 0 koji predstavlja "Neverifikovanog korisnika"

-- RANKS
CREATE TABLE Rank(
	RankID int NOT NULL,
	Name varchar(50) NOT NULL UNIQUE,
	PRIMARY KEY(RankID)
) CHARACTER SET utf8 COLLATE utf8_bin;

-- PERMISSIONS AND RESTRICTIONS
CREATE TABLE PermRest(
	PermRestID int NOT NULL,
	Name varchar(200) NOT NULL,
	Type tinyint(5) NOT NULL,
	PRIMARY KEY(PermRestID)
) CHARACTER SET utf8 COLLATE utf8_bin;

-- CONNECTS RANKS AND PERMISSIONS
CREATE TABLE HasPermRest(
	RankID int NOT NULL,
	PermRestID int NOT NULL,
	PRIMARY KEY(RankID, PermRestID),
	FOREIGN KEY(RankID) REFERENCES Rank(RankID) ON DELETE CASCADE,
	FOREIGN KEY(PermRestID) REFERENCES PermRest(PermRestID) ON DELETE CASCADE
) CHARACTER SET utf8 COLLATE utf8_bin;

-- USERS
CREATE TABLE User(
	UserID int NOT NULL,
	Username varchar(32) NOT NULL UNIQUE,
	FirstName varchar(100) DEFAULT "" NOT NULL,
	LastName varchar(100) DEFAULT "" NOT NULL,
	Password varchar(500) NOT NULL,
	HashActivation varchar(500) NOT NULL,
	Sex varchar(1) DEFAULT NULL,
	Avatar varchar(1000) DEFAULT NULL,
	Email varchar(100) NOT NULL,
	Major varchar(50) DEFAULT NULL,
	About varchar(10000) DEFAULT NULL,
	EnrollmentYear int,
	BelongsTo int DEFAULT NULL,
	LastTimeSeen datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
	DateOfBirth date DEFAULT NULL,
	RegistrationTime datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
	PRIMARY KEY(UserID),
	FOREIGN KEY(BelongsTo) REFERENCES Rank(RankID)
) CHARACTER SET utf8 COLLATE utf8_bin;

-- POSTS
CREATE TABLE Post(
	PostID int NOT NULL,
	Content text NOT NULL,
	PostingTime datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
	ModificationTime datetime ON UPDATE CURRENT_TIMESTAMP DEFAULT NULL,
	Deleted datetime DEFAULT NULL,
	Author int,
	Type int NOT NULL,
	PRIMARY KEY(PostID),
	FOREIGN KEY(Author) REFERENCES User(UserID) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_bin;

-- REACTIONS
CREATE TABLE Reaction(
	UserID int NOT NULL,
	PostID int NOT NULL,
	-- Like +1, dislike -1
	Type tinyint(1) NOT NULL,
	PRIMARY KEY(UserID, PostID),
	FOREIGN KEY(UserID) REFERENCES User(UserID),
	FOREIGN KEY(PostID) REFERENCES Post(PostID)
) CHARACTER SET utf8 COLLATE utf8_bin;

-- QUESTIONS
CREATE TABLE Question(
	PostID int NOT NULL,
	Header varchar(200) NOT NULL,
	CategoryID int NOT NULL,
	PRIMARY KEY(PostID),
	FOREIGN KEY(PostID) REFERENCES Post(PostID),
	FOREIGN KEY(CategoryID) REFERENCES Category(CategoryID)
) CHARACTER SET utf8 COLLATE utf8_bin;

-- ANSWERS
CREATE TABLE Answer(
	PostID int NOT NULL,
	RelatedTo int NOT NULL,
	Accepted tinyint(1) DEFAULT NULL, -- TODO: LOOK FOR BETTER SOLUTION
	Checked int DEFAULT NULL,
	PRIMARY KEY(PostID),
	FOREIGN KEY(PostID) REFERENCES Post(PostID),
	FOREIGN KEY(RelatedTo) REFERENCES Question(PostID)
) CHARACTER SET utf8 COLLATE utf8_bin;

-- TAGS
CREATE TABLE Tag(
	TagID int NOT NULL,
	Name varchar(35) NOT NULL UNIQUE,
	PRIMARY KEY(TagID)
) CHARACTER SET utf8 COLLATE utf8_bin;

-- TABLE THAT CONNECTS TAGS AND POSTS
CREATE TABLE Tagged(
	PostID int NOT NULL,
	TagID int NOT NULL,
	PRIMARY KEY(PostID, TagID),
	FOREIGN KEY(PostID) REFERENCES Question(PostID),
	FOREIGN KEY(TagID) REFERENCES Tag(TagID)
) CHARACTER SET utf8 COLLATE utf8_bin;

-- REQUESTS(VERIFY ACCOUNT, RESET PASSWORD)
-- Funkcionalnost za request-ove jos nije ugradjena u db_utils
CREATE TABLE Request(
	ID varchar(50) NOT NULL,
	UserID int NOT NULL,
	Type int NOT NULL,
	PRIMARY KEY(ID),
	FOREIGN KEY(UserID) REFERENCES User(UserID)
) CHARACTER SET utf8 COLLATE utf8_bin;

CREATE TABLE Category(
	CategoryID int NOT NULL,
	Name varchar(50) NOT NULL UNIQUE,
	PRIMARY KEY(CategoryID)
) CHARACTER SET utf8 COLLATE utf8_bin;