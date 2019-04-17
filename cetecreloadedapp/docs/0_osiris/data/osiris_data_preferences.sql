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
-- Dumping data for table `preferences`
--

INSERT INTO `preferences` VALUES (1,'IVA','16'),(2,'FOTO','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'),(3,'TUTORIAL','Inactivo'),(4,'ICONS','minimal');
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-12-28 13:08:01