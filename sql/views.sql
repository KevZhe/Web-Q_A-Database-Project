CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `maxlikes` AS with `t1` as (select `answer`.`qid` AS `qid`,max(`answer`.`numlikes`) AS `ML` from `answer` group by `answer`.`qid`) select `answer`.`qid` AS `qid`,`answer`.`anum` AS `an`,`answer`.`numlikes` AS `ML` from (`answer` join `t1`) where ((`answer`.`qid` = `t1`.`qid`) and (`answer`.`numlikes` = `t1`.`ML`))

CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `q_a` AS
    SELECT 
        `question`.`qid` AS `qid`,
        `question`.`asker` AS `asker`,
        `question`.`tid` AS `tid`,
        `question`.`title` AS `title`,
        `question`.`qbody` AS `qbody`,
        `question`.`questiondate` AS `questiondate`,
        `question`.`qstatus` AS `qstatus`,
        `answer`.`anum` AS `anum`,
        `answer`.`answerer` AS `answerer`,
        `answer`.`abody` AS `abody`,
        `answer`.`answerdate` AS `answerdate`,
        `answer`.`bestanswer` AS `bestanswer`,
        `answer`.`numlikes` AS `numlikes`
    FROM
        (`question`
        JOIN `answer` ON ((`question`.`qid` = `answer`.`qid`)))

CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `q_a_t` AS
    SELECT 
        `question`.`tid` AS `tid`,
        `question`.`qid` AS `qid`,
        `question`.`asker` AS `asker`,
        `question`.`title` AS `title`,
        `question`.`qbody` AS `qbody`,
        `question`.`questiondate` AS `questiondate`,
        `question`.`qstatus` AS `qstatus`,
        `answer`.`anum` AS `anum`,
        `answer`.`answerer` AS `answerer`,
        `answer`.`abody` AS `abody`,
        `answer`.`answerdate` AS `answerdate`,
        `answer`.`bestanswer` AS `bestanswer`,
        `answer`.`numlikes` AS `numlikes`,
        `topic`.`tname` AS `tname`,
        `topic`.`stname` AS `stname`
    FROM
        ((`question`
        LEFT JOIN `answer` ON ((`question`.`qid` = `answer`.`qid`)))
        JOIN `topic` ON ((`question`.`tid` = `topic`.`tid`)))