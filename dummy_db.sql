USE pmfoverflow;

INSERT INTO User(username, password, fullname, avatar, email, major, enrollmentyear) VALUES ("perica", "nest@#!@#!@", "Pera Peric", "nesto.jpg", "perica@gmail.com", "Racunarske nauke", 2017);
INSERT INTO User(username, password, fullname, avatar, email, major, enrollmentyear) VALUES ("staraR", "nest@#!@#!@", "Stara Rosa", "nesto.jpg", "rosa@gmail.com", "Informacione tehnologije", 2016);

INSERT INTO Tag(name) VALUES("PHP");
INSERT INTO Tag(name) VALUES("HTML");
INSERT INTO Tag(name) VALUES("Math");
INSERT INTO Tag(name) VALUES("Javascript");
INSERT INTO Tag(name) VALUES("CSS");
INSERT INTO Tag(name) VALUES("Big Data");

INSERT INTO Post(Header, Content, PostingTime, Author) VALUES ("How to use Dictionary in Swift?
", "I created Dictionary like below, and I'd like to make a tableView using this book list.", DATE("2017-06-15 09:34:21"), 1);
INSERT INTO Post(Header, Content, PostingTime, Author) VALUES ("Value For Dict - Python", "I have a code to get a specific value from yahoo API. The problem is it matched the IF statement but it returns None and also again its going to the else loop for some reason. Can some one help me out pls. Very new to python.", DATE("2017-01-05 10:23:15"), 1);

INSERT INTO PostTags VALUES (1, 1);
INSERT INTO PostTags VALUES (1, 6);
INSERT INTO PostTags VALUES (2, 3);
INSERT INTO PostTags VALUES (2, 4);