# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.26)
# Database: renqi_api
# Generation Time: 2015-09-14 07:44:30 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table pre_companyrecord
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pre_companyrecord`;

CREATE TABLE `pre_companyrecord` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` char(15) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `pre_companyrecord` WRITE;
/*!40000 ALTER TABLE `pre_companyrecord` DISABLE KEYS */;

INSERT INTO `pre_companyrecord` (`id`, `ip`, `time`)
VALUES
	(30,'127.0.0.1',1442215454);

/*!40000 ALTER TABLE `pre_companyrecord` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pre_expressrecord
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pre_expressrecord`;

CREATE TABLE `pre_expressrecord` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` char(15) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `pre_expressrecord` WRITE;
/*!40000 ALTER TABLE `pre_expressrecord` DISABLE KEYS */;

INSERT INTO `pre_expressrecord` (`id`, `ip`, `time`)
VALUES
	(16,'127.0.0.1',1442216589);

/*!40000 ALTER TABLE `pre_expressrecord` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pre_smsrecord
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pre_smsrecord`;

CREATE TABLE `pre_smsrecord` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(11) NOT NULL DEFAULT '',
  `tpl_id` int(11) NOT NULL,
  `code` int(6) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `pre_smsrecord` WRITE;
/*!40000 ALTER TABLE `pre_smsrecord` DISABLE KEYS */;

INSERT INTO `pre_smsrecord` (`id`, `mobile`, `tpl_id`, `code`, `time`)
VALUES
	(9,'2424234',5596,717971,1442198346),
	(7,'18173782162',5602,275457,1442197799),
	(10,'234234',5596,424053,1442198397);

/*!40000 ALTER TABLE `pre_smsrecord` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
