CREATE DEFINER=`root`@`localhost` PROCEDURE `Add_Answer`(
  qid INTEGER,
  answerer INTEGER,
  abody VARCHAR(240)
)
BEGIN
	DECLARE na INTEGER;
    SET na = next_anum(qid);
	INSERT INTO Answer(qid,anum,answerer,abody,answerdate,bestanswer,numlikes) 
    VALUES (qid,na,answerer,abody,now(),FALSE,0);
END;

CREATE DEFINER=`root`@`localhost` PROCEDURE `Add_Question`(
  asker INTEGER, 
  tid INTEGER,
  title VARCHAR(100),
  qbody VARCHAR(200)
  )
BEGIN
	INSERT INTO Question(asker,tid,title,qbody,questiondate,qstatus) 
    VALUES (asker,tid,title,qbody,now(),"Unanswered");
END;

CREATE DEFINER=`root`@`localhost` PROCEDURE `Add_User`(firstname VARCHAR(50) ,
	lastname VARCHAR(50),
    username VARCHAR (50),
	email VARCHAR(50), 
	password VARCHAR(100),
	city VARCHAR(30),
	state VARCHAR(20), 
	country VARCHAR(30),
	personal_profile VARCHAR(200),
	affilation VARCHAR(30))
BEGIN
	Insert Into Users(firstname, lastname, username, email, password,  city, state, country, personal_profile,affilation ) 
		Values (firstname, lastname, username, email, password, city, state, country, personal_profile,affilation);
END;

CREATE DEFINER=`root`@`localhost` PROCEDURE `Answers_to_Question`(questionid INTEGER)
BEGIN
	Select anum, username,abody,answerdate,bestanswer,numlikes
    From Q_A Inner Join Users on Q_A.answerer = Users.uid
    Where qid = questionid 
    Order by answerdate DESC;
END;

CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_profile`(
	userid integer,
    prof VARCHAR(200)
)
BEGIN
	Update Users
    Set personal_profile = prof
    Where uid = userid;
END;


CREATE DEFINER=`root`@`localhost` PROCEDURE `likes`(
	u1 INTEGER,
    questionid INTEGER,
    answernum INTEGER
)
BEGIN
	INSERT INTO Likes(uid,qid,anum) VALUES
    (u1, questionid, answernum);
END;


CREATE DEFINER=`root`@`localhost` PROCEDURE `Recent_Answers`(userid VARCHAR(3))
BEGIN
	Select qid, title, abody, answerdate, numlikes
    From Q_A
    Where Q_A.asker = userid 
    Order by answerdate DESC
    Limit 5;
END;



CREATE DEFINER=`root`@`localhost` PROCEDURE `Search_Chronologically`(keywords VARCHAR(1000))
BEGIN
	drop table if exists Info;
    
	Create table Info as Select * From Q_A_T;
	Create Fulltext Index FTqbody on Info(qbody);
	Create Fulltext Index FTtitle on Info(title);
	Create Fulltext Index FTabody on Info(abody);
	SET @KeywordQuery = keywords;
	With 
	Stats as (Select qid, title as Title , qbody as Question_Body, questiondate, qstatus, tname as Topic ,stname as Subtopic, 
		   Match(qbody) Against (@KeyWordQuery in Boolean Mode) as w1,
		   Match(title) Against (@KeyWordQuery in Boolean Mode) as w2,
		   Match(abody) Against (@KeyWordQuery in Boolean Mode) as w3
		   From Info)
	Select qid, Title, Question_Body, questiondate, qstatus, Topic, Subtopic, (3*w1 + 5*w2 + Sum(w3)) as TotalWeight
	From Stats
	Group by qid
	Having TotalWeight > 0
	Order by questiondate DESC;
END;


CREATE DEFINER=`root`@`localhost` PROCEDURE `Search_Relevance`(keywords VARCHAR(1000))
BEGIN
	drop table if exists Info;
    
	Create table Info as Select * From Q_A_T;
	Create Fulltext Index FTqbody on Info(qbody);
	Create Fulltext Index FTtitle on Info(title);
	Create Fulltext Index FTabody on Info(abody);
	SET @KeywordQuery = keywords;
	With 
	Stats as (Select qid, title as Title , qbody as Question_Body, questiondate,qstatus, tname as Topic ,stname as Subtopic, 
		   Match(qbody) Against (@KeyWordQuery in Boolean Mode) as w1,
		   Match(title) Against (@KeyWordQuery in Boolean Mode) as w2,
		   Match(abody) Against (@KeyWordQuery in Boolean Mode) as w3
		   From Info)
	Select qid, Title, Question_Body, questiondate, qstatus, Topic, Subtopic, (w1 + w2 + Sum(w3)) as TotalWeight
	From Stats
	Group by qid
	Having TotalWeight > 0
	Order by TotalWeight DESC;
END;

CREATE DEFINER=`root`@`localhost` PROCEDURE `See_Answers_ByUser`(userid INTEGER)
BEGIN
	Select qid, title, abody, answerdate, numlikes, bestanswer
    From Question Natural Join Answer
    Where answerer = userid;
END;

CREATE DEFINER=`root`@`localhost` PROCEDURE `See_Questions_ByUser`(userid INTEGER)
BEGIN
	Select title, qbody, questiondate, qstatus, tname, stname 
    From Q_A_T
    Where asker = userid;
END;

CREATE DEFINER=`root`@`localhost` FUNCTION `next_anum`(
	questionid INTEGER
) RETURNS int
    DETERMINISTIC
BEGIN
DECLARE anum INTEGER;
Select COUNT(*) + 1 into anum
FROM Answer
Where qid = questionid;
RETURN anum;
END