CREATE DATABASE MusicOnline;
USE MusicOnline;

CREATE TABLE `User` (
	`username` VARCHAR(20) NOT NULL,
	`password`  VARCHAR(45)   NOT   NULL, 
   	`uname` VARCHAR(45) NOT NULL,
    	`uemail` VARCHAR(45),
    	`ucity` VARCHAR(20),
	PRIMARY KEY (`username`),  
    	UNIQUE INDEX `uemail_Index` (`uemail` ASC));
    
CREATE TABLE `Artist` (
	`artistID` VARCHAR(45) NOT NULL ,
	`arname` VARCHAR(200) NOT NULL,
	`ardesc` TEXT,
	PRIMARY KEY (`artistID`),
	UNIQUE INDEX `arname_Index` (`arname` ASC)
);
      
CREATE TABLE `Album` (
	`albumID` VARCHAR(45) NOT NULL,
	`altitle` TEXT,
	`aldate` VARCHAR(45),
	PRIMARY KEY (`albumID`));
    
CREATE TABLE `Track` (
	`trackID` VARCHAR(45) NOT NULL, 
    	`ttitle` TEXT,
    	`duration` INT NOT NULL,
    	`arname` VARCHAR(200) ,
        `albumID` VARCHAR(45),
    	PRIMARY KEY (`trackID`),
  	FOREIGN KEY (`arname`) REFERENCES `Artist`(`arname`) ON DELETE NO ACTION ON UPDATE CASCADE,
  	FOREIGN KEY (`albumID`) REFERENCES `Album`(`albumID`) ON DELETE NO ACTION ON UPDATE CASCADE
);
      
CREATE TABLE `Playlist` (             
		`playID` INT NOT NULL AUTO_INCREMENT,
    	`ptitle` VARCHAR(20) NOT NULL,
    	`pdate` DATE NOT NULL,
		`visible` VARCHAR(20) NOT NULL,
		`username` VARCHAR(20) NOT NULL,
    	PRIMARY KEY (`playID`),
 	INDEX `username_Index` (`username` ASC),     
    	CONSTRAINT `username`
	FOREIGN KEY (`username`) REFERENCES `User` (`username`) ON DELETE CASCADE ON UPDATE CASCADE);

CREATE TABLE `Playlist_Track` (  
	`playID` INT NOT NULL,
	`trackID` VARCHAR(45)  NOT NULL,
	`lsequence` INT,
	PRIMARY KEY (`playID`, `trackID`),
	FOREIGN KEY (`playID`) REFERENCES `Playlist` (`playID`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`trackID`) REFERENCES `Track` (`trackID`) ON DELETE CASCADE ON UPDATE CASCADE);

CREATE TABLE `Like_Artist` (
	`username` VARCHAR(20) NOT NULL,     
    	`artistID` VARCHAR(45)  NOT NULL,
	`ltimestamp` DATETIME NOT NULL,    
    	PRIMARY KEY (`username`, `artistID`),
	INDEX `artistID_Index` (`artistID` ASC),
	FOREIGN KEY (`username`) REFERENCES `User` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`artistID`) REFERENCES `Artist` (`artistID`) ON DELETE CASCADE ON UPDATE CASCADE);

CREATE TABLE `Rate_Song` (
	`username` VARCHAR(20) NOT NULL,
    	`trackID` VARCHAR(45)  NOT NULL,
	`score` INT NOT NULL,
	`rtimestamp` DATETIME NOT NULL,
	PRIMARY KEY (`username`, `trackID`),
	INDEX `trackID_Index` (`trackID` ASC),
	FOREIGN KEY (`username`) REFERENCES `User` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`trackID`) REFERENCES `Track` (`trackID`) ON DELETE CASCADE ON UPDATE CASCADE);

CREATE TABLE `Follow_User` (
	`follow` VARCHAR(20) NOT NULL,       
    	`followed` VARCHAR(20) NOT NULL,
	`ftimestamp` DATETIME NOT NULL,
	PRIMARY KEY (`follow`, `followed`),
	INDEX `follow_Index` (`follow` ASC),
	FOREIGN KEY (`follow`) REFERENCES `User` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`followed`) REFERENCES `User` (`username`) ON DELETE CASCADE ON UPDATE CASCADE);

CREATE TABLE `Play_Track` (
	`username` VARCHAR(20) NOT NULL,     
    	`trackID` VARCHAR(45)  NOT NULL,
	`ptimestamp` DATETIME NOT NULL,
	PRIMARY KEY (`username`, `trackID`, `ptimestamp`),
	INDEX `trackID_Index` (`trackID` ASC),
	FOREIGN KEY (`username`) REFERENCES `User` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`trackID`) REFERENCES `Track` (`trackID`) ON DELETE CASCADE ON UPDATE CASCADE);
