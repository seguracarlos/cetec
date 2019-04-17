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
-- Table structure for table `sinister`
--

DROP TABLE IF EXISTS `sinister`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `sinister` (
`id_sinister` int(11) NOT NULL,
  `id_car` int(4) NOT NULL,
  `type_sinister` int(11) NOT NULL,
  `date` date NOT NULL,
  `hour` varchar(7) NOT NULL,
  `report` varchar(50) NOT NULL,
  `insurance_policy` varchar(50) NOT NULL,
  `sinister_number` varchar(50) NOT NULL,
  `address_sinister` varchar(50) NOT NULL,
  `follow_up` varchar(200) NOT NULL,
  `deductible` int(11) NOT NULL,
  `call_to` varchar(100) NOT NULL,
  `coments` varchar(200) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Indices de la tabla `sinister`
--
ALTER TABLE `sinister`
 ADD PRIMARY KEY (`id_sinister`), ADD KEY `id_car` (`id_car`), ADD KEY `type_sinister` (`type_sinister`);

 --
-- AUTO_INCREMENT de la tabla `sinister`
--
ALTER TABLE `sinister`
MODIFY `id_sinister` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `sinister`
ADD CONSTRAINT `sinister_ibfk_1` FOREIGN KEY (`id_car`) REFERENCES `inventories` (`id_inventories`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `sinister_ibfk_2` FOREIGN KEY (`type_sinister`) REFERENCES `typesinister` (`id_typesinister`) ON DELETE CASCADE ON UPDATE CASCADE;
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
