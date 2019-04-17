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
-- Table structure for table `vision`
--

DROP TABLE IF EXISTS `vision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vision` (
  `idvision` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` varchar(10) NOT NULL,
  `name_customers` varchar(100) NOT NULL,
  `name_project` varchar(100) NOT NULL,
  `problem` varchar(1000) NOT NULL,
  `affects` varchar(1000) NOT NULL,
  `impact` varchar(1000) NOT NULL,
  `solutions` varchar(1000) NOT NULL,
  `operating_environment` varchar(1000) NOT NULL,
  `user_environment` varchar(1000) NOT NULL,
  `user_profiles` varchar(1000) NOT NULL,
  `customers` varchar(100) NOT NULL,
  `corporate` varchar(100) NOT NULL,
  `distribuitor` varchar(100) NOT NULL,
  `view_project` varchar(1000) NOT NULL,
  `perspective_project` varchar(1000) NOT NULL,
  `login` varchar(500) NOT NULL,
  `activations` varchar(500) NOT NULL,
  `activate_card` varchar(500) NOT NULL,
  `assign_points` varchar(500) NOT NULL,
  `promotion` varchar(500) NOT NULL,
  `comunicator` varchar(500) NOT NULL,
  `activate_card_check` varchar(500) NOT NULL,
  `points_awarded` varchar(500) NOT NULL,
  `points_redeemed` varchar(500) NOT NULL,
  `news` varchar(500) NOT NULL,
  `personal_data` varchar(500) NOT NULL,
  `points_balance` varchar(500) NOT NULL,
  `see_promotions` varchar(500) NOT NULL,
  `the_news` varchar(500) NOT NULL,
  `promotions` varchar(500) NOT NULL,
  `store` varchar(1000) NOT NULL,
  `other_requerimnets` varchar(500) NOT NULL,
  `applicable_standard` varchar(500) NOT NULL,
  `system_requeriments` varchar(500) NOT NULL,
  `performance_requeriments` varchar(500) NOT NULL,
  `environmental_requirements` varchar(500) NOT NULL,
  PRIMARY KEY (`idvision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='IOTeca Es la vision del proyecto';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-12-28 13:07:41
