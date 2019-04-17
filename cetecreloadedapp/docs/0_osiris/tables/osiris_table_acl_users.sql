CREATE DATABASE  IF NOT EXISTS `horus` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `osiris`;
-- MySQL dump 10.13  Distrib 5.5.40, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: horus
-- ------------------------------------------------------
-- Server version	5.5.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acl_users`
--

DROP TABLE IF EXISTS `acl_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_users` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `privateKey` int(10) NOT NULL,
  `role_id` int(4) NOT NULL,
  `user_type` varchar(7) NOT NULL,
  `company_ID` int(5) NOT NULL,
  `mail` varchar(40) NOT NULL,
  `numberEmployee` int(40) DEFAULT NULL,
  `rfc` varchar(35) DEFAULT NULL,
  `user_name` varchar(200) NOT NULL,
  `password` varchar(32) NOT NULL,
  `password_salt` varchar(32) NOT NULL,
  `real_name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `isDetailed` tinyint(1) NOT NULL DEFAULT '0',
  `photofilename` longtext,
  `photofile` longtext,
  `id_job` int(4) DEFAULT NULL,
  `canlogin` int(11) DEFAULT NULL,
  `id_department` int(11) NOT NULL,
  `user_principal` int(11) DEFAULT '0',
  `avatar` longtext,
  PRIMARY KEY (`id`),
  KEY `acl_users_to_company` (`company_ID`),
  KEY `fk_acl_users_acl_roles1` (`role_id`),
  KEY `fk_acl_users_job_users1` (`id_job`),
  KEY `fk_acl_users_department1_idx` (`id_department`),
  KEY `fk_privateKey_field_Key_Private` (`privateKey`),
  CONSTRAINT `fk_acl_users_acl_roles1` FOREIGN KEY (`role_id`) REFERENCES `acl_roles` (`role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_acl_users_company1` FOREIGN KEY (`company_ID`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_acl_users_department1` FOREIGN KEY (`id_department`) REFERENCES `department` (`id_department`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_acl_users_job_users1` FOREIGN KEY (`id_job`) REFERENCES `job_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='Entidad de usuarios';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-12-28 13:07:40
