CREATE DATABASE  IF NOT EXISTS `osirisACL` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `osiris`;
-- MySQL dump 10.13  Distrib 5.5.40, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: osirisACL
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
-- Table structure for table `iof_permission`
--

DROP TABLE IF EXISTS `iof_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `iof_permission` (
`id` int(10) unsigned NOT NULL,
  `permission_name` varchar(45) NOT NULL,
  `pathResource` varchar(100) NOT NULL,
  `resource_id` int(10) unsigned NOT NULL,
  `resourceName` varchar(45) DEFAULT NULL,
  `name_esp` varchar(80) DEFAULT NULL,
  `app` varchar(100) DEFAULT NULL,
  `name_menu` varchar(45) DEFAULT NULL,
  `sub_action` varchar(45) DEFAULT NULL,
  `agroup` varchar(100) DEFAULT NULL,
  `agroupName` varchar(100) DEFAULT NULL,
  `menutemp` varchar(100) DEFAULT NULL,
  `is_displayed_action` int(11) DEFAULT NULL,
  `displayed_order_action` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Indices de la tabla `iof_permission`
--
ALTER TABLE `iof_permission`
 ADD PRIMARY KEY (`id`), ADD KEY `resource_id` (`resource_id`);


--
-- AUTO_INCREMENT de la tabla `iof_permission`
--
ALTER TABLE `iof_permission`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

--
-- Filtros para la tabla `iof_permission`
--
ALTER TABLE `iof_permission`
ADD CONSTRAINT `iof_permission_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `iof_resource` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-01-02 19:31:33
