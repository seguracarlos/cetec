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
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `id_company` int(5) NOT NULL AUTO_INCREMENT,
  `name_company` varchar(100) DEFAULT NULL,
  `brand` varchar(100) NOT NULL,
  `rfc` varchar(35) NOT NULL,
  `website` varchar(45) DEFAULT NULL,
  `company_isactive` tinyint(1) NOT NULL,
  `name_bank` varchar(100) NOT NULL,
  `number_acount` varchar(100) NOT NULL,
  `interbank_clabe` varchar(100) NOT NULL,
  `sucursal_name` varchar(100) DEFAULT NULL,
  `record_date` date NOT NULL,
  `business` varchar(100) DEFAULT NULL,
  `id_update_actions` int(5) NOT NULL,
  `progress_profile` int(11) DEFAULT '0',
  `cust_type` int(1) NOT NULL DEFAULT '0',
  `isprospect` int(1) NOT NULL DEFAULT '0',
  `ishost` int(1) DEFAULT '0',
  `interestingin` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_company`),
  KEY `fk_company_update_actions1` (`id_update_actions`),
  CONSTRAINT `fk_company_update_actions1` FOREIGN KEY (`id_update_actions`) REFERENCES `update_actions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='Entidad compania de cliente, proveedor o host';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-12-28 13:07:39
