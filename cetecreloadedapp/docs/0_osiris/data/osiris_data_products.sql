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
-- Dumping data for table `products`
--

INSERT INTO `products` VALUES (1,3,1,2,1,'Harina','','2014-01-24','','','2014-01-31',1,15.00),(2,5,1,2,1,'Mantequilla','Barra de mantequilla','2013-11-27','p-h2',NULL,'2014-01-15',1,16.00),(3,3,1,2,1,'Polvo para hornear','Polvo para preparacion de pastel','2013-11-27','p-h3',NULL,'2014-01-15',1,14.00),(4,3,1,2,1,'Huevo','Huevo de cascaron blanco','2013-11-27','p-h4',NULL,'2014-01-15',1,28.00),(5,3,1,2,1,'Azucar','Azucar morena','2013-11-27','p-h1',NULL,'2014-01-15',1,19.00),(6,3,1,2,1,'Vainilla','Vainilla liquida','2013-11-27','p-h5',NULL,'2014-01-15',1,15.00),(7,3,1,2,1,'Leche evaporada','Sin descripcion','2013-11-27','p-h6',NULL,'2014-01-15',1,12.00),(8,3,1,2,1,'Leche condensada','Suficiente','2013-11-27','p-h7',NULL,'2014-01-15',1,14.00),(9,5,1,2,1,'Media crema','Suficiente','2013-11-27','p-h8',NULL,'2014-01-15',1,16.00),(10,5,1,2,1,'Crema para batir','Crema para batir','2013-11-27','p-h9',NULL,'2014-01-15',1,60.00),(11,3,1,2,1,'Azucar pulverizada','Sin descripcion','2013-11-27','p-h10',NULL,'2014-01-15',1,35.00),(12,4,1,2,1,'Fresas','Tamaño regular','2013-11-27','p-h11',NULL,'2014-01-15',1,15.00),(13,4,1,2,1,'Kiwi','Maduros','2013-11-27','p-h12',NULL,'2014-01-15',1,30.00),(14,4,1,2,1,'Moras','En buen estado','2013-11-27','p-h13',NULL,'2014-01-15',1,20.00),(15,3,1,2,1,'Durazno en almibar','Duraznos de tamaño grande','2013-11-27','p-h14',NULL,'2014-01-15',1,24.00),(16,12,1,7,1,'materia fina',NULL,'2014-09-26',NULL,NULL,'2014-09-18',0,0.00),(17,10,1,7,1,'Moldes',NULL,'2014-11-06',NULL,NULL,'2021-11-16',0,100.00);
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-12-28 13:08:01
