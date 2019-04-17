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
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id_products` int(10) NOT NULL AUTO_INCREMENT,
  `id_company` int(10) DEFAULT NULL,
  `id_fk_category` int(10) NOT NULL,
  `measuring_fk_id` int(11) NOT NULL,
  `acl_users_fk_id` int(4) NOT NULL,
  `p_name` varchar(45) NOT NULL,
  `p_description` text,
  `p_record_date` date DEFAULT NULL,
  `p_key` varchar(45) DEFAULT NULL,
  `p_photo` longtext,
  `expiration` date DEFAULT NULL,
  `review_product` int(11) NOT NULL,
  `p_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_products`),
  KEY `fk_products_category1_idx` (`id_fk_category`),
  KEY `fk_products_acl_users1_idx` (`acl_users_fk_id`),
  KEY `fk_products_acl_unit2` (`measuring_fk_id`),
  KEY `fk_supplier_id_supplier` (`id_company`),
  CONSTRAINT `fk_products_acl_unit2` FOREIGN KEY (`measuring_fk_id`) REFERENCES `measuring_unit` (`id_measuring`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_products_acl_users1` FOREIGN KEY (`acl_users_fk_id`) REFERENCES `iof_users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_products_category1` FOREIGN KEY (`id_fk_category`) REFERENCES `category` (`id_category`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_supplier_id_supplier` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
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
