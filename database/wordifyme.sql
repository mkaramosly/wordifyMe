# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 0.0.0.0 (MySQL 5.7.17)
# Database: woridyme
# Generation Time: 2017-03-17 06:01:26 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `fileId` int(10) NOT NULL AUTO_INCREMENT,
  `path` varchar(50) COLLATE utf8_bin NOT NULL,
  `filename` varchar(50) COLLATE utf8_bin NOT NULL,
  `uploadedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`fileId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table meeting
# ------------------------------------------------------------

DROP TABLE IF EXISTS `meeting`;

CREATE TABLE `meeting` (
  `meetingId` int(10) NOT NULL AUTO_INCREMENT,
  `projectId` int(10) NOT NULL,
  `fileId` int(10) NOT NULL,
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `startTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `endTimestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `transcriptId` int(10) NOT NULL,
  `transcribed` enum('0','1','2') COLLATE utf8_bin NOT NULL DEFAULT '0',
  `parsed` enum('0','1','2') COLLATE utf8_bin NOT NULL DEFAULT '0',
  `analyzed` enum('0','1','2') COLLATE utf8_bin NOT NULL DEFAULT '0',
  PRIMARY KEY (`meetingId`),
  KEY `fk_fileId` (`fileId`),
  KEY `transcriptId` (`transcriptId`),
  CONSTRAINT `fk_fileId` FOREIGN KEY (`fileId`) REFERENCES `files` (`fileId`),
  CONSTRAINT `meeting_ibfk_1` FOREIGN KEY (`transcriptId`) REFERENCES `transcript` (`transcriptId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table meeting_words
# ------------------------------------------------------------

DROP TABLE IF EXISTS `meeting_words`;

CREATE TABLE `meeting_words` (
  `meetingWordId` int(10) NOT NULL AUTO_INCREMENT,
  `meetingId` int(10) NOT NULL,
  `word` varchar(100) COLLATE utf8_bin NOT NULL,
  `startTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `endTimestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `confidence` float NOT NULL,
  PRIMARY KEY (`meetingWordId`),
  KEY `fk_meetingId` (`meetingId`),
  CONSTRAINT `fk_meetingId` FOREIGN KEY (`meetingId`) REFERENCES `meeting` (`meetingId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table transcript
# ------------------------------------------------------------

DROP TABLE IF EXISTS `transcript`;

CREATE TABLE `transcript` (
  `transcriptId` int(10) NOT NULL AUTO_INCREMENT,
  `transcript` text COLLATE utf8_bin NOT NULL,
  `confidence` float NOT NULL,
  PRIMARY KEY (`transcriptId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table word_alternative
# ------------------------------------------------------------

DROP TABLE IF EXISTS `word_alternative`;

CREATE TABLE `word_alternative` (
  `wordAlternativeId` int(10) NOT NULL AUTO_INCREMENT,
  `meetingWordId` int(10) NOT NULL,
  `confidence` float NOT NULL,
  PRIMARY KEY (`wordAlternativeId`),
  KEY `fk_meetingWordId` (`meetingWordId`),
  CONSTRAINT `fk_meetingWordId` FOREIGN KEY (`meetingWordId`) REFERENCES `meeting_words` (`meetingWordId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
