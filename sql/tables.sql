-- Drop any if already present
drop table if exists Likes CASCADE;
drop table if exists Answer CASCADE;
drop table if exists Question CASCADE;
drop table if exists Users CASCADE;
drop table if exists Topic CASCADE;

-- Create the Tables

CREATE TABLE Users(
	uid INTEGER auto_increment PRIMARY KEY, 
	firstname VARCHAR(50) NOT NULL ,
	lastname VARCHAR(50) NOT NULL,
    username VARCHAR (50) NOT NULL,
	email VARCHAR(50) NOT NULL, 
	password VARCHAR(100) NOT NULL,
	city VARCHAR(30) NOT NULL,
	state VARCHAR(20) NOT NULL, 
	country VARCHAR(30) NOT NULL,
	personal_profile VARCHAR(200),
    level VARCHAR (20),
	affilation VARCHAR(30)
);


-- Topic
CREATE TABLE Topic(
	tid INTEGER auto_increment PRIMARY KEY,
    tname VARCHAR(20) NOT NULL,
	stname VARCHAR(20) NOT NULL
);


-- Question
CREATE TABLE Question(
  qid INTEGER auto_increment PRIMARY KEY,
  asker INTEGER NOT NULL, 
  tid INTEGER NOT NULL,
  title VARCHAR(100) NOT NULL,
  qbody VARCHAR(200) NOT NULL,
  questiondate DATETIME NOT NULL,
  qstatus VARCHAR(10) NOT NULL,
  FOREIGN KEY (asker) REFERENCES Users(uid),
  FOREIGN KEY (tid) REFERENCES Topic(tid)
);

-- Answer
CREATE TABLE Answer(
  qid INTEGER NOT NULL,
  anum INTEGER NOT NULL,
  answerer INTEGER NOT NULL,
  abody VARCHAR(240) NOT NULL,
  answerdate DATETIME NOT NULL,
  bestanswer BOOL,
  numlikes INTEGER,
  PRIMARY KEY (qid, anum),
  FOREIGN KEY (answerer) REFERENCES Users(uid),
  FOREIGN KEY (qid) REFERENCES Question(qid)
);


-- Likes
CREATE TABLE Likes(
  uid Integer NOT NULL,
  qid Integer NOT NULL,
  anum INTEGER NOT NULL,
  PRIMARY KEY (uid, qid, anum),
  FOREIGN KEY (uid) REFERENCES Users(uid),
  FOREIGN KEY (qid,anum) REFERENCES Answer(qid,anum)
);


SET SQL_SAFE_UPDATES = 0;
Drop Trigger if exists update_likes
DELIMITER $$
CREATE TRIGGER update_likes
AFTER INSERT ON LIKES
FOR EACH ROW
BEGIN
	UPDATE Answer
		SET numlikes = numlikes + 1
	WHERE NEW.qid = qid and NEW.anum = anum;
	UPDATE Answer Left Join MaxLikes on Answer.qid = MaxLikes.qid
		SET bestanswer = 
			Case When Answer.anum = an  and numlikes = ML and numlikes > 0 then True
				 Else False
			End;
END $$


Drop Trigger if exists update_status;
DELIMITER $$
CREATE TRIGGER update_status
AFTER INSERT ON Answer
FOR EACH ROW
BEGIN
	UPDATE Question
		SET qstatus = "Answered"
        Where New.qid = Question.qid and qstatus != "Answered" ;
END $$

DELIMITER ;

Drop Trigger if exists same_answerer
DELIMITER $$
CREATE TRIGGER same_answerer
Before INSERT ON Answer
FOR EACH ROW
BEGIN
	if NEW.answerer in (Select answerer From Answer Where New.qid = Answer.qid) Then 
		SIGNAL SQLSTATE '45000' 
		SET MESSAGE_TEXT = "You can't answer the same question twice";
	end if;
END $$


Drop Trigger if exists same_like
DELIMITER $$
CREATE TRIGGER same_like
Before INSERT ON Likes
FOR EACH ROW
BEGIN
	if (NEW.uid = (Select answerer From Answer Where New.qid = Answer.qid and New.anum = Answer.anum)) Then 
		SIGNAL SQLSTATE '45000' 
		SET MESSAGE_TEXT = "You can't like your own answer";
	end if;
END $$

DELIMITER ;

Call Add_User('Alex','Jhonson','AlexJ','happywee@gmail.com','qwertyuio*','Brooklyn','New York','United States','senior student','New York University');
Call Add_User('Jennifer','Miller','Jenflower','Jen85430@nyu.edu','jenMiller430','Philadelphia','Pennsylvania','United States','Software Developer and Assistant Professor in NYU','New York University');
Call Add_User('Harry','Smith','Funplus_Harry','230449543@hotmail.com','hs1978ny','Manhattan','New York','United States','Product Manager','Manhattan Funplus Tech ltd.');
Call Add_User('Emma','Brown','BestEmma','eb4456@nyu.edu','emmaisbest99','Brooklyn','New York','United States','graduate student','New York University');
Call Add_User('Mario','Taglic','SuperMario','martag@gmail.com','mmm3456qwer%','Warman','Saskatchewan','Canada','Graduate from University of Toronto, Good at Calculus, Java& C++','Cisco System, Inc.');
Call Add_User('Kevin','Du','KevinInNYU','kdd3244@nyu.edu','kd19980602','Shanghai','Shanghai','China',NULL,'New York University');
Call Add_User('Shawn','Lee','ROOF','sl5399@nyu.edu','sl1996leeeeee','Hongkong','Hongkong','China',NULL,'New York University');
Call Add_User('Alvin','Musk','ProA','projectA@gmail.com','aabbcc135','Brooklyn','New York','United States',NULL,NULL);
Call Add_User('Steven','Zhang','Snowfish','tothemoon@gmail.com','zxy19970302ss','Syracuse','New York','United States',NULL,NULL);
Call Add_User('Yuki','Toriyama','SnowflakeLady77','yukitoriym@gmail.com','yktryMuioiaa2020','Tokyo','Kanto','Japan',NULL,'Rakuten Inc.');

INSERT INTO Topic(tname,stname) VALUES ('Computer Science','Programming');
INSERT INTO Topic(tname,stname) VALUES ('Computer Science','JAVA');
INSERT INTO Topic(tname,stname) VALUES ('Computer Science','C++');
INSERT INTO Topic(tname,stname) VALUES ('Computer Science','Python');
INSERT INTO Topic(tname,stname) VALUES ('Computer Science','HTML');
INSERT INTO Topic(tname,stname) VALUES ('Computer Science','JavaScript');
INSERT INTO Topic(tname,stname) VALUES ('Mathematics','Calculus');
INSERT INTO Topic(tname,stname) VALUES ('Mathematics','Algebra');
INSERT INTO Topic(tname,stname) VALUES ('Mathematics','Applied Math');
INSERT INTO Topic(tname,stname) VALUES ('Others','Misc');
INSERT INTO Topic(tname,stname) VALUES ('Others','Introduction');
INSERT INTO Topic(tname,stname) VALUES ('Science','Chemistry');
INSERT INTO Topic(tname,stname) VALUES ('Science','Physics');



Call Add_Question(1,1,'Need Help','As a beginner, which programming language should I choose as starter?');
Call Add_Question(1,2,'Error!','Out of range');
Call Add_Question(6,7,'a math problem','anyone can explain Squeeze Law for me?');
Call Add_Question(6,7,'Squeeze Law','Is Squeeze Theorem Always Zero?');
Call Add_Question(4,11,'Hello, this is Emma','I am a graduate student in NYU. Nice to meet you all!');
Call Add_Question(2,10,'test','test');
Call Add_Question(6,7,'Math','What is the dimension of real anti-symmetric 4 × 4 matrices?');
Call Add_Question(7,8,'Solving FODE','How should I approach solving first order differential equations?');
Call Add_Question(7,7,'Integral help','How should I define the limits of an integral?');
Call Add_Question(7,4,'Split','Can I use split to separate dictionaries?');
Call Add_Question(7,1,'IDE','What IDE should I use?');
Call Add_Question(7,2,'Help with syntax','Can someone explain to me why this code is giving me errors?');
Call Add_Question(7,10,'Hi','Hey everyone, this is my first time using this site. Can someone explain how to upload an image?');
Call Add_Question(7,3,'Inheritance','How does inheritance work?');
Call Add_Question(2,12,'Acids and Bases: What differentiates them','title');

Call Add_Answer(1,2,'I recommand Java and C; Python is easy to learn but not appropriate for beginner to learn data structure.');
Call Add_Answer(1,3,'C++ IS THE BEST WITH NO REASON');
Call Add_Answer(2,2,'An out-of-range index occurs when the index is less than zero, or greater than or equal to the length of the string.');
Call Add_Answer(3,5,'The squeeze (or sandwich) theorem states that if f(x)≤g(x)≤h(x) for all numbers, and at some point x=k we have f(k)=h(k), then g(k) must also be equal to them.');
Call Add_Answer(3,9,'if f(x)≤g(x)≤h(x) for all numbers, at some point x=k f(k)=h(k), then g(k) should be equal to them.');
Call Add_Answer(5,5,'Hi, Emma! Nice to meet you~ I am Mario.');
Call Add_Answer(5,6,'Hi, I am also a student at NYU.');
Call Add_Answer(5,1,'I am Alex at NYU.');

Call Add_Answer(1,7,'Hi! I do not know much about programming but I heard python is good');
Call Add_Answer(2,7,'You are probably going out of range');
Call Add_Answer(3,7,'Maybe you could try khan academy');
Call Add_Answer(4,7,'According to google, yes');
Call Add_Answer(5,7,'Hello Emma, nice to meet you too!');
Call Add_Answer(6,7,'?');
Call Add_Answer(15,7,'Hi, acids compounds that donate hydrogen ions and bases are substances that donate hydroxide ions when mixed');



Call likes(1,1,3);
Call likes(2,3,3);
Call likes(3,3,3);
Call likes(4,3,3);
Call likes(1,5,4);
Call likes(10,6,1);
Call likes(4,6,1);
Call likes(5,6,1);
Call likes(6,6,1);
Call likes(8,6,1);
Call likes(10,15,1);
Call likes(4,15,1);
Call likes(5,15,1);
Call likes(6,15,1);
Call likes(8,15,1);
Call likes(1,1,1);
Call likes(3,1,1);
Call likes(4,1,1);
Call likes(5,1,1);
Call likes(6,1,1);
Call likes(7,1,1);
Call likes(8,1,1);
Call likes(9,1,1);
Call likes(10,1,1);
Call likes(5,1,2);
Call likes(1,2,1);
Call likes(4,2,1);
Call likes(6,2,1);
Call likes(7,2,1);
Call likes(8,2,1);
Call likes(6,3,1);
Call likes(1,3,1);
Call likes(4,5,1);
Call likes(4,5,2);
Call likes(4,5,3);
Call likes(1,5,2);
Call likes(6,5,3);



