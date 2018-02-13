# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.21)
# Database: adminsite
# Generation Time: 2018-02-13 08:17:30 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table country
# ------------------------------------------------------------

DROP TABLE IF EXISTS `country`;

CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;

INSERT INTO `country` (`id`, `code`, `name`)
VALUES
	(1,'AF','Afghanistan'),
	(2,'AL','Albania'),
	(3,'DZ','Algeria'),
	(4,'DS','American Samoa'),
	(5,'AD','Andorra'),
	(6,'AO','Angola'),
	(7,'AI','Anguilla'),
	(8,'AQ','Antarctica'),
	(9,'AG','Antigua and Barbuda'),
	(10,'AR','Argentina'),
	(11,'AM','Armenia'),
	(12,'AW','Aruba'),
	(13,'AU','Australia'),
	(14,'AT','Austria'),
	(15,'AZ','Azerbaijan'),
	(16,'BS','Bahamas'),
	(17,'BH','Bahrain'),
	(18,'BD','Bangladesh'),
	(19,'BB','Barbados'),
	(20,'BY','Belarus'),
	(21,'BE','Belgium'),
	(22,'BZ','Belize'),
	(23,'BJ','Benin'),
	(24,'BM','Bermuda'),
	(25,'BT','Bhutan'),
	(26,'BO','Bolivia'),
	(27,'BA','Bosnia and Herzegovina'),
	(28,'BW','Botswana'),
	(29,'BV','Bouvet Island'),
	(30,'BR','Brazil'),
	(31,'IO','British Indian Ocean Territory'),
	(32,'BN','Brunei Darussalam'),
	(33,'BG','Bulgaria'),
	(34,'BF','Burkina Faso'),
	(35,'BI','Burundi'),
	(36,'KH','Cambodia'),
	(37,'CM','Cameroon'),
	(38,'CA','Canada'),
	(39,'CV','Cape Verde'),
	(40,'KY','Cayman Islands'),
	(41,'CF','Central African Republic'),
	(42,'TD','Chad'),
	(43,'CL','Chile'),
	(44,'CN','China'),
	(45,'CX','Christmas Island'),
	(46,'CC','Cocos (Keeling) Islands'),
	(47,'CO','Colombia'),
	(48,'KM','Comoros'),
	(49,'CG','Congo'),
	(50,'CK','Cook Islands'),
	(51,'CR','Costa Rica'),
	(52,'HR','Croatia (Hrvatska)'),
	(53,'CU','Cuba'),
	(54,'CY','Cyprus'),
	(55,'CZ','Czech Republic'),
	(56,'DK','Denmark'),
	(57,'DJ','Djibouti'),
	(58,'DM','Dominica'),
	(59,'DO','Dominican Republic'),
	(60,'TP','East Timor'),
	(61,'EC','Ecuador'),
	(62,'EG','Egypt'),
	(63,'SV','El Salvador'),
	(64,'GQ','Equatorial Guinea'),
	(65,'ER','Eritrea'),
	(66,'EE','Estonia'),
	(67,'ET','Ethiopia'),
	(68,'FK','Falkland Islands (Malvinas)'),
	(69,'FO','Faroe Islands'),
	(70,'FJ','Fiji'),
	(71,'FI','Finland'),
	(72,'FR','France'),
	(73,'FX','France, Metropolitan'),
	(74,'GF','French Guiana'),
	(75,'PF','French Polynesia'),
	(76,'TF','French Southern Territories'),
	(77,'GA','Gabon'),
	(78,'GM','Gambia'),
	(79,'GE','Georgia'),
	(80,'DE','Germany'),
	(81,'GH','Ghana'),
	(82,'GI','Gibraltar'),
	(83,'GK','Guernsey'),
	(84,'GR','Greece'),
	(85,'GL','Greenland'),
	(86,'GD','Grenada'),
	(87,'GP','Guadeloupe'),
	(88,'GU','Guam'),
	(89,'GT','Guatemala'),
	(90,'GN','Guinea'),
	(91,'GW','Guinea-Bissau'),
	(92,'GY','Guyana'),
	(93,'HT','Haiti'),
	(94,'HM','Heard and Mc Donald Islands'),
	(95,'HN','Honduras'),
	(96,'HK','Hong Kong'),
	(97,'HU','Hungary'),
	(98,'IS','Iceland'),
	(99,'IN','India'),
	(100,'IM','Isle of Man'),
	(101,'ID','Indonesia'),
	(102,'IR','Iran (Islamic Republic of)'),
	(103,'IQ','Iraq'),
	(104,'IE','Ireland'),
	(105,'IL','Israel'),
	(106,'IT','Italy'),
	(107,'CI','Ivory Coast'),
	(108,'JE','Jersey'),
	(109,'JM','Jamaica'),
	(110,'JP','Japan'),
	(111,'JO','Jordan'),
	(112,'KZ','Kazakhstan'),
	(113,'KE','Kenya'),
	(114,'KI','Kiribati'),
	(115,'KP','Korea, Democratic People\'s Republic of'),
	(116,'KR','Korea, Republic of'),
	(117,'XK','Kosovo'),
	(118,'KW','Kuwait'),
	(119,'KG','Kyrgyzstan'),
	(120,'LA','Lao People\'s Democratic Republic'),
	(121,'LV','Latvia'),
	(122,'LB','Lebanon'),
	(123,'LS','Lesotho'),
	(124,'LR','Liberia'),
	(125,'LY','Libyan Arab Jamahiriya'),
	(126,'LI','Liechtenstein'),
	(127,'LT','Lithuania'),
	(128,'LU','Luxembourg'),
	(129,'MO','Macau'),
	(130,'MK','Macedonia'),
	(131,'MG','Madagascar'),
	(132,'MW','Malawi'),
	(133,'MY','Malaysia'),
	(134,'MV','Maldives'),
	(135,'ML','Mali'),
	(136,'MT','Malta'),
	(137,'MH','Marshall Islands'),
	(138,'MQ','Martinique'),
	(139,'MR','Mauritania'),
	(140,'MU','Mauritius'),
	(141,'TY','Mayotte'),
	(142,'MX','Mexico'),
	(143,'FM','Micronesia, Federated States of'),
	(144,'MD','Moldova, Republic of'),
	(145,'MC','Monaco'),
	(146,'MN','Mongolia'),
	(147,'ME','Montenegro'),
	(148,'MS','Montserrat'),
	(149,'MA','Morocco'),
	(150,'MZ','Mozambique'),
	(151,'MM','Myanmar'),
	(152,'NA','Namibia'),
	(153,'NR','Nauru'),
	(154,'NP','Nepal'),
	(155,'NL','Netherlands'),
	(156,'AN','Netherlands Antilles'),
	(157,'NC','New Caledonia'),
	(158,'NZ','New Zealand'),
	(159,'NI','Nicaragua'),
	(160,'NE','Niger'),
	(161,'NG','Nigeria'),
	(162,'NU','Niue'),
	(163,'NF','Norfolk Island'),
	(164,'MP','Northern Mariana Islands'),
	(165,'NO','Norway'),
	(166,'OM','Oman'),
	(167,'PK','Pakistan'),
	(168,'PW','Palau'),
	(169,'PS','Palestine'),
	(170,'PA','Panama'),
	(171,'PG','Papua New Guinea'),
	(172,'PY','Paraguay'),
	(173,'PE','Peru'),
	(174,'PH','Philippines'),
	(175,'PN','Pitcairn'),
	(176,'PL','Poland'),
	(177,'PT','Portugal'),
	(178,'PR','Puerto Rico'),
	(179,'QA','Qatar'),
	(180,'RE','Reunion'),
	(181,'RO','Romania'),
	(182,'RU','Russian Federation'),
	(183,'RW','Rwanda'),
	(184,'KN','Saint Kitts and Nevis'),
	(185,'LC','Saint Lucia'),
	(186,'VC','Saint Vincent and the Grenadines'),
	(187,'WS','Samoa'),
	(188,'SM','San Marino'),
	(189,'ST','Sao Tome and Principe'),
	(190,'SA','Saudi Arabia'),
	(191,'SN','Senegal'),
	(192,'RS','Serbia'),
	(193,'SC','Seychelles'),
	(194,'SL','Sierra Leone'),
	(195,'SG','Singapore'),
	(196,'SK','Slovakia'),
	(197,'SI','Slovenia'),
	(198,'SB','Solomon Islands'),
	(199,'SO','Somalia'),
	(200,'ZA','South Africa'),
	(201,'GS','South Georgia South Sandwich Islands'),
	(202,'ES','Spain'),
	(203,'LK','Sri Lanka'),
	(204,'SH','St. Helena'),
	(205,'PM','St. Pierre and Miquelon'),
	(206,'SD','Sudan'),
	(207,'SR','Suriname'),
	(208,'SJ','Svalbard and Jan Mayen Islands'),
	(209,'SZ','Swaziland'),
	(210,'SE','Sweden'),
	(211,'CH','Switzerland'),
	(212,'SY','Syrian Arab Republic'),
	(213,'TW','Taiwan'),
	(214,'TJ','Tajikistan'),
	(215,'TZ','Tanzania, United Republic of'),
	(216,'TH','Thailand'),
	(217,'TG','Togo'),
	(218,'TK','Tokelau'),
	(219,'TO','Tonga'),
	(220,'TT','Trinidad and Tobago'),
	(221,'TN','Tunisia'),
	(222,'TR','Turkey'),
	(223,'TM','Turkmenistan'),
	(224,'TC','Turks and Caicos Islands'),
	(225,'TV','Tuvalu'),
	(226,'UG','Uganda'),
	(227,'UA','Ukraine'),
	(228,'AE','United Arab Emirates'),
	(229,'GB','United Kingdom'),
	(230,'US','United States'),
	(231,'UM','United States minor outlying islands'),
	(232,'UY','Uruguay'),
	(233,'UZ','Uzbekistan'),
	(234,'VU','Vanuatu'),
	(235,'VA','Vatican City State'),
	(236,'VE','Venezuela'),
	(237,'VN','Vietnam'),
	(238,'VG','Virgin Islands (British)'),
	(239,'VI','Virgin Islands (U.S.)'),
	(240,'WF','Wallis and Futuna Islands'),
	(241,'EH','Western Sahara'),
	(242,'YE','Yemen'),
	(243,'ZR','Zaire'),
	(244,'ZM','Zambia'),
	(245,'ZW','Zimbabwe');

/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table org
# ------------------------------------------------------------

DROP TABLE IF EXISTS `org`;

CREATE TABLE `org` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Navn',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT 'Adresse',
  `zipcode` varchar(255) NOT NULL DEFAULT '' COMMENT 'Postnummer',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT 'By',
  `country_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT 'Email',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT 'Telefon',
  `created` bigint(20) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `org` WRITE;
/*!40000 ALTER TABLE `org` DISABLE KEYS */;

INSERT INTO `org` (`id`, `name`, `address`, `zipcode`, `city`, `country_id`, `email`, `phone`, `created`, `deleted`)
VALUES
	(3,'Tech College','Øster Uttrupvej 1','9000','Aalborg',56,'info@techcollege.dk','',1518508298,0);

/*!40000 ALTER TABLE `org` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `usergroup` WRITE;
/*!40000 ALTER TABLE `usergroup` DISABLE KEYS */;

INSERT INTO `usergroup` (`id`, `name`, `description`, `role`, `created`, `deleted`)
VALUES
	(1,'System administrator','Brugere i denne gruppe har overordnet adgang til kontrolpanel, database håndtering og design manager','sysadmin',1518508722,0),
	(2,'Administrator','Brugere i denne gruppe har rettigheder til at oprette, redigere og slette elementer i CMS systemet','admin',1518508722,0),
	(3,'Extranet Bruger','Brugere i denne gruppe har adgang til et loginbeskyttet ekstranet på frontend','extranet',1518508722,0),
	(4,'Nyhedsbrev Bruger','Brugere i denne gruppe er tilmeldt et nyhedsbrev','newsletter',1518508722,0);

/*!40000 ALTER TABLE `usergroup` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `usergrouprel` WRITE;
/*!40000 ALTER TABLE `usergrouprel` DISABLE KEYS */;

INSERT INTO `usergrouprel` (`id`, `user_id`, `group_id`)
VALUES
	(1,1,1),
	(2,1,2);

/*!40000 ALTER TABLE `usergrouprel` ENABLE KEYS */;
UNLOCK TABLES;


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
