# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.21)
# Database: adminsite
# Generation Time: 2018-02-02 08:48:33 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT 'Brugernavn',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT 'Adgangskode',
  `firstname` varchar(255) NOT NULL DEFAULT '' COMMENT 'Fornavn',
  `lastname` varchar(255) NOT NULL DEFAULT '' COMMENT 'Efternavn',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT 'Adresse',
  `zipcode` mediumint(10) NOT NULL COMMENT 'Postnummer',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT 'By',
  `country` varchar(255) NOT NULL DEFAULT '' COMMENT 'Land',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT 'Email',
  `phone1` varchar(255) DEFAULT NULL COMMENT 'Tlf 1',
  `phone2` varchar(255) DEFAULT NULL COMMENT 'Tlf 2',
  `phone3` varchar(255) DEFAULT NULL COMMENT 'Tlf 3',
  `birthdate` bigint(20) DEFAULT '0' COMMENT 'Organisation',
  `gender` varchar(1) NOT NULL,
  `created` bigint(20) NOT NULL DEFAULT '0',
  `suspended` tinyint(1) DEFAULT '0' COMMENT 'Suspenderet',
  `deleted` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `iOrgID` (`birthdate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `username`, `password`, `firstname`, `lastname`, `address`, `zipcode`, `city`, `country`, `email`, `phone1`, `phone2`, `phone3`, `birthdate`, `gender`, `created`, `suspended`, `deleted`)
VALUES
	(1,'admin','$2y$10$eD0PuOqt0SabZvvlNk0iNebKy7MxZ0q0pSIN7etmP5H.ZRlXZus3q','Admin','Amin Admin','Admingade 12',1234,'Adminde','DK','admin@admin.dk',NULL,NULL,NULL,1517559355,'m',1517559355,0,0);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table usergroup
# ------------------------------------------------------------

DROP TABLE IF EXISTS `usergroup`;

CREATE TABLE `usergroup` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Gruppenavn',
  `description` text COMMENT 'Beskrivelse',
  `role` varchar(20) DEFAULT '' COMMENT 'Rollenavn',
  `created` bigint(20) NOT NULL DEFAULT '0' COMMENT 'Oprettet',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table usergrouprel
# ------------------------------------------------------------

DROP TABLE IF EXISTS `usergrouprel`;

CREATE TABLE `usergrouprel` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `group_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `iUserID` (`user_id`),
  KEY `iGroupID` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table usersession
# ------------------------------------------------------------

DROP TABLE IF EXISTS `usersession`;

CREATE TABLE `usersession` (
  `id` varchar(32) NOT NULL DEFAULT '',
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `isloggedin` tinyint(1) NOT NULL DEFAULT '0',
  `created` bigint(20) NOT NULL DEFAULT '0',
  `lastaction` bigint(20) NOT NULL DEFAULT '0',
  KEY `iUserID` (`user_id`),
  CONSTRAINT `usersession_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
