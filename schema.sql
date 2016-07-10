DROP DATABASE IF EXISTS pweb;
CREATE DATABASE pweb;
USE pweb;

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(45) UNIQUE NOT NULL,
    passwd VARCHAR(45) NOT NULL,
    firstName VARCHAR(45),
    surname   VARCHAR(45),
    profilePic INTEGER
);

CREATE TABLE followShip(
    follower INTEGER NOT NULL,
    followed INTEGER NOT NULL,
    FOREIGN KEY(follower) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
     FOREIGN KEY(followed) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE,       
    PRIMARY KEY(follower,followed)
);


CREATE TABLE pics (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    description VARCHAR (45),
    path VARCHAR(200) NOT NULL,
    created DATETIME NOT NULL,
    userId INTEGER NOT NULL,
    mime BOOLEAN NOT NULL, /*  1=image, 0=video  */
    feed BOOLEAN NOT NULL, /* 1=feed 0=other */
    FOREIGN KEY(userId) REFERENCES users(id) 
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

/* FOREIGN KEY FOR USER PROFILE PIC */
ALTER TABLE users ADD CONSTRAINT
FOREIGN KEY(profilePic) REFERENCES pics(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL;
/***************************************************/

CREATE TABLE likes (
    userId INTEGER NOT NULL,
    picId INTEGER NOT NULL,
    upvote BOOLEAN, /* 1=upvote, 0=downvote  */
    FOREIGN KEY(userId) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY(picId) REFERENCES pics(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    PRIMARY KEY(userId,picId)
);


CREATE TABLE comments(
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    userId INTEGER NOT NULL,
    picId INTEGER NOT NULL,
    commentBody LONGTEXT NOT NULL,
    FOREIGN KEY(userId) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY(picId) REFERENCES pics(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE tags(
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    tagName VARCHAR(45) NOT NULL
);

CREATE TABLE tagShip(
    tagId INTEGER NOT NULL,
    picId INTEGER NOT NULL,
    FOREIGN KEY(tagId) REFERENCES tags(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(picId) REFERENCES pics(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY(tagId,picId)
);

CREATE TABLE messages(
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    srcId INTEGER NOT NULL,
    dstId INTEGER NOT NULL,
    messageBody VARCHAR(150),
    picId INTEGER,
    FOREIGN KEY(srcId) REFERENCES users(id) 
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(dstId) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(picId) REFERENCES pics(id)
        ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE notifies (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    userId INTEGER NOT NULL,
    actionDone VARCHAR(50) NOT NULL, /* COMMENT LIKE FOLLOW MESSAGE */
    userDone INTEGER,
    picInvolved INTEGER,   /* if like or comment the field below contains involved pic's id. */
    eventAt DATETIME,
    FOREIGN KEY(userDone) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(picInvolved) REFERENCES pics(id)
        ON UPDATE CASCADE ON DELETE CASCADE
);


/******************************************************* TRIGGER FOR NOTIFY FUNCTIONALITY ******************************************************/
/****************************************************************************************************************************************************************/
DROP TRIGGER IF EXISTS newFollower;
DROP TRIGGER IF EXISTS newLike;
DROP TRIGGER IF EXISTS newComment;
DROP TRIGGER IF EXISTS newMessage;
DROP TRIGGER IF EXISTS tagPic;
DROP TRIGGER IF EXISTS currTimePicCreated;

/* TRIGGERS FOR NOTIFY SYSTEM UPDATE */
CREATE TRIGGER newFollower 
AFTER INSERT ON  followShip FOR EACH ROW
    INSERT INTO notifies(userId,actionDone,userDone,eventAt) VALUES(NEW.followed,"FOLLOW",NEW.follower,CURRENT_TIME);


CREATE TRIGGER newLike
AFTER INSERT ON likes FOR EACH ROW
    INSERT INTO notifies(userId,actionDone,userDone,eventAt) 
    SELECT userId,"LIKE",NEW.userId,CURRENT_TIME FROM pics WHERE id = NEW.picId;
    
CREATE TRIGGER newComment
AFTER INSERT ON comments FOR EACH ROW
    INSERT INTO notifies(userId,actionDone,userDone,eventAt) 
    SELECT userId,"COMMENT",NEW.userId,CURRENT_TIME FROM pics WHERE id = NEW.picId;
    
CREATE TRIGGER newMessage
AFTER INSERT ON messages FOR EACH ROW
    INSERT INTO notifies(userId,actionDone,userDone,eventAt) VALUES(NEW.dstId,"MESSAGE",NEW.srcId,CURRENT_TIME);
    
/************************************************************************************************************************************************************/    
/***********************************************************************************************************************************************************/

/**** TRIGGER FOR DATETIME IN PICS ****/
CREATE TRIGGER  currTimePicCreated
BEFORE INSERT ON pics FOR EACH ROW
    SET NEW.created = CURRENT_TIME();


/**** STORED PROCEDURE TO HELP PIC'S TAG***/
DROP PROCEDURE IF EXISTS tagPic;
DELIMITER $$
CREATE PROCEDURE tagPic (IN picId INTEGER, IN tagName VARCHAR(45)) 
BEGIN
    DECLARE tagId INT DEFAULT NULL;
    /** CHECK IF TAG ALREADY EXISTS */
    SELECT  id  INTO tagId FROM tags T WHERE T.tagName = tagName;
    
    IF tagId IS NOT NULL THEN /* TAG ALREADY EXISTS */
        INSERT INTO tagship(tagId,picId) VALUES(tagId,picId);
    END IF;
    
    IF tagId IS NULL THEN
        INSERT INTO tags(tagName) VALUES(tagName);
        INSERT INTO tagship(tagId,picId) VALUES(LAST_INSERT_ID(),picId);
    END IF;
END $$
DELIMITER ;

/**** STORED PROCEDURE TO HELP USER'S FOLLOW***/
DROP PROCEDURE IF EXISTS followUser;
DELIMITER $$
CREATE PROCEDURE followUser (IN follower INT,IN followed INT)
BEGIN
    INSERT INTO followship(follower,followed) VALUES(follower,followed);
END $$


